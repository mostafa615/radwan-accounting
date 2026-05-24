<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Account;
use App\Models\Daily;
use App\Models\Quantity;
use App\Models\Item;
use Yajra\Datatables\Datatables;
use DB;

class HomeController extends Controller
{
    //
    public function index()
    {
    //     $clients = Client::count();
        
    //     $suppliers = Supplier::count();

    //   if(auth()->user()->branch_id){
    //     $employees = Employee::where('branch_id',auth()->user()->branch_id)->count();
    //     $ordersIn = Order::whereHas('orderDetails',function($query){
    //         $query
    //         ->where('price_pending',false)
    //         ->where('load_pending',false);
    //     }
    //     )->where('type','in')
    //     ->where('branch_id',auth()->user()->branch_id)
    //     ->where('is_return',false)->sum('final_total');

    //     $ordersOut = Order::whereHas('orderDetails',function($query){
    //         $query
    //         ->where('price_pending',false)
    //         ->where('load_pending',false);
    //     })
    //     ->where('type','out')
    //     ->where('branch_id',auth()->user()->branch_id)
    //     ->where('is_return',false)->sum('final_total');

    //     $in = Account::where('type','in')
    //     ->where('branch_id',auth()->user()->branch_id)
    //     ->where('pending',false)->sum('cost') 
    //     + 

    //     Daily::where('type','in')
    //     ->where('branch_id',auth()->user()->branch_id)
    //     ->where('pending',false)
    //     ->sum('cost');
    //     $out = Account::where('type','out')
    //     ->where('branch_id',auth()->user()->branch_id)
    //     ->where('pending',false)
    //     ->sum('cost') +
    //      Daily::where('type','out')
    //      ->where('branch_id',auth()->user()->branch_id)
    //      ->where('pending',false)->sum('cost');

    //   } else {
    //     $employees = Employee::count();
    //     $ordersIn = Order::whereHas('orderDetails',function($query){
    //         $query
    //         ->where('price_pending',false)
    //         ->where('load_pending',false);
    //     })->where('type','in')->where('is_return',false)->sum('final_total');

    //     $ordersOut = Order::whereHas('orderDetails',function($query){
    //         $query
    //         ->where('price_pending',false)
    //         ->where('load_pending',false);
    //     })->where('type','out')->where('is_return',false)->sum('final_total');

    //     $in = Account::where('type','in')->where('pending',false)->sum('cost') + Daily::where('type','in')->where('pending',false)->sum('cost');
    //     $out = Account::where('type','out')->where('pending',false)->sum('cost') + Daily::where('type','out')->where('pending',false)->sum('cost');
    //   }

    //     $items = Item::count();
    //     $profits =   $in - $out;
        return view('home.index');
        // return view('home.index',compact('clients','employees','suppliers','ordersIn','ordersOut','profits','items','in','out'));
    }


    public function quantitiesLessThan()
    {
        $query = Quantity::
        select('items.created_at','items.name as item','stores.name as store','quantity')
        ->leftJoin('items','items.id','=','quantities.item_id')
        ->join('stores',function($join){
            $join->on('quantities.ownerable_id','=','stores.id')
            ->where('quantities.ownerable_type','App\Models\Store');
        })
        // ->leftJoin('stores','stores.id','=','quantities.store_id')
        ->where('quantities.quantity','<=',10)->latest();
        return Datatables::of($query)->make(true);
    }

    public function itemsBalance()
    {
        $query = Item::
        select('items.created_at','items.name as item',DB::raw('sum(quantities.quantity) as sum_of_quantity'))
        ->leftJoin('quantities','items.id','=','quantities.item_id')
        ->groupBy('items.id');
        return Datatables::of($query)
        ->editColumn('sum_of_quantity',function(Item $item){
            if(!$item->sum_of_quantity)
            {
                $item->sum_of_quantity = 0;
            }

            return $item->sum_of_quantity;
        })
        ->make(true);
    }

    
}
