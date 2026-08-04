<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Item;
use App\Models\Reposite;
use App\Models\Store;
use App\Models\Supplier;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

/**
 * Statistics dashboard.
 *
 * Read-only: every figure is aggregated in SQL from the existing tables, so no
 * schema change and no writes. A full 12-month aggregation over the orders
 * table measures ~85ms, which is why none of this is cached yet.
 *
 * Sign conventions follow the rest of the system, which names invoices after
 * the money side rather than the goods side:
 *   orders.type = 'in'  -> فاتورة بيع    (sale to a client)
 *   orders.type = 'out' -> فاتورة شراء   (purchase from a supplier)
 *   is_return = 1       -> the matching return
 */
class DashboardController extends Controller
{
    const CLIENT   = 'App\Models\Client';
    const SUPPLIER = 'App\Models\Supplier';
    const STORE    = 'App\Models\Store';

    /**
     * Latest operation_order_results row per detail, as a joinable subquery.
     *
     * Resolved with one GROUP BY pass plus a join on the winning id. The
     * obvious `where id = (select max(id) ... where order_details_id = r1...)`
     * form is a correlated subquery that MySQL re-runs for every one of the
     * ~13.5k result rows, and it measured 38s on its own.
     */
    const LATEST_RESULT_JOIN = "(select res.order_details_id, res.old_item_quantity
                                   from operation_order_results res
                                   join (select order_details_id, max(id) as mx
                                           from operation_order_results
                                          group by order_details_id) pick
                                     on pick.mx = res.id) as r";

    public function index(Request $request)
    {
        // ---- period ------------------------------------------------------
        // Default to the current month, but bounded by the latest date that
        // actually has data so the page is never blank on a quiet day.
        $latest = DB::table('orders')->max('date2') ?: Carbon::now()->toDateString();

        $from = $request->filled('date_from')
            ? Carbon::parse($request->date_from)->toDateString()
            : Carbon::parse($latest)->startOfMonth()->toDateString();
        $to = $request->filled('date_to')
            ? Carbon::parse($request->date_to)->toDateString()
            : $latest;

        if (strtotime($to) < strtotime($from)) {
            list($from, $to) = [$to, $from];
        }

        $branchId = $request->filled('branch_id') ? (int) $request->branch_id : null;

        // Same-length window immediately before the selected one, so the KPI
        // cards compare like with like instead of a full month against a part
        // of a month.
        $days = Carbon::parse($from)->diffInDays(Carbon::parse($to));
        $prevTo   = Carbon::parse($from)->subDay()->toDateString();
        $prevFrom = Carbon::parse($prevTo)->subDays($days)->toDateString();

        // ---- KPI cards ---------------------------------------------------
        $sales     = $this->invoiceTotals($from, $to, 'in',  0, $branchId);
        $purchases = $this->invoiceTotals($from, $to, 'out', 0, $branchId);
        $returns   = $this->invoiceTotals($from, $to, 'out', 1, $branchId);
        $collected = $this->collectedTotal($from, $to, $branchId);

        $prevSales     = $this->invoiceTotals($prevFrom, $prevTo, 'in',  0, $branchId);
        $prevPurchases = $this->invoiceTotals($prevFrom, $prevTo, 'out', 0, $branchId);
        $prevCollected = $this->collectedTotal($prevFrom, $prevTo, $branchId);

        $receivables = (float) DB::table('clients')
            ->whereRaw("cast(nullif(balance,'') as decimal(18,2)) > 0")
            ->sum(DB::raw("cast(nullif(balance,'') as decimal(18,2))"));

        $kpi = [
            'sales' => [
                'label' => 'المبيعات', 'icon' => 'fa-line-chart', 'colour' => 'aqua',
                'value' => $sales->total, 'count' => $sales->count, 'unit' => 'ج.م',
                'sub' => number_format($sales->count) . ' فاتورة',
                'change' => $this->pctChange($sales->total, $prevSales->total),
            ],
            'purchases' => [
                'label' => 'المشتريات', 'icon' => 'fa-shopping-cart', 'colour' => 'yellow',
                'value' => $purchases->total, 'count' => $purchases->count, 'unit' => 'ج.م',
                'sub' => number_format($purchases->count) . ' فاتورة',
                'change' => $this->pctChange($purchases->total, $prevPurchases->total),
            ],
            'collected' => [
                'label' => 'التحصيلات', 'icon' => 'fa-money', 'colour' => 'green',
                'value' => $collected, 'unit' => 'ج.م',
                'sub' => $sales->total > 0
                    ? 'معدل التحصيل ' . number_format($collected / $sales->total * 100, 1) . '%'
                    : 'لا مبيعات في الفترة',
                'change' => $this->pctChange($collected, $prevCollected),
            ],
            // No receivables card by request. $receivables is still computed
            // above because the top-debtors table needs it for each client's
            // share of the total.
            'safes' => [
                'label' => 'رصيد الخزن', 'icon' => 'fa-university', 'colour' => 'blue',
                'value' => (float) DB::table('reposites')->sum(DB::raw("cast(nullif(balance,'') as decimal(18,2))")),
                'unit' => 'ج.م',
                'sub' => Reposite::count() . ' خزنة',
                'change' => null,
            ],
            'people' => [
                'label' => 'العملاء والموظفين', 'icon' => 'fa-users', 'colour' => 'purple',
                'value' => Client::count(), 'unit' => 'عميل',
                'sub' => Employee::where('active', 1)->count() . ' موظف نشط من ' . Employee::count(),
                'change' => null,
            ],
        ];

        // ---- row 2: 12-month trend --------------------------------------
        $trend = $this->monthlyTrend(12, $branchId);

        // ---- row 3: branches --------------------------------------------
        $branchStats = $this->branchStats($from, $to);

        // ---- row 4: top lists -------------------------------------------
        $topDebtors  = $this->topDebtors(8, $receivables);
        $topItems    = $this->topItems($from, $to, $branchId, 8);
        $safes       = DB::table('reposites')
            ->select('name', DB::raw("cast(nullif(balance,'') as decimal(18,2)) as bal"))
            ->orderByDesc('bal')->get();
        $lowStock    = $this->lowStock(8);

        // ---- row 5: alerts ----------------------------------------------
        $alerts = $this->alerts();

        // ---- extras ------------------------------------------------------
        $aging   = $this->aging();
        $factory = $this->factory($from, $to, $branchId);

        $branches = Branch::select('id', 'name')->orderBy('name')->get();

        // Quick ranges, anchored on the latest day that has data rather than on
        // today. Anchoring on today would hand the user an empty period whenever
        // the database is a day or two behind, which is what "I picked yesterday
        // and got zero" was.
        $presets = $this->presets($latest, $from, $to);

        return view('dashboard.index', compact(
            'kpi', 'trend', 'branchStats', 'topDebtors', 'topItems', 'safes', 'lowStock',
            'alerts', 'aging', 'factory', 'branches', 'presets',
            'from', 'to', 'prevFrom', 'prevTo', 'branchId', 'latest'
        ));
    }

    // =====================================================================
    // helpers
    // =====================================================================

    /**
     * Quick-range shortcuts for the filter bar.
     *
     * Anchored on $latest (the newest invoice date) instead of today, so every
     * shortcut lands on a window that actually contains data.
     */
    private function presets($latest, $from, $to)
    {
        $L = Carbon::parse($latest);

        $ranges = [
            'آخر يوم فيه بيانات' => [$L->copy(), $L->copy()],
            'آخر ٧ أيام'         => [$L->copy()->subDays(6), $L->copy()],
            'آخر ٣٠ يوم'         => [$L->copy()->subDays(29), $L->copy()],
            'الشهر الحالي'       => [$L->copy()->startOfMonth(), $L->copy()],
            'الشهر السابق'       => [$L->copy()->subMonthNoOverflow()->startOfMonth(),
                                     $L->copy()->subMonthNoOverflow()->endOfMonth()],
            'آخر ٣ شهور'         => [$L->copy()->subMonthsNoOverflow(3), $L->copy()],
            'السنة الحالية'      => [$L->copy()->startOfYear(), $L->copy()],
        ];

        $out = [];
        foreach ($ranges as $label => $r) {
            $f = $r[0]->format('Y-m-d');
            $t = $r[1]->format('Y-m-d');
            $out[] = [
                'label'  => $label,
                'from'   => $f,
                'to'     => $t,
                'active' => ($f === $from && $t === $to),
            ];
        }
        return $out;
    }

    /** Count + value of invoices of one kind in a window. */
    private function invoiceTotals($from, $to, $type, $isReturn, $branchId)
    {
        return DB::table('orders')
            ->selectRaw('count(*) as count, coalesce(sum(final_total),0) as total')
            ->where('type', $type)
            ->where('is_return', $isReturn)
            ->whereBetween('date2', [$from, $to])
            ->when($branchId, function ($q) use ($branchId) {
                return $q->where('branch_id', $branchId);
            })
            ->first();
    }

    /** Money actually received from clients in the window (posted only). */
    private function collectedTotal($from, $to, $branchId)
    {
        return (float) DB::table('accounts')
            ->where('type', 'in')
            ->where('pending', 0)
            ->where('accountable_type', self::CLIENT)
            ->whereBetween('date', [$from, $to])
            ->when($branchId, function ($q) use ($branchId) {
                return $q->where('branch_id', $branchId);
            })
            ->sum('cost');
    }

    private function pctChange($now, $before)
    {
        $now = (float) $now; $before = (float) $before;
        if ($before <= 0) return null;
        return round(($now - $before) / $before * 100, 1);
    }

    /** Sales vs purchases vs collections, by month. */
    private function monthlyTrend($months, $branchId)
    {
        $start = Carbon::parse(DB::table('orders')->max('date2') ?: Carbon::now())
            ->startOfMonth()->subMonths($months - 1)->toDateString();

        $rows = DB::table('orders')
            ->selectRaw("left(date2,7) as m, type, is_return, count(*) as n, coalesce(sum(final_total),0) as v")
            ->where('date2', '>=', $start)
            ->when($branchId, function ($q) use ($branchId) {
                return $q->where('branch_id', $branchId);
            })
            ->groupBy('m', 'type', 'is_return')
            ->get();

        $pay = DB::table('accounts')
            ->selectRaw("left(date,7) as m, coalesce(sum(cost),0) as v")
            ->where('type', 'in')->where('pending', 0)
            ->where('accountable_type', self::CLIENT)
            ->where('date', '>=', $start)
            ->when($branchId, function ($q) use ($branchId) {
                return $q->where('branch_id', $branchId);
            })
            ->groupBy('m')->pluck('v', 'm');

        $out = [];
        for ($i = 0; $i < $months; $i++) {
            $m = Carbon::parse($start)->addMonths($i)->format('Y-m');
            $out[$m] = ['month' => $m, 'sales' => 0.0, 'purchases' => 0.0, 'collected' => (float) ($pay[$m] ?? 0), 'count' => 0];
        }
        foreach ($rows as $r) {
            if (!isset($out[$r->m])) continue;
            if ($r->type == 'in' && !$r->is_return) {
                $out[$r->m]['sales'] += (float) $r->v;
                $out[$r->m]['count'] += (int) $r->n;
            } elseif ($r->type == 'out' && !$r->is_return) {
                $out[$r->m]['purchases'] += (float) $r->v;
            }
        }
        return array_values($out);
    }

    /**
     * Per-branch sales. Average invoice value is the interesting column: it
     * separates wholesale branches from retail ones.
     */
    private function branchStats($from, $to)
    {
        return DB::table('orders as o')
            ->leftJoin('branches as b', 'b.id', '=', 'o.branch_id')
            ->selectRaw("coalesce(b.name,'(بدون فرع)') as name, count(*) as n,
                         coalesce(sum(o.final_total),0) as v,
                         coalesce(avg(o.final_total),0) as avg_ticket")
            ->where('o.type', 'in')->where('o.is_return', 0)
            ->whereBetween('o.date2', [$from, $to])
            ->groupBy('b.id', 'b.name')
            ->orderByDesc('v')
            ->get();
    }

    private function topDebtors($limit, $totalReceivables)
    {
        $rows = DB::table('clients')
            ->select('id', 'name', DB::raw("cast(nullif(balance,'') as decimal(18,2)) as bal"))
            ->whereRaw("cast(nullif(balance,'') as decimal(18,2)) > 0")
            ->orderByDesc('bal')->limit($limit)->get();

        foreach ($rows as $r) {
            $r->share = $totalReceivables > 0 ? round($r->bal / $totalReceivables * 100, 1) : 0;
        }
        return $rows;
    }

    private function topItems($from, $to, $branchId, $limit)
    {
        return DB::table('order_details as od')
            ->join('orders as o', 'o.id', '=', 'od.order_id')
            ->join('items as i', 'i.id', '=', 'od.item_id')
            ->selectRaw('i.name, sum(od.quantity) as qty,
                         coalesce(sum(od.quantity * od.unite_price),0) as value')
            ->where('o.type', 'in')->where('o.is_return', 0)
            ->whereBetween('o.date2', [$from, $to])
            ->when($branchId, function ($q) use ($branchId) {
                return $q->where('o.branch_id', $branchId);
            })
            ->groupBy('i.id', 'i.name')
            ->orderByDesc('qty')
            ->limit($limit)->get();
    }

    /** Lowest positive stock, plus anything negative (which is a data problem). */
    private function lowStock($limit)
    {
        return DB::table('quantities as q')
            ->join('items as i', 'i.id', '=', 'q.item_id')
            ->leftJoin('stores as s', 's.id', '=', 'q.ownerable_id')
            ->select('i.name', 'q.quantity', 's.name as store')
            ->where('q.ownerable_type', self::STORE)
            ->where('q.quantity', '<=', 0)
            ->orderBy('q.quantity')
            ->limit($limit)->get();
    }

    private function alerts()
    {
        return [
            ['label' => 'فواتير معلّقة', 'n' => DB::table('orders')->where('status', 'pending')->count(),
             'url' => url('/orders/pending'), 'colour' => 'yellow', 'icon' => 'fa-file-text-o'],
            ['label' => 'دفعات معلّقة', 'n' => DB::table('accounts')->where('pending', 1)->count(),
             'url' => url('/pending-pays'), 'colour' => 'yellow', 'icon' => 'fa-money'],
            ['label' => 'سطور بانتظار التحميل', 'n' => DB::table('order_details')->where('load_pending', 1)->count(),
             'url' => url('/pending-load'), 'colour' => 'aqua', 'icon' => 'fa-truck'],
            ['label' => 'أصناف رصيدها سالب', 'n' => DB::table('quantities')->where('quantity', '<', 0)->count(),
             'url' => url('/items'), 'colour' => 'red', 'icon' => 'fa-exclamation-circle'],
            ['label' => 'سلف غير مسددة', 'n' => DB::table('loans')
                ->whereRaw("cast(coalesce(paid_value,0) as decimal(18,2)) < cast(coalesce(cost,0) as decimal(18,2))")->count(),
             'url' => url('/loans'), 'colour' => 'purple', 'icon' => 'fa-hand-o-right'],
        ];
    }

    /**
     * Receivables split by how long the client has been carrying a balance.
     * Anchored on the client's most recent sale, since that is the closest
     * thing to an invoice date available on the running balance.
     */
    private function aging()
    {
        $latest = DB::table('orders')->max('date2') ?: Carbon::now()->toDateString();

        $rows = DB::table('clients as c')
            ->leftJoin(DB::raw("(select ownerable_id, max(date2) as last_sale
                                   from orders
                                  where ownerable_type = '" . self::CLIENT . "'
                                    and type = 'in' and is_return = 0
                                  group by ownerable_id) as o"),
                       'o.ownerable_id', '=', 'c.id')
            ->selectRaw("cast(nullif(c.balance,'') as decimal(18,2)) as bal,
                         datediff('$latest', o.last_sale) as age")
            ->whereRaw("cast(nullif(c.balance,'') as decimal(18,2)) > 0")
            ->get();

        $buckets = [
            '0-30 يوم'   => 0.0,
            '31-60 يوم'  => 0.0,
            '61-90 يوم'  => 0.0,
            'أكثر من 90' => 0.0,
            'بدون حركة'  => 0.0,
        ];
        foreach ($rows as $r) {
            $bal = (float) $r->bal;
            if ($r->age === null)   $buckets['بدون حركة']  += $bal;
            elseif ($r->age <= 30)  $buckets['0-30 يوم']   += $bal;
            elseif ($r->age <= 60)  $buckets['31-60 يوم']  += $bal;
            elseif ($r->age <= 90)  $buckets['61-90 يوم']  += $bal;
            else                    $buckets['أكثر من 90'] += $bal;
        }
        return $buckets;
    }

    /** Factory throughput and waste ratio for the window. */
    private function factory($from, $to, $branchId)
    {
        // Joined on operation_orders rather than plucking ids into an IN list:
        // a full year is thousands of ids, which pushed the page to 27s and
        // then failed outright.
        $orders = (int) DB::table('operation_orders')->whereBetween('date', [$from, $to])->count();

        if (!$orders) {
            return ['orders' => 0, 'input' => 0.0, 'output' => 0.0, 'waste' => 0.0, 'waste_pct' => null];
        }

        $input = (float) DB::table('operation_order_details as d')
            ->join('operation_orders as oo', 'oo.id', '=', 'd.operation_order_id')
            ->leftJoin(DB::raw(self::LATEST_RESULT_JOIN), 'r.order_details_id', '=', 'd.id')
            ->whereBetween('oo.date', [$from, $to])
            ->sum(DB::raw('coalesce(nullif(r.old_item_quantity,0), d.old_item_quantity)'));

        $output = (float) DB::table('operation_order_results as r')
            ->join('operation_order_details as d', 'd.id', '=', 'r.order_details_id')
            ->join('operation_orders as oo', 'oo.id', '=', 'd.operation_order_id')
            ->whereBetween('oo.date', [$from, $to])
            ->sum('r.actual_output');

        $waste = (float) DB::table('operation_order_result_details as rd')
            ->join('operation_order_details as d', 'd.id', '=', 'rd.order_details_id')
            ->join('operation_orders as oo', 'oo.id', '=', 'd.operation_order_id')
            ->whereBetween('oo.date', [$from, $to])
            ->whereIn('rd.damage_type', ['scrap', 'pieces'])
            ->sum('rd.damage_weight');

        return [
            'orders'    => $orders,
            'input'     => $input,
            'output'    => $output,
            'waste'     => $waste,
            'waste_pct' => $input > 0 ? round($waste / $input * 100, 2) : null,
        ];
    }
}
