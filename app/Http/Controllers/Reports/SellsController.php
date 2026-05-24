<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Employee;
use App\Models\Branch;
use App\Models\Attendance;
use App\Models\Order;
use App\Models\Client;
use Yajra\Datatables\Datatables;
use App\Models\AttendanceSettings;
use Carbon\Carbon;

class SellsController extends Controller
{
    //
    public function index(Request $request)
    {
        $this->validate($request, [
            'date_from' => 'required',
            'date_to' => 'required',
        ]);
        $branch = null;

        $from = $request->date_from;
        $to = $request->date_to;
        
        $orders = Order::select(
            'id',
            'date',
            'type',
            'ownerable_id',
            'branch_id',
            'reposite_id',
            'final_total'
            )
            ->where('orders.type', 'in')
            ->where('orders.is_return', 0)
            ->whereBetween('date',[
                $request->date_from,
                $request->date_to
            ]);
            

            $sumTotal = Order::where('type', 'in')
                        ->where('is_return', 0)
                        ->whereBetween('date',[
                            $request->date_from,
                            $request->date_to
                        ]);

            $sumReturns = Order::where('type', 'out')
            ->where('is_return', 1)
            ->whereBetween('date',[
                $request->date_from,
                $request->date_to
            ]);

            if($request->branch_id){
                $orders->where('branch_id', $request->branch_id);
                $sumTotal->where('branch_id', $request->branch_id);
                $sumReturns->where('branch_id', $request->branch_id);
                $branch = Branch::find($request->branch_id);
            }
            $sumTotal = $sumTotal->sum('final_total');
            $sumReturns = $sumReturns->sum('final_total');
            $orders = $orders->get();



        
        return view('reports.sells.index',compact('orders','sumTotal','sumReturns','from','to','branch'));
    }

    public function detailed(Request $request)
    {

       
        $query = Order::select(
            'date',
            'type',
            'ownerable_id',
            'branch_id',
            'reposite_id',
            'final_total'
            )
            ->where('orders.type', 'in')
            ->where('orders.is_return', 0)
            ->whereBetween('date',[
                $request->from,
                $request->to
            ]);
        
            if($request->branch_id){
                $query->where('branch_id', $request->branch_id);
            }
           
            return Datatables::of($query)
            ->editColumn('date',function(Order $order){
                return optional($order->date)->toDateString();
            })
            ->editColumn('ownerable_id',function(Order $order){
                $client = Client::find($order->ownerable_id);
                return $client->name ?? '';
            })
            ->editColumn('branch_id',function(Order $order){
                $branch = Branch::find($order->branch_id);
                return $branch->name ?? '';
            })
          

            ->make(true);


    }


    public function abstracted(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
            $query = Order::selectRaw('sum(final_total) as total_finals, type')
            ->where('orders.type', 'in')
            ->where('orders.is_return', 0)
            ->whereBetween('date',[
                $request->from,
                $request->to
            ]);
        
            if($request->branch_id){
                $query->where('branch_id', $request->branch_id);
            }
            $query->groupby('type');
           
            return Datatables::of($query)
            ->editColumn('total_finals',function(Order $order){
                return $order->total_finals ?? '';
            })
            ->editColumn('total_returns',function(Order $order){
                dd($from);
                $sumReturns = Order::where('type', 'in')
                        ->where('is_return', 0)
                        ->whereBetween('date',[
                            $request->from,
                            $request->to
                        ])
                        ->sum('final_total');

                return $sumReturns;
            })
            ->editColumn('difference',function($request){
                $sumOrders = Order::where('type', 'in')
                        ->where('is_return', 0)
                        ->whereBetween('date',[
                            $request->from,
                            $request->to
                        ]);
                $sumReturns = Order::where('type', 'in')
                        ->where('is_return', 1)
                        ->whereBetween('date',[
                            $request->from,
                            $request->to
                        ]); 
                // if($request->branch_id){
                //     $sumOrders->where('branch_id', $request->branch_id);
                //     $sumReturns->where('branch_id', $request->branch_id);
                // }
                // dd($sumOrders->sum('final_total'));
                
              $difference = $sumOrders->sum('final_total') -  $sumReturns->sum('final_total');
                
                return $difference;
            })
           
          

            ->make(true);
    }
}
