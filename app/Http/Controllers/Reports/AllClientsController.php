<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Client;
use App\Models\Order;
use App\Models\Branch;

use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use DB;

class AllClientsController extends Controller
{
    //
    public function index(Request $request)
    {
        $clients = Client::latest()->get();
        $query = Client::query();

        if ($request->client_id > 0)
            $query->where('id', $request->client_id);

        $query->where('balance','!=',0);
        
        // Get client IDs
        $clientIds = $query->pluck('id');
        
        // Get latest order IDs and branch IDs for each client using a subquery
        $latestOrders = Order::select('ownerable_id', DB::raw('MAX(id) as latest_order_id'))
            ->where('ownerable_type', 'App\Models\Client')
            ->where('type', 'in')
            ->whereIn('ownerable_id', $clientIds)
            ->groupBy('ownerable_id')
            ->get();
        
        // Get order IDs to fetch branch information
        $orderIds = $latestOrders->pluck('latest_order_id');
        
        // Get branch and date information for the latest orders
        $ordersWithBranches = Order::select('id', 'branch_id', 'date')
            ->whereIn('id', $orderIds)
            ->with('branch:id,name')
            ->get()
            ->keyBy('id');
        
        // Create a map of client_id => latest_order_id
        $latestOrderMap = $latestOrders->pluck('latest_order_id', 'ownerable_id');
        
        // Get all branches for the filter dropdown
        $branches = Branch::select('id', 'name')->orderBy('name')->get();

        $resources = $query->get()->map(function($client) use ($latestOrderMap, $ordersWithBranches) {
            $latestOrderId = $latestOrderMap[$client->id] ?? null;
            $client->latest_order_id = $latestOrderId;
            
            if ($latestOrderId && isset($ordersWithBranches[$latestOrderId])) {
                $order = $ordersWithBranches[$latestOrderId];
                $client->latest_order_branch_name = $order->branch ? $order->branch->name : null;
                $client->latest_order_branch_id = $order->branch_id;
                $client->latest_order_date = $order->date ? Carbon::parse($order->date)->format('Y-m-d') : null;
                $client->latest_order_date_display = $order->date ? Carbon::parse($order->date)->format('Y-m-d') : null;
            } else {
                $client->latest_order_branch_name = null;
                $client->latest_order_branch_id = null;
                $client->latest_order_date = null;
                $client->latest_order_date_display = null;
            }
            
            return $client;
        });

        return view('reports.all-clients',compact('resources','clients','branches'));
    }

    public function accounts(Request $request)
    {

        $query = Client::select('id', 'name', 'init', 'balance')->where('balance','>',0)->latest();
        if (!$request->from) {
            $query = collect([]);
        }
        return Datatables::of($query)
            ->editColumn('balance', function (Client $client) use ($request) {
                $balance = $client->init;
                $orderIn = $client->orders()
                    ->whereHas('orderDetails', function ($query) {
                        $query
                            ->where('load_pending', false)
                            ->orWhere('price_pending', false);
                    })
                    ->where('type', 'in')
                    ->whereBetween('date', [
                        $request->from,
                        $request->to,
                    ])->sum('final_total');
                $orderOut = $client->orders()
                    ->where('type', 'out')
                    ->whereHas('orderDetails', function ($query) {
                        $query
                            ->where('load_pending', false)
                            ->orWhere('price_pending', false);
                    })
                    ->whereBetween('date', [
                        $request->from,
                        $request->to,
                    ])->sum('final_total');
                $cost = $client->accounts()
                    ->where('pending', false)
                    ->where('type', 'in')->whereBetween('date', [
                        $request->from,
                        $request->to,
                    ])->sum('cost');
                $balance += $orderIn - ($orderOut + $cost);
                return $balance;
            })
            ->make(true);

    }
}
