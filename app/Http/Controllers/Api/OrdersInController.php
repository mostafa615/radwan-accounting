<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Item;
use App\Models\Quantity;

use Yajra\Datatables\Datatables;


class OrdersInController extends Controller
{
    //
    public function itemsInGroup(Request $request)
    {
        $items = Item::whereHas('quantities',function($query){
            $query
            ->where('ownerable_type','App\Models\Store')
            ->where('quantity','>=','1');
        })
        ->where('group_id',$request->group_id)->get();
        return response()->json([
            'items'=>$items
        ]);
    }


    public function itemsQuantitiesInStore(Request $request) 
    {
        $quantities = Quantity::with('ownerable')->where('ownerable_type','App\Models\Store')->where('item_id',$request->item_id)
        ->where('quantity','>=',1)->get();
        return response()->json([
            'quantities'=>$quantities
        ]);
    }

    public function getBuyers(Request $request) 
    {
        $buyers = ('App\Models\\'.$request->type)::select('id','name','phone_1')->get();

        return response()->json([
            'buyers'=>$buyers
        ]);
    }


    public function itemsDataTable(Request $request)
    {
        $query = Quantity::
        select('items.name as item','quantity','stores.name as store')
        ->join('items','items.id','=','quantities.item_id')
        ->join('stores',function($join) use($request){
            $join->on('quantities.ownerable_id','=','stores.id')
            ->where('ownerable_type','App\Models\Store');
        })
        ->where('item_id',$request->item_id)
        ->where('quantity','>=','1');

        return Datatables::of($query)->make(true);

    }


}
