<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Item;

class OrdersOutController extends Controller
{
    public function itemsInGroup(Request $request)
    {
        $items = Item::where('group_id',$request->group_id)->get();
        return response()->json([
            'items'=>$items
        ]);
    }
}
