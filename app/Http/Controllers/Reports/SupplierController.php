<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Supplier;
use App\Models\Account;
use App\Models\Order;

use Yajra\Datatables\Datatables;

use Carbon\Carbon;


class SupplierController extends Controller
{
    private $balance = 0;


    public function getStartDateOfDaily($supplier_id) { 
        $startDailyDate = "";
        try{
            $date = Supplier::join("orders", "suppliers.id", "=", "ownerable_id")
                ->join("accounts", "suppliers.id", "=", 'accountable_id')
                ->where('accounts.accountable_type', 'App\Models\Supplier')
                ->where('orders.ownerable_type', 'App\Models\Supplier')
                ->where("suppliers.id", $supplier_id)
                ->first(["orders.date as d1", "accounts.date as d2"]); 
         
            $startDailyDate = min(strtotime($date->d1), strtotime($date->d2));
            
            return date("Y-m-d", $startDailyDate);
        }catch(\Exception $e){
            // start date of the system
            return "2018-07-01";
        }
    }
    
    public function getLastBalance(Request $request) {
        $suppliers = Supplier::select('name', 'id')->latest()->get();
        $orders_out = [];
        $orders_in = [];
        $pay_in = [];
        $pay_out = [];
        $returns = [];
        $supplier = '';
        $from = '';
        $to = '';
        $supplier_accounts = [];
        $s_balance = 0;
        $rclone = new Request(); 
        $r = "";

        //$afterDateOfZero = date('Y-m-d', strtotime($this->getLastDateOfZeroBalance($rclone) . ' + 1 days'));


        if ($request->from != null && $request->to != null && $request->supplier_id != null) {
            $from = $request->from;
            $to = $request->to;
            $supplier = Supplier::where('id', $request->supplier_id)->first();
 
            $s_balance = $supplier->init;  
             
            if (strtotime($request->to) <= strtotime($request->from))
                return (float)$s_balance;

                $out = Order::where('type', 'out')
                    ->where('is_return', false)
                    ->whereBetween('date', [$from, $to])
          
                    ->where([
                        'ownerable_type' => 'App\Models\Supplier',
                        'ownerable_id' => $request->supplier_id,
                    ])->get();
            

                $order_return = Order::where('type', 'out')
                    ->where('is_return', true)
                    ->whereBetween('date', [$from, $to])
                    ->where([
                        'ownerable_type' => 'App\Models\Supplier',
                        'ownerable_id' => $request->supplier_id,
                    ])->get();
             
                $in = Order::where('type', 'in')
                    ->where('is_return', false)
                    ->whereBetween('date', [$from, $to])                 
                    ->where([
                        'ownerable_type' => 'App\Models\Supplier',
                        'ownerable_id' => $request->supplier_id,
                    ])
                    ->get();
              
                $pin = Account::with('reposite')->where('type', 'in')
                    ->whereBetween('date', [$from, $to])            
                    ->where('pending', false)
                    ->where('accountable_id', $request->supplier_id)
                    ->where('accountable_type', 'App\Models\Supplier')
                    ->get();
              
                $pout = Account::with('reposite')->where('accounts.type', 'out')
                     ->whereBetween('date', [$from, $to])
                    ->where('pending', false)
                    ->where('accountable_id', $request->supplier_id)
                    ->where('accountable_type', 'App\Models\Supplier')
                    ->get();
             
                if ($pout->sum('cost') > 0 || $pin->sum('cost') || $in->sum('final_total') > 0 || $order_return->sum('final_total') > 0 || $out->sum('final_total') > 0){
                $s_balance= $s_balance + ( $out->sum('final_total') - $in->sum('final_total') - $order_return->sum('final_total') )-($pout->sum('cost') - $pin->sum('cost'))  ;
                 
                }

            
        }
        
        return $s_balance;
    }

    // new function
    public function index(Request $request) {
        $suppliers = Supplier::select('name', 'id')->latest()->get();
        $orders_out = [];
        $orders_in = [];
        $pay_in = [];
        $pay_out = [];
        $returns = [];
        $supplier = '';
        $from = '';
        $to = '';
        $supplier_accounts = [];
        $s_balance = 0;
        $rclone = new Request(); 
        //$afterDateOfZero = date('Y-m-d', strtotime($this->getLastDateOfZeroBalance($rclone) . ' + 1 days'));


        if ($request->has('from') && $request->from != null && $request->has('to') && $request->to != null && $request->has('supplier_id') && $request->supplier_id != null) {
            $from = $request->from;
            $to = $request->to;
            $supplier = Supplier::where('id', $request->supplier_id)->first();
 
            $startDate = $this->getStartDateOfDaily($request->supplier_id); 
              
            $rclone->from = $startDate;
            
            $rclone->to =  date('Y-m-d',(strtotime ( '-1 day' , strtotime ($request->from) ) ));// date("Y-m-d", $request->from);
            $rclone->supplier_id = $request->supplier_id;
            $s_balance = $this->getLastBalance($rclone);
             
              
            for ($i = 0; $i <= Carbon::parse($request->from)->diffInDays(Carbon::parse($request->to)); $i++) {
                $out = Order::where('type', 'out')
                    ->where('is_return', false)
                    ->whereDate('date', Carbon::parse($request->from)->addDays($i))
                    // ->whereHas('orderDetails', function ($query) {
                    //     $query->where('load_pending', false)->orWhere('price_pending', false);
                    // })
                    ->where([
                        'ownerable_type' => 'App\Models\Supplier',
                        'ownerable_id' => $request->supplier_id,
                    ])->get();
                if (count($out) > 0) {
                    foreach ($out as $item) {
                        array_push($orders_out, $item);
                    }
                }

                $order_return = Order::where('type', 'out')
                    ->where('is_return', true)
                    ->whereDate('date',Carbon::parse($request->from)->addDays($i))
                    // ->whereHas('orderDetails', function ($query) {
                    //     $query->where('load_pending', false)->orWhere('price_pending', false);
                    // })
                    ->where([
                        'ownerable_type' => 'App\Models\Supplier',
                        'ownerable_id' => $request->supplier_id,
                    ])->get();
                if (count($order_return) > 0) {
                    foreach ($order_return as $item) {
                        array_push($returns, $item);
                    }
                }
                $in = Order::where('type', 'in')
                    ->where('is_return', false)
                    ->whereDate('date',Carbon::parse($request->from)->addDays($i))
                    // ->whereHas('orderDetails', function ($query) {
                    //     $query
                    //         ->where('load_pending', false)
                    //         ->orWhere('price_pending', false);
                    // })
                    ->where([
                        'ownerable_type' => 'App\Models\Supplier',
                        'ownerable_id' => $request->supplier_id,
                    ])
                    ->get();
                if (count($in) > 0) {
                    foreach ($in as $item) {
                        array_push($orders_in, $item);
                    }
                }
                $pin = Account::with('reposite')->where('type', 'in')
                    ->whereDate('date',Carbon::parse($request->from)->addDays($i))
                    ->where('pending', false)
                    ->where('accountable_id', $request->supplier_id)
                    ->where('accountable_type', 'App\Models\Supplier')
                    ->get();
                if (count($pin) > 0) {
                    foreach ($pin as $item) {
                        array_push($pay_in, $item);
                    }
                }
                $pout = Account::with('reposite')->where('accounts.type', 'out')
                    ->whereDate('date',Carbon::parse($request->from)->addDays($i))
                    ->where('pending', false)
                    ->where('accountable_id', $request->supplier_id)
                    ->where('accountable_type', 'App\Models\Supplier')
                    ->get();
                if (count($pout) > 0) {
                    foreach ($pout as $item) {
                        array_push($pay_out, $item);
                    }
                }
                if ($pout->sum('cost') > 0 || $pin->sum('cost') || $in->sum('final_total') > 0 || $order_return->sum('final_total') > 0 || $out->sum('final_total') > 0){
$s_balance= $s_balance + ( $out->sum('final_total') - $in->sum('final_total') - $order_return->sum('final_total') )-($pout->sum('cost') - $pin->sum('cost'))  ;
                    array_push($supplier_accounts,[
                        'date'=>Carbon::parse($request->from)->addDays($i)->format('Y-m-d'),
                        'order_out'=>$out->sum('final_total'),
                        'order_in'=>$in->sum('final_total'),
                        'return'=>$order_return->sum('final_total'),
                        'pay_in'=>$pin->sum('cost'),
                        'pay_out'=>$pout->sum('cost'),
                        'balance'=>round($s_balance)
                    ]);
                }

            }
        }
        return view('reports.supplier', compact('from', 'to', 'supplier', 'suppliers', 'orders_in', 'orders_out', 'pay_in', 'pay_out', 'returns', 'supplier_accounts'));
    }


    public function getSupplierBalance(Request $request) { 
        $orders_out = [];
        $orders_in = [];
        $pay_in = [];
        $pay_out = [];
        $returns = [];
        $supplier = '';
        $from = '';
        $to = '';
        $supplier_accounts = [];
        $s_balance = 0;
        $rclone = new Request(); 
        //$afterDateOfZero = date('Y-m-d', strtotime($this->getLastDateOfZeroBalance($rclone) . ' + 1 days'));


        if ($request->has('from') && $request->from != null && $request->has('to') && $request->to != null && $request->has('supplier_id') && $request->supplier_id != null) {
            $from = $request->from;
            $to = $request->to;
            $supplier = Supplier::where('id', $request->supplier_id)->first();
 
            $startDate = $this->getStartDateOfDaily($request->supplier_id); 
              
            $rclone->from = $startDate;
            
            $rclone->to =  date('Y-m-d',(strtotime ( '-1 day' , strtotime ($request->from) ) ));// date("Y-m-d", $request->from);
            $rclone->supplier_id = $request->supplier_id;
            $s_balance = $this->getLastBalance($rclone);
             
              
            for ($i = 0; $i <= Carbon::parse($request->from)->diffInDays(Carbon::parse($request->to)); $i++) {
                $out = Order::where('type', 'out')
                    ->where('is_return', false)
                    ->whereDate('date', Carbon::parse($request->from)->addDays($i))
                    // ->whereHas('orderDetails', function ($query) {
                    //     $query->where('load_pending', false)->orWhere('price_pending', false);
                    // })
                    ->where([
                        'ownerable_type' => 'App\Models\Supplier',
                        'ownerable_id' => $request->supplier_id,
                    ])->get();
                if (count($out) > 0) {
                    foreach ($out as $item) {
                        array_push($orders_out, $item);
                    }
                }

                $order_return = Order::where('type', 'out')
                    ->where('is_return', true)
                    ->whereDate('date',Carbon::parse($request->from)->addDays($i))
                    // ->whereHas('orderDetails', function ($query) {
                    //     $query->where('load_pending', false)->orWhere('price_pending', false);
                    // })
                    ->where([
                        'ownerable_type' => 'App\Models\Supplier',
                        'ownerable_id' => $request->supplier_id,
                    ])->get();
                if (count($order_return) > 0) {
                    foreach ($order_return as $item) {
                        array_push($returns, $item);
                    }
                }
                $in = Order::where('type', 'in')
                    ->where('is_return', false)
                    ->whereDate('date',Carbon::parse($request->from)->addDays($i))
                    // ->whereHas('orderDetails', function ($query) {
                    //     $query
                    //         ->where('load_pending', false)
                    //         ->orWhere('price_pending', false);
                    // })
                    ->where([
                        'ownerable_type' => 'App\Models\Supplier',
                        'ownerable_id' => $request->supplier_id,
                    ])
                    ->get();
                if (count($in) > 0) {
                    foreach ($in as $item) {
                        array_push($orders_in, $item);
                    }
                }
                $pin = Account::with('reposite')->where('type', 'in')
                    ->whereDate('date',Carbon::parse($request->from)->addDays($i))
                    ->where('pending', false)
                    ->where('accountable_id', $request->supplier_id)
                    ->where('accountable_type', 'App\Models\Supplier')
                    ->get();
                if (count($pin) > 0) {
                    foreach ($pin as $item) {
                        array_push($pay_in, $item);
                    }
                }
                $pout = Account::with('reposite')->where('accounts.type', 'out')
                    ->whereDate('date',Carbon::parse($request->from)->addDays($i))
                    ->where('pending', false)
                    ->where('accountable_id', $request->supplier_id)
                    ->where('accountable_type', 'App\Models\Supplier')
                    ->get();
                if (count($pout) > 0) {
                    foreach ($pout as $item) {
                        array_push($pay_out, $item);
                    }
                }
                if ($pout->sum('cost') > 0 || $pin->sum('cost') || $in->sum('final_total') > 0 || $order_return->sum('final_total') > 0 || $out->sum('final_total') > 0){
$s_balance= $s_balance + ( $out->sum('final_total') - $in->sum('final_total') - $order_return->sum('final_total') )-($pout->sum('cost') - $pin->sum('cost'))  ;

                    $index = strtotime(Carbon::parse($request->from)->addDays($i)) . '';
                    $supplier_accounts[$index] = [
                        'date'=>Carbon::parse($request->from)->addDays($i)->format('Y-m-d'),
                        'order_out'=>$out->sum('final_total'),
                        'order_in'=>$in->sum('final_total'),
                        'return'=>$order_return->sum('final_total'),
                        'pay_in'=>$pin->sum('cost'),
                        'pay_out'=>$pout->sum('cost'),
                        'balance'=>round($s_balance)
                    ]; 
                }

            }
        }
        
        return [
           "from" => $from,
           "to" => $to,
           "supplier" => $supplier, 
           "orders_in" => $orders_in,
           "orders_out" => $orders_out,
           "pay_in" => $pay_in,
           "pay_out" => $pay_out,
           "returns" => $returns,
           "supplier_accounts" => $supplier_accounts  
        ];
        //return view('reports.supplier', compact('from', 'to', 'supplier', 'suppliers', 'orders_in', 'orders_out', 'pay_in', 'pay_out', 'returns', 'supplier_accounts'));
    }
    
    public function index1(Request $request)
    { 
//return $request->all();
        $suppliers = Supplier::select('name', 'id')->latest()->get();
        $orders_out = [];
        $orders_in = [];
        $pay_in = [];
        $pay_out = [];
        $returns = [];
        $supplier = '';
        $from = '';
        $to = '';
        $supplier_accounts = [];
        $s_balance=0;
        if ($request->has('from') && $request->from != null && $request->has('to') && $request->to != null && $request->has('supplier_id') && $request->supplier_id != null) {
            $from = $request->from;
            $to = $request->to;
            $supplier = Supplier::where('id', $request->supplier_id)->first();
            $s_balance=$supplier->init;
            for ($i = 0; $i <= Carbon::parse($request->from)->diffInDays(Carbon::parse($request->to)); $i++) {
                $out = Order::where('type', 'out')
                    ->where('is_return', false)
                    ->whereDate('date', Carbon::parse($request->from)->addDays($i))
                    // ->whereHas('orderDetails', function ($query) {
                    //     $query->where('load_pending', false)->orWhere('price_pending', false);
                    // })
                    ->where([
                        'ownerable_type' => 'App\Models\Supplier',
                        'ownerable_id' => $request->supplier_id,
                    ])->get();
                if (count($out) > 0) {
                    foreach ($out as $item) {
                        array_push($orders_out, $item);
                    }
                }

                $order_return = Order::where('type', 'out')
                    ->where('is_return', true)
                    ->whereDate('date',Carbon::parse($request->from)->addDays($i))
                    // ->whereHas('orderDetails', function ($query) {
                    //     $query->where('load_pending', false)->orWhere('price_pending', false);
                    // })
                    ->where([
                        'ownerable_type' => 'App\Models\Supplier',
                        'ownerable_id' => $request->supplier_id,
                    ])->get();
                if (count($order_return) > 0) {
                    foreach ($order_return as $item) {
                        array_push($returns, $item);
                    }
                }
                $in = Order::where('type', 'in')
                    ->where('is_return', false)
                    ->whereDate('date',Carbon::parse($request->from)->addDays($i))
                    // ->whereHas('orderDetails', function ($query) {
                    //     $query
                    //         ->where('load_pending', false)
                    //         ->orWhere('price_pending', false);
                    // })
                    ->where([
                        'ownerable_type' => 'App\Models\Supplier',
                        'ownerable_id' => $request->supplier_id,
                    ])
                    ->get();
                if (count($in) > 0) {
                    foreach ($in as $item) {
                        array_push($orders_in, $item);
                    }
                }
                $pin = Account::with('reposite')->where('type', 'in')
                    ->whereDate('date',Carbon::parse($request->from)->addDays($i))
                    ->where('pending', false)
                    ->where('accountable_id', $request->supplier_id)
                    ->where('accountable_type', 'App\Models\Supplier')
                    ->get();
                if (count($pin) > 0) {
                    foreach ($pin as $item) {
                        array_push($pay_in, $item);
                    }
                }
                $pout = Account::with('reposite')->where('accounts.type', 'out')
                    ->whereDate('date',Carbon::parse($request->from)->addDays($i))
                    ->where('pending', false)
                    ->where('accountable_id', $request->supplier_id)
                    ->where('accountable_type', 'App\Models\Supplier')
                    ->get();
                if (count($pout) > 0) {
                    foreach ($pout as $item) {
                        array_push($pay_out, $item);
                    }
                }
                if ($pout->sum('cost') > 0 || $pin->sum('cost') || $in->sum('final_total') > 0 || $order_return->sum('final_total') > 0 || $out->sum('final_total') > 0){
$s_balance= $s_balance + ( $out->sum('final_total') - $in->sum('final_total') - $order_return->sum('final_total') )-($pout->sum('cost') - $pin->sum('cost'))  ;
                    array_push($supplier_accounts,[
                        'date'=>Carbon::parse($request->from)->addDays($i)->format('Y-m-d'),
                        'order_out'=>$out->sum('final_total'),
                        'order_in'=>$in->sum('final_total'),
                        'return'=>$order_return->sum('final_total'),
                        'pay_in'=>$pin->sum('cost'),
                        'pay_out'=>$pout->sum('cost'),
                        'balance'=>round($s_balance)
                    ]);
                }

            }

        }
//        return $supplier_accounts;
        return view('reports.supplier', compact('from', 'to', 'supplier', 'suppliers', 'orders_in', 'orders_out', 'pay_in', 'pay_out', 'returns','supplier_accounts'));
    }

    public function ordersIn(Request $request)
    {
        $query = Order::
        select('orders.id', 'orders.created_at', 'date', 'final_total', 'suppliers.name')
            ->join('suppliers', function ($join) {
                $join->on('suppliers.id', '=', 'orders.ownerable_id')
                    ->where('orders.ownerable_type', 'App\Models\Supplier');
            })
            ->where('type', 'in')
            ->whereBetween('date', [
                $request->from,
                $request->to
            ])
            ->whereHas('orderDetails', function ($query) {
                $query
                    ->where('load_pending', false)
                    ->orWhere('price_pending', false);
            })
            ->where([
                'ownerable_type' => 'App\Models\Supplier',
                'ownerable_id' => $request->supplier_id,
            ])
            ->latest();


        return Datatables::of($query)
            ->editColumn('date', function (Order $order) {
                return optional($order->date)->toDateString();
            })
            ->addColumn('rest', function (Order $order) {
                return $order->max;
            })
            ->addColumn('payed', function (Order $order) {
                return $order->payed;
            })
            ->make(true);
    }


    public function ordersOut(Request $request)
    {

        $query = Order::
        select('orders.id', 'orders.created_at', 'date', 'final_total', 'suppliers.name')
            ->join('suppliers', function ($join) {
                $join->on('suppliers.id', '=', 'orders.ownerable_id')
                    ->where('orders.ownerable_type', 'App\Models\Supplier');
            })
            ->where('type', 'out')
            ->whereBetween('date', [
                $request->from,
                $request->to
            ])
            ->whereHas('orderDetails', function ($query) {
                $query
                    ->where('load_pending', false)
                    ->orWhere('price_pending', false);
            })
            ->where([
                'ownerable_type' => 'App\Models\Supplier',
                'ownerable_id' => $request->supplier_id,
            ])
            ->latest();


        return Datatables::of($query)
            ->editColumn('date', function (Order $order) {
                return optional($order->date)->toDateString();
            })
            ->addColumn('rest', function (Order $order) {
                return $order->max;
            })
            ->addColumn('payed', function (Order $order) {
                return $order->payed;
            })
            ->make(true);
    }

    public function accountsIn(Request $request)
    {
        $query = Account::
        select('accounts.id', 'accounts.created_at', 'accounts.date', 'reposites.name', 'accounts.cost', 'orders.id as order', 'suppliers.name as supplier')
            ->join('suppliers', function ($join) {
                $join->on('suppliers.id', '=', 'accounts.accountable_id')
                    ->where('accounts.accountable_type', 'App\Models\Supplier');
            })
            ->where('accounts.type', 'in')
            ->whereBetween('accounts.date', [
                $request->from,
                $request->to
            ])
            ->leftJoin('orders', 'orders.id', '=', 'accounts.order_id')
            ->leftJoin('reposites', 'reposites.id', '=', 'accounts.reposite_id')
            ->where('pending', false)
            ->where('accountable_id', $request->supplier_id)
            ->latest();

        return Datatables::of($query)
            ->editColumn('date', function (Account $account) {
                return optional($account->date)->toDateString();
            })
            ->make(true);

    }


    public function accountsOut(Request $request)
    {
        $query = Account::select('accounts.id', 'accounts.created_at', 'accounts.date', 'reposites.name', 'accounts.cost', 'orders.id as order', 'suppliers.name as supplier')
            ->join('suppliers', function ($join) {
                $join->on('suppliers.id', '=', 'accounts.accountable_id')
                    ->where('accounts.accountable_type', 'App\Models\Supplier');
            })
            ->where('accounts.type', 'out')
            ->whereBetween('accounts.date', [
                $request->from,
                $request->to
            ])
            ->leftJoin('orders', 'orders.id', '=', 'accounts.order_id')
            ->leftJoin('reposites', 'reposites.id', '=', 'accounts.reposite_id')
            ->where('accountable_id', $request->supplier_id)
            ->where('pending', false)
            ->latest();

        return Datatables::of($query)
            ->editColumn('date', function (Account $account) {
                return optional($account->date)->toDateString();
            })
            ->make(true);

    }


    public function account(Request $request)
    {
        $collect = collect();
        $supplier = null;
        if ($request->supplier_id) {
            $from = Carbon::parse($request->from);
            $to = Carbon::parse($request->to);
            $supplier = Supplier::find($request->supplier_id);
            $balance = $supplier->init;
            for ($date = $from; $date->lte($to); $date->addDay()) {
                $d = $date->toDateString();
                $orderOut = $supplier
                    ->orders()
                    ->where('type', 'out')
                    ->whereHas('orderDetails', function ($query) {
                        $query
                            ->where('load_pending', false)
                            ->orWhere('price_pending', false);
                    })
                    ->whereDate('date', $d)
                    ->sum('final_total');
                $orderIn = $supplier
                    ->orders()
                    ->where('type', 'in')
                    ->whereHas('orderDetails', function ($query) {
                        $query
                            ->where('load_pending', false)
                            ->orWhere('price_pending', false);
                    })
                    ->whereDate('date', $d)
                    ->sum('final_total');
                $cost = $supplier
                    ->accounts()
                    ->where('type', 'out')
                    ->whereDate('date', $d)
                    ->where('pending', false)
                    ->sum('cost');

                $balance += $orderOut - ($orderIn + $cost);
                if ($orderIn || $orderOut || $cost) {
                    $collect->push([
                        'date' => $d,
                        'order_in' => $orderIn,
                        'order_out' => $orderOut,
                        'cost' => $cost,
                        'balance' => -$balance,
                    ]);
                }
            }
            $this->balance = $collect->last()['balance'];
        }

        return Datatables::of($collect)
            ->with(['balance' => $this->balance, 'supplier' => $supplier])
            ->make(true);


    }
}
