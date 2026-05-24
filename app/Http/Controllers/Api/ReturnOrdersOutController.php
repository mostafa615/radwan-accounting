<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Item;
use App\Models\Quantity;

use Yajra\Datatables\Datatables;

class ReturnOrdersOutController extends Controller
{
    public function itemsInGroup(Request $request)
    {
        $items = Item::
        whereHas('quantities',function($query) use($request){
            $query
            ->where('quantity','>=','1')
            ->where('ownerable_type','App\Models\\'.$request->ownerable_class)
            ->orWhere('ownerable_id',$request->ownerable_id);
        })
        ->with(['quantities'=>function($query) use($request) {
            $query
            ->where('quantity','>=','1')
            ->where('ownerable_type','App\Models\\'.$request->ownerable_class)
            ->orWhere('ownerable_id',$request->ownerable_id);
        }])
        ->where('group_id',$request->group_id)->get();

        \Log::info($items);
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


    public function itemsDataTable(Request $request)
    {
        $query = Quantity::
        select('items.name as item','quantity')
        ->join('items','items.id','=','quantities.item_id')
        ->where([
            'ownerable_type'=>'App\Models\\'.$request->ownerable_class,
            'ownerable_id'=>$request->ownerable_id,
        ])
        ->where('items.group_id',$request->group_id)
        ->where('quantity','>=','1');

        return Datatables::of($query)->make(true);

    }
}
