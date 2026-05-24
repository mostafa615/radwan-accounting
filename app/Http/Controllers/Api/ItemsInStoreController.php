<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Store;

class ItemsInStoreController extends Controller
{
    //
    public function index(Request $request)
    {
        $store = Store::find($request->id);
        return response()->json([
            'items'=>$store->quantities()->with('item')->get()
        ]);
    }
}
