<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Yajra\Datatables\Datatables;

use App\Models\OrderDetail;
use App\Models\LoadDetail;
use App\Models\Order;
use App\Models\Load;

use App\DataTables\PendingLoadDataTable;

class PendingLoadController extends Controller
{
    public function updateOrder(Request $request)
    {
        foreach ($request->id as $id) {
            $detail = OrderDetail::findOrFail($id);
            $detail->update(['load_pending' => false]);
        }
        flash()->success('تم الحفظ بنجاح');
        return back();
    }

    public function updateLoad(int $id)
    {
        $detail = LoadDetail::where('id', $id)->first();
        $detail->performPendingLoad();
//        return $detail;
        flash()->success('تم الحفظ بنجاح');
        return back();
    }


    public function index(Request $request)
    {

        if ($request->has('type') && $request->type != null) {
            $type = $request->type;
        } else {
            $type = 'load';
        }
        $order_load_type_in = [];
        $order_load_type_out = [];
        $loads = [];

        if ($type == 'order_load_type_in_list') {
            $order_load_type_in = OrderDetail::with('order')
                ->whereHas('order',function ($q){
                    $q->where('type', 'in');
                })
                ->where('store_id', optional(auth()->user()->store)->id)
                ->where('load_pending', true)
                ->paginate(10);
//            return $order_load_type_in;
            /*$order_load_type_in = Order::with('user', 'ownerable', 'orderDetails')
                ->where('type', 'in')
                ->whereHas('orderDetails', function ($q) {
                    $q->where('store_id',optional(auth()->user()->store)->id);
                    $q->where('load_pending', true);
                })->paginate(10);*/

        } elseif ($type == 'order_load_type_out') {
//            $order_load_type_out = Order::with('user', 'ownerable', 'orderDetails')
//                ->where('type', 'out')
//                ->whereHas('orderDetails', function ($q) {
//                    $q->where('store_id', optional(auth()->user()->store)->id);
//                    $q->where('load_pending', true);
//                })->paginate(10);
            $order_load_type_out = OrderDetail::with('order')
                ->whereHas('order',function ($q){
                    $q->where('type', 'in');
                })
                ->where('store_id', optional(auth()->user()->store)->id)
                ->where('load_pending', true)
                ->paginate(10);
        } elseif ($type == 'load') {
//            $loads = LoadDetail::where('pending', true)->paginate(10);
            if(auth()->user()->id == 1){
                $loads = Load::with('user', 'loadDetails', 'from', 'to')
                ->whereHas('loadDetails', function ($query) {
                    $query->where('pending', true);
                })->paginate(10);
            }else{
                $loads = Load::with('user', 'loadDetails', 'from', 'to')
                ->whereHas('loadDetails', function ($query) {
                    $query->where('pending', true);
                })->paginate(10);
            }
           
                // ->paginate(10);
        }
        // return $loads;
        return view('pending-load.index', compact('order_load_type_in', 'order_load_type_out', 'loads', 'type'));
    }

    public function ordersDataTable(Request $request)
    {
        $query = Order::select('orders.created_at', 'orders.id', 'users.name as user', 'branches.name as branch', 'orders.ownerable_id', 'orders.ownerable_type')
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->leftJoin('branches', 'users.branch_id', '=', 'branches.id')
            ->leftJoin('order_details', 'order_details.order_id', '=', 'orders.id')
            ->whereHas('orderDetails', function ($query) {
                $query
                    ->where('store_id', optional(auth()->user()->store)->id)
                    ->where('load_pending', true);
            })
            ->where('type', $request->type)
            ->groupBy('orders.id')
            ->latest();
        return Datatables::of($query)
            ->addColumn('buyer', function (Order $order) {
                return optional($order->ownerable)->name;
            })
            ->addColumn('action', 'pending-load.orders.action')
            ->make(true);
    }

    public function ordersShow(Request $request)
    {
        $query = OrderDetail::
        select(
            'items.name as item',
            'order_details.quantity',
            'order_details.id'
        )
            ->leftJoin('items', 'items.id', '=', 'order_details.item_id')
            ->where('order_id', $request->order_id)
            ->where('store_id', auth()->user()->store->id)
            ->where('load_pending', true);
        return Datatables::of($query)
            ->addColumn('action', 'pending-load.orders.modal.action')
            ->make(true);
    }

    public function loadsDataTable(Request $request)
    {
        $query = Load::select('loads.created_at', 'stores.name as store', 'loads.id', 'users.name as user', 'notes', 'date','to_id')
            ->leftJoin('users', 'loads.user_id', '=', 'users.id')
            ->leftJoin('stores', 'loads.from_id', '=', 'stores.id')
            ->whereHas('loadDetails', function ($query) {
                $query->where('pending', true);
            })
            ->groupBy('loads.id');
            
            // if(auth()->user()->id != 1){
            //     $query->where('to_id', auth()->user()->store_id);
            // }
            $query->latest();

        return Datatables::of($query)
            ->addColumn('action', 'pending-load.loads.action')
            ->editColumn('date', function (Load $load) {
                return optional($load->date)->toDateString();
            })
            ->make(true);
    }

    public function loadsShow(Request $request)
    {
        $query = LoadDetail::
        select(
            'items.name as item',
            'load_details.quantity',
            'load_details.id'
        )
            ->leftJoin('items', 'items.id', '=', 'load_details.item_id')
            ->where('load_id', $request->load_id)
            ->where('pending', true);

        return Datatables::of($query)
            ->addColumn('action', 'pending-load.loads.modal.action')
            ->make(true);
    }
}
