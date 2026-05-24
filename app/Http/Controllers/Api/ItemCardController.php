<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Group;
use App\Models\Item;

class ItemCardController extends Controller
{
    //
    public function groupsHasQuantitiesInStore(Request $request)
    {
        $groups = Group::whereHas('items.quantities',function($query) use($request){
            $query
            ->where('ownerable_type','App\Models\Store')
            ->where('ownerable_id',$request->store_id);
        })
        ->get();

        return response([
            'groups'=>$groups
        ]);
    }


    public function itemQuantitiesInStore(Request $request)
    {
        $items = Item::whereHas('quantities',function($query) use($request){
            $query
            ->where('ownerable_type','App\Models\Store')
            ->where('ownerable_id',$request->store_id);
        })
        ->where('group_id',$request->group_id)
        ->get();

        return response([
            'items'=>$items
        ]);
    }
}
