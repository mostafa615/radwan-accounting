<?php

namespace App\Http\Controllers\Reports;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Store;
use App\Models\Load;
use Yajra\Datatables\Datatables;


class LoadController extends Controller
{
    //
    public function index(Request $request)
    {
        $resources=Load::with('from','to','loadDetails')->where(function($q) use ($request){
            if ($request->has('date_from') && !empty($request->date_from) && $request->date_from != null){
                $q->whereDate('date','>=',Carbon::parse($request->date_from));
            }
            if ($request->has('date_to') && !empty($request->date_to) && $request->date_to != null){
                $q->whereDate('date','<=',Carbon::parse($request->date_to));
            }
            if ($request->has('stores_from') && !empty($request->stores_from) && $request->stores_from != null){
                $q->whereIn('from_id',$request->stores_from);
            }
            if ($request->has('stores_to') && !empty($request->stores_to) && $request->stores_to != null){
                $q->whereIn('to_id',$request->stores_to);
            }
        })->get();
        $stores = Store::all();
        return view('reports.load',compact('stores','resources'));
    }

    public function perform(Request $request)
    {
        $query = Load::select('loads.created_at','loads.id','no','stores.name','date')
            ->whereBetween('date',[
                $request->from,
                $request->to
            ]);

        if($request->store_id){
            $query = $query->where('stores.id',$request->store_id);
        }
        if($request->type == 'from'){
            $query = $query->leftJoin('stores','stores.id','loads.from_id');
        }  else {
            $query = $query->leftJoin('stores','stores.id','loads.to_id');
        }



        return Datatables::of($query)
            ->editColumn('date',function(Load $order){
                return optional($order->date)->toDateString();
            })
            ->make(true);


    }
}
