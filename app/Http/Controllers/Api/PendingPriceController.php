<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\OrderDetail;

class PendingPriceController extends Controller
{
    //
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request ,OrderDetail $detail)
    {
        $detail->performPendingPrice($request);
        return response()->json([
            'done'=>true
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        OrderDetail::find($id)->order->delete();
        return respons()->json([
            'done'=>true
        ]);
    }
}
