<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Quantity;
class StoreController extends Controller
{
    //
    public function setQuantityOfItem(Request $request)
    {
        Quantity::find($request->id)->update(['quantity'=>$request->quantity]);
        return response([
            'done'=>true
        ]);
    }
}
