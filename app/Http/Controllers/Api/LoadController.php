<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Item;

class LoadController extends Controller
{
    //
    public function getItemsInGroup(Request $request)
    {
        // first i have group id and store id
        $items = Item::with(['quantities'=>function($query) use($request){
            $query
            ->where('ownerable_type','App\Models\Store')
            ->where('ownerable_id',$request->store_id)
            ->where('quantity','>=','1');
        }])->whereHas('quantities',function($query) use($request){
            $query
            ->where('ownerable_type','App\Models\Store')
            ->where('ownerable_id',$request->store_id)
            ->where('quantity','>=','1');
        })
        ->where('group_id',$request->group_id)->get();

        return response()->json([
            'items'=>$items
        ]);
    }
}
