<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Order;

class GetOrdersController extends Controller
{
    //
    public function index(Request $request)
    {
        $owners = [
            'supplier'=>'App\Models\Supplier',
            'client'=>'App\Models\Client',
        ];

        $orders= Order::where([
            'ownerable_id'=>$request->id,
            'ownerable_type'=>$owners[$request->class],
            'type'=>$request->type,
        ])->latest()->get();

        return response()->json([
            'orders'=>$orders
        ]);
    }
}
