<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\OrderDetail;
use App\Models\LoadDetail;

class PendingLoadController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateOrder(Request $request ,OrderDetail $detail)
    {
        $done = $detail->performPendingLoad();
        return response()->json([
            'done'=>$done
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyOrder($id)
    {
        OrderDetail::find($id)->order->delete();
        return respons()->json([
            'done'=>true
        ]);
    }


     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateLoad(Request $request ,LoadDetail $detail, $quantity)
    {
        if($detail->quantity != $quantity) {
            return response()->json([
                'done'=>false
            ]);
        }   
        // perform update
            $done = $detail->performPendingLoad();
            return response()->json([
                'done'=>$done
            ]);    
      
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyLoad($id)
    {
        OrderDetail::find($id)->order->delete();
        return respons()->json([
            'done'=>true
        ]);
    }
}
