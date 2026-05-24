<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Yajra\Datatables\Datatables;

use App\Models\OrderDetail;
use App\Models\Account;

use App\DataTables\PendingPriceDataTable;
class PendingPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PendingPriceDataTable $dataTable)
    {
        return $dataTable->render('pending-price.index');
    }
   



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $query = OrderDetail::
        select('stores.name as store',
        'items.name as item','items.price as original_price',
        'order_details.unite_price as modified_price',
        'order_details.quantity',
        'order_details.discount',
        'order_details.id'
        )
        ->leftJoin('items','items.id','=','order_details.item_id')
        ->leftJoin('stores','stores.id','=','order_details.store_id')
        ->where('order_id',$request->order_id)
        ->where('price_pending',true);

        return Datatables::of($query)
        ->addColumn('action','pending-price.modal.action')
        ->make(true);
    }

    
}
