<?php



namespace App\Http\Controllers\Reports;



use App\Models\Account;

use App\Models\Attendance;

use App\Models\Client;

use App\Models\Daily;

use App\Models\Employee;

use App\Models\Item;

use App\Models\Load;

use App\Models\LoadDetail;

use Carbon\CarbonPeriod;

use App\Models\Loan;

use App\Models\Order;

use App\Models\OrderDetail;

use App\Models\Quantity;

use App\Models\Reposite;

use App\Models\Salary;

use App\Models\Store;

use App\Models\QuantityDailies;

use App\Models\Supplier;

use App\Models\NewQuantities;

use App\Models\Transaction;

use App\Models\Tree;

use App\Models\User;

use Carbon\Carbon;

use Illuminate\Http\Request;

use DB;

use App\Http\Controllers\Controller;



class ReportsController extends Controller {



    public function index() {

        return view('reports.index');

    }

    

    public function loansOfEmployee(Request $request) {

        $loans = Loan::where("employee_id", "=", $request->employee_id)->where("type", "=", "solfa")->
        whereBetween("date", array($request->from, $request->to))->get();


        $loans2 = Loan::where("employee_id", "=", $request->employee_id)
                ->whereBetween("date", array($request->from, $request->to))
                ->where("type", "=", "madionia")
                //->where("paid", "=", 1)
                ->whereRaw("loans.cost = loans.paid_value")
                ->get();


        $mads = Salary::where("employee_id", "=", $request->employee_id)->
        whereBetween("date", array($request->from, $request->to))->get();
        
        $dailies = Daily::whereBetween("date", array($request->from, $request->to))
            ->where('tree_id', 'j1_1')
            ->where('employee_id', $request->employee_id)
            ->get();

        //dump($dailies);
        //return;
        return view("reports.employee-loans-in-salary", compact('loans', 'mads', 'loans2', 'dailies'));

    }



    public function holidays_report(Request $request) {

        $this->validate($request, [
            'employee_id' => 'required',
            'date_from' => 'required',
            'date_to' => 'required',
        ]);
      

        $employee = Employee::with(['attendanceSettings' => function ($q) use($request) {

                        if ($request->has('date_from') && $request->date_from != '' && $request->date_from != null) {

                            $q->where('year', '>=', Carbon::parse($request->date_from)->year);

                        }

                        if ($request->has('date_to') && $request->date_to != '' && $request->date_to != null) {

                            $q->where('year', '<=', Carbon::parse($request->date_to)->year);

                        }

                    }])->where(function ($q) use($request) {

                    if ($request->has('employee_id') && $request->employee_id != '' && $request->employee_id != null) {

                        $q->where('id', $request->employee_id);

                    }

                }) 
                ->first();

                $employees = Employee::where('active', 1)->with(['attendanceSettings' => function ($q) use($request) {

                    if ($request->has('date_from') && $request->date_from != '' && $request->date_from != null) {

                        $q->where('year', '>=', Carbon::parse($request->date_from)->year);

                    }

                    if ($request->has('date_to') && $request->date_to != '' && $request->date_to != null) {

                        $q->where('year', '<=', Carbon::parse($request->date_to)->year);

                    }

                }])
                ->select('id','name')->get();

    //    return $employees;

        $resources = Attendance::where(function ($q) use ($request) {

                    if ($request->has('employee_id') && $request->employee_id != '' && $request->employee_id != null) {

                        $q->where('employee_id', $request->employee_id);

                    }

                    if ($request->has('date_from') && $request->date_from != '' && $request->date_from != null) {

                        $q->whereDate('date', '>=', Carbon::parse($request->date_from));

                    }

                    if ($request->has('date_to') && $request->date_to != '' && $request->date_to != null) {

                        $q->whereDate('date', '<=', Carbon::parse($request->date_to));

                    }

                    // $q->where('absence_with_holiday', 1);


                    $q->where(function ($q) {
                        $q->where('absence_with_holiday', 1)
                        ->orWhere('summer_holidays', 1)
                        ->orWhere('emergency_absence', 1);
                    });
                })
                ->get();

                $year = Carbon::parse($request->date_from)->year;
                $date_from = $request->date_from;
                $date_to = $request->date_to;
                
    //    return $resources;

        return view('reports.holidays', compact('employees','resources', 'employee','year','date_from','date_to'));

    }

    public function holidays_all_report(Request $request) {

        $this->validate($request, [
            'date_from' => 'required',
            'date_to' => 'required',
        ]);
        

     

                $employees = Employee::where('active', 1)->with(['attendanceSettings' => function ($q) use($request) {

                    if ($request->has('date_from') && $request->date_from != '' && $request->date_from != null) {

                        $q->where('year', '>=', Carbon::parse($request->date_from)->year);

                    }

                    if ($request->has('date_to') && $request->date_to != '' && $request->date_to != null) {

                        $q->where('year', '<=', Carbon::parse($request->date_to)->year);

                    }
                  

                }])
                ->select('id','name','branch_id'); 
                if ($request->has('branch_id') && $request->branch_id != '' && $request->branch_id != null) {
                    $employees->where('branch_id', $request->branch_id);
                }
                $employees = $employees->get();
    //    return $employees;

       

                $year = Carbon::parse($request->date_from)->year;
                $date_from = $request->date_from;
                $date_to = $request->date_to;
                
    //    return $resources;

        return view('reports.holidays_all', compact('employees','year','date_from','date_to'));

    }




    public function salaries_report(Request $request) {

        $date = $request->date;

        

        // month of the date

        $month = Carbon::parse($request->date)->month;

        // year of th date

        $year = Carbon::parse($request->date)->year;



        // start date of the month

        $date1 = $year . "-" . $month . "-0" . 1;



        // end date of the month

        $date2 = $year . "-" . $month . "-" . 31; 


        if ($request->employee_id == "all") {

            $resources = Salary::join("employees", "salaries.employee_id", "=", "employees.id")
            ->where("employees.branch_id", "=", $request->branch_id)
            //->where("salaries.date", "like", '%-10-%')
            ->whereBetween("salaries.date", [$date1, $date2])
            ->select('*', 'salaries.date as created_at', 'salaries.notes as notes')
            ->get(); 

            //return dump($resources);
        } else { 
            $resources = Salary::where("employee_id", $request->employee_id)-> 
                            whereBetween("salaries.created_at", [$date1, $date2])
                            ->select('*', 'salaries.created_at as created_at', 'salaries.notes as notes')
                            ->get();

        }

        return view('reports.salaries', compact('resources', 'date'));

    }

    public function salaries_report_print(Request $request) {
       
        $date = $request->date;

        

        // month of the date

        $month = Carbon::parse($request->date)->month;

        // year of th date

        $year = Carbon::parse($request->date)->year;



        // start date of the month

        $date1 = $year . "-" . $month . "-0" . 1;



        // end date of the month

        $date2 = $year . "-" . $month . "-" . 31; 


        if ($request->employee_id == "all") {
            
            $resources = Salary::join("employees", "salaries.employee_id", "=", "employees.id")
            ->where("employees.branch_id", "=", $request->branch_id)
            //->where("salaries.date", "like", '%-10-%')
            ->whereBetween("salaries.date", [$date1, $date2])
            ->select('*', 'salaries.date as created_at', 'salaries.notes as notes')
            ->get(); 
            // dd($resources);
            //return dump($resources);
        } else { 
            $resources = Salary::where("employee_id", $request->employee_id)-> 
                            whereBetween("salaries.created_at", [$date1, $date2])
                            ->select('*', 'salaries.created_at as created_at', 'salaries.notes as notes')
                            ->get();

        }
        
        return view('reports.salaries_print', compact('resources', 'date'));

    }


    /**
     * new function
     * 
     */
    public function supplier_client_report(Request $request) {

        $this->validate($request, [

            'supplier_id' => 'required',

            'client_id' => 'required',

                ], [

            'supplier_id.required' => 'اختر المورد من فضلك',

            'client_id.required' => 'اختر العميل من فضلك',

        ]);

        $from = $request->date_from;
        $to = $request->date_to;

        $request->request->add([
            "from" => $request->date_from,  
            "to" => $request->date_to,  
        ]);
        
        // client accounts
        $clientReport = new ClientController();
        $clientBalance = $clientReport->getClientBalance($request);
        
        // supplier accounts
        $suppliertReport = new SupplierController();
        $supplierBalance = $suppliertReport->getSupplierBalance($request);
        
        //models
        $client = Client::find($request->client_id);
        $supplier = Supplier::find($request->supplier_id);
         
       //dump($clientBalance["client_accounts"]);
        
        //return;
        return view('reports.client_supplier', compact('clientBalance', 'supplierBalance', 'from', 'to', 'client', 'supplier'));

    }

    public function supplier_client_report1(Request $request) {

        $this->validate($request, [

            'supplier_id' => 'required',

            'client_id' => 'required',

                ], [

            'supplier_id.required' => 'اختر المورد من فضلك',

            'client_id.required' => 'اختر العميل من فضلك',

        ]);



        $orders = [];

        $supplierOrders = [];

        $pay_in = [];

        $pay_out = [];

        $returns = [];

        $supplierReturns = [];

        $client = '';

        $from = '';

        $to = '';

        $client_accounts = [];

        if ($request->has('date_from') && $request->date_from != null && $request->has('date_to') && $request->date_to != null && $request->has('client_id') && $request->client_id != null) {

            $from = $request->date_from;

            $to = $request->date_to;

            $client = Client::where('id', $request->client_id)->first();

            $supplier = Supplier::where('id', $request->supplier_id)->first();

            $s_balance = 0;
            $clientBalance = $client->init;
            $supplierBalance = $supplier->init;

            for ($i = 0; $i <= Carbon::parse($request->date_from)->diffInDays(Carbon::parse($request->date_to)); $i++) {
                
                ///////////////////////////// client
                $out = Order::where('type', 'out')
                                ->where('is_return', false)
                                ->whereDate('date', Carbon::parse($request->from)->addDays($i))
                                /* ->whereHas('orderDetails', function ($query) {
                                  $query->where('load_pending', false)->orWhere('price_pending', false);
                                  }) */
                                ->where([
                                    'ownerable_type' => 'App\Models\Client',
                                    'ownerable_id' => $request->client_id,
                                ])->get();


                foreach ($out as $item) { 
                    array_push($orders, $item); 
                }
                $order_return = Order::where('type', 'out')
                                ->where('is_return', true)
                                ->whereDate('date', Carbon::parse($request->from)->addDays($i))
                                // ->whereHas('orderDetails', function ($query) {
                                //     $query->where('load_pending', false)->orWhere('price_pending', false);
                                // })
                                ->where([
                                    'ownerable_type' => 'App\Models\Client',
                                    'ownerable_id' => $request->client_id,
                                ])->get();

                foreach ($order_return as $item) { 
                    array_push($returns, $item); 
                }

                $in = Order::where('type', 'in')
                        ->where('is_return', false)
                        ->whereDate('date', Carbon::parse($request->from)->addDays($i))
                        // ->whereHas('orderDetails', function ($query) {
                        //     $query
                        //         ->where('load_pending', false)
                        //         ->orWhere('price_pending', false);
                        // })
                        ->where([
                            'ownerable_type' => 'App\Models\Client',
                            'ownerable_id' => $request->client_id,
                        ])
                        ->get();

                foreach ($in as $item) { 
                    array_push($orders, $item); 
                }
                
                $pin = Account::with('reposite')->where('type', 'in')
                        ->whereDate('date', Carbon::parse($request->from)->addDays($i))
                        ->where('pending', false)
                        ->where('accountable_id', $request->client_id)
                        ->where('accountable_type', 'App\Models\Client')
                        ->get();
                        
                
                foreach ($pin as $item) { 
                    array_push($pay_in, $item); 
                }
                $pout = Account::with('reposite')->where('accounts.type', 'out')
                        ->whereDate('date', Carbon::parse($request->from)->addDays($i))
                        ->where('pending', false)
                        ->where('accountable_id', $request->client_id)
                        ->where('accountable_type', 'App\Models\Client')
                        ->get();
                        
                
                foreach ($pout as $item) { 
                    array_push($pay_out, $item); 
                }
                if ($pout->sum('cost') > 0 || $pin->sum('cost') || $in->sum('final_total') > 0 || $order_return->sum('final_total') > 0 || $out->sum('final_total') > 0) {
//                    $s_balance= $s_balance + ( $out->sum('final_total') - $in->sum('final_total') ) + ($pin->sum('cost') - $pout->sum('cost'))  ;
                    $clientBalance = $clientBalance + ( $in->sum('final_total') - $out->sum('final_total') - $order_return->sum('final_total') - ($pin->sum('cost') ) + $pout->sum('cost'));

                }
                ///////////////////////////// end of client


                //////////////////////////// supplier
                $out = Order::where('type', 'out')
                    ->where('is_return', false)
                    ->whereDate('date', Carbon::parse($request->from)->addDays($i))
                    // ->whereHas('orderDetails', function ($query) {
                    //     $query->where('load_pending', false)->orWhere('price_pending', false);
                    // })
                    ->where([
                        'ownerable_type' => 'App\Models\Supplier',
                        'ownerable_id' => $request->supplier_id,
                    ])->get();
                if (count($out) > 0) {
                    foreach ($out as $item) {
                        array_push($supplierOrders, $item);
                    }
                }

                $order_return = Order::where('type', 'out')
                    ->where('is_return', true)
                    ->whereDate('date',Carbon::parse($request->from)->addDays($i))
                    // ->whereHas('orderDetails', function ($query) {
                    //     $query->where('load_pending', false)->orWhere('price_pending', false);
                    // })
                    ->where([
                        'ownerable_type' => 'App\Models\Supplier',
                        'ownerable_id' => $request->supplier_id,
                    ])->get();
                if (count($order_return) > 0) {
                    foreach ($order_return as $item) {
                        array_push($supplierReturns, $item);
                    }
                }
                $in = Order::where('type', 'in')
                    ->where('is_return', false)
                    ->whereDate('date',Carbon::parse($request->from)->addDays($i))
                    // ->whereHas('orderDetails', function ($query) {
                    //     $query
                    //         ->where('load_pending', false)
                    //         ->orWhere('price_pending', false);
                    // })
                    ->where([
                        'ownerable_type' => 'App\Models\Supplier',
                        'ownerable_id' => $request->supplier_id,
                    ])
                    ->get();
                if (count($in) > 0) {
                    foreach ($in as $item) {
                        array_push($supplierOrders, $item);
                    }
                }
                $pin = Account::with('reposite')->where('type', 'in')
                    ->whereDate('date',Carbon::parse($request->from)->addDays($i))
                    ->where('pending', false)
                    ->where('accountable_id', $request->supplier_id)
                    ->where('accountable_type', 'App\Models\Supplier')
                    ->get();
                if (count($pin) > 0) {
                    foreach ($pin as $item) {
                        array_push($pay_in, $item);
                    }
                }
                $pout = Account::with('reposite')->where('accounts.type', 'out')
                    ->whereDate('date',Carbon::parse($request->from)->addDays($i))
                    ->where('pending', false)
                    ->where('accountable_id', $request->supplier_id)
                    ->where('accountable_type', 'App\Models\Supplier')
                    ->get();
                if (count($pout) > 0) {
                    foreach ($pout as $item) {
                        array_push($pay_out, $item);
                    }
                }
                if ($pout->sum('cost') > 0 || $pin->sum('cost') || $in->sum('final_total') > 0 || $order_return->sum('final_total') > 0 || $out->sum('final_total') > 0){
                    $supplierBalance= $supplierBalance + ( $out->sum('final_total') - $in->sum('final_total') - $order_return->sum('final_total') )-($pout->sum('cost') - $pin->sum('cost'))  ;
                    
                }
                
                $s_balance += $clientBalance - $supplierBalance;
                array_push($client_accounts,[
                        'date'=>Carbon::parse($request->from)->addDays($i)->format('Y-m-d'),
                        'order_out'=>$out->sum('final_total'),
                        'order_in'=>$in->sum('final_total'),
                        'return'=>$order_return->sum('final_total'),
                        'pay_in'=>$pin->sum('cost'),
                        'pay_out'=>$pout->sum('cost'),
                        'balance'=>number_format($s_balance . '', 2)
                    ]);
                //////////////////////////// end of supplier

                

            }

        }

        return view('reports.client_supplier', compact('supplierOrders', 'supplierReturns', 'from', 'client', 'supplier', 'to', 'orders', 'pay_in', 'pay_out', 'returns', 'client_accounts'));

    }



    public function client_item_report(Request $request) {

//        return $request->all();

        $resources = OrderDetail::with('item', 'order')->where(function ($q) use ($request) {

                    if ($request->has('client_id') && $request->client_id != null) {

                        $q->whereHas('order', function ($q1) use ($request) {

                            $q1->where('ownerable_type', 'App\Models\Client');

                            $q1->where('ownerable_id', $request->client_id);

                        });

                    }

                    if ($request->has('group_id') && $request->group_id != null) {

                        $q->whereHas('item', function ($q1) use ($request) {

                            $q1->where('group_id', $request->group_id);

                        });

                    }

                    if ($request->has('item_id') && $request->item_id != null) {

                        $q->whereIn('item_id', $request->item_id);

                    }

                })->get();

        return view('reports.client_item_report', compact('resources'));

    }



    public function daily_report(Request $request) {

        

        $resources = Daily::with('tree')->where(function ($q) use ($request) {

                    if ($request->tree_id && $request->tree_id != '' && $request->tree_id != null) {

                        $q->where('tree_id', $request->tree_id);

                    }
                    if ($request->branch_id  && $request->branch_id  != '' && $request->branch_id != null) {

                        $q->whereIn('branch_id', $request->branch_id);

                    }

                    if ($request->date_from && $request->date_from != '' && $request->date_from != null) {

                        $q->where('date', '>=', $request->date_from);

                    }

                    if ($request->date_to && $request->date_to != '' && $request->date_to != null) {

                        $q->where('date', '<=', $request->date_to);

                    }

                })->get();

//        return $resources;

        return view('reports.daily_report', compact('resources'));

    }

    public function getEmployees(Request $request)
    {
        $branchId = $request->input('branch_id');
        
        // Retrieve employees for the selected branch
        $employees = Employee::where('branch_id', $branchId)
            ->where('active', 1)
            ->get();

        // Return the employees as a JSON response
        return response()->json($employees);
    }


    public function mandator_report(Request $request) {

        $resources = Order::with('mandator')->where(function ($q) use ($request) {

                    if ($request->has('mandator_id') && $request->mandator_id != null && !empty($request->mandator_id)) {

                        $q->whereIn('mandator_id', $request->mandator_id);

                    }

                    if ($request->has('date_from') && $request->date_from != null && !empty($request->date_from)) {

                        $q->whereDate('date', '>=', Carbon::parse($request->date_from));

                    }

                    if ($request->has('date_to') && $request->date_to != null && !empty($request->date_to)) {

                        $q->whereDate('date', '<=', Carbon::parse($request->date_to));

                    }

                })->get();

        return view('reports.mandator', compact('resources'));

    }


    public function attendReportDetails(Request $request){
        $this->validate($request, [
            'date_from' => 'required',
            'date_to' => 'required',
        ]);

        $employees = Employee::where('active', 1)->get();

        return view('reports.attend_report', compact('employees'));
    }


    public function item_all_report(Request $request) {
        // dd($request);
        $this->validate($request, [

            'items' => 'required',

            'date_from' => 'required',

            'date_to' => 'required',

        ]);

        $orders_in = OrderDetail::with('order.ownerable', 'item')

                        ->where(function ($q) use ($request) {

                            if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {

                                $q->whereIn('store_id', $request->stores_id);

                            }

                        })

//            ->where('price_pending', 0)

//            ->where('load_pending', 0)

                        ->whereHas('order', function ($q) use ($request) {

                            $q->whereBetween('date2', [$request->date_from, $request->date_to])

                            ->where('is_return', 0)

                            ->where('type', 'in');

                        })->whereIn('item_id', $request->items)->get();
        // dd($orders_in);
        $orders_in_return = OrderDetail::with('order.ownerable', 'item')

                        ->where(function ($q) use ($request) {

                            if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {

                                $q->whereIn('store_id', $request->stores_id);

                            }

                        })

                        ->whereHas('order', function ($q) use ($request) {

                            $q->whereBetween('date2', [$request->date_from, $request->date_to]);

                            $q->where('is_return', true);

                            $q->where('type', 'out');

                        })

                        ->whereIn('item_id', $request->items)->get();

        $orders_out = OrderDetail::with('order.ownerable', 'item')

                        ->where(function ($q) use ($request) {

                            if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {

                                $q->whereIn('store_id', $request->stores_id);

                            }

                        })

//            ->where('price_pending', 0)

//            ->where('load_pending', 0)

                        ->whereHas('order', function ($q) use ($request) {

                            $q->whereBetween('date2', [$request->date_from, $request->date_to])

                            ->where('is_return', 0)

                            ->where('type', 'out');

                        })->whereIn('item_id', $request->items)->get();



        $orders_out_return = OrderDetail::with('order.ownerable', 'item')

                        ->where(function ($q) use ($request) {

                            if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {

                                $q->whereIn('store_id', $request->stores_id);

                            }

                        })

                        ->whereHas('order', function ($q) use ($request) {

                            $q->whereBetween('date2', [$request->date_from, $request->date_to])

                            ->where('is_return', 1)

                            ->where('type', 'in');

                        })->whereIn('item_id', $request->items)->get();



        $loads_from = LoadDetail::with('parent', 'item')

                ->where(function ($q) use ($request) {

                    if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {

                        $q->whereHas('parent', function ($q2) use ($request) {

                            $q2->whereIn('from_id', $request->stores_id);

//                        $q2->OrWhereIn('to_id', $request->stores_id);

                        });

                    }

                })

//            ->where('pending', 0)

                ->whereHas('parent', function ($q) use ($request) {

                    $q->whereBetween('date', [$request->date_from, $request->date_to]);

                })->whereIn('item_id', $request->items)

                ->get();



        $loads_to = LoadDetail::with('parent', 'item')

                ->where(function ($q) use ($request) {

                    if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {

                        $q->whereHas('parent', function ($q2) use ($request) {

//                        $q2->whereIn('from_id', $request->stores_id);

                            $q2->whereIn('to_id', $request->stores_id);

                        });

                    }

                })

//            ->where('pending', 0)

                ->whereHas('parent', function ($q) use ($request) {

                    $q->whereBetween('date', [$request->date_from, $request->date_to]);

                })->whereIn('item_id', $request->items)

                ->get();
            
        $operation_order_store_ids = DB::table('operation_orders')
                                        ->whereIn('store_id', $request->stores_id)->pluck('id')->toArray();


        $to_factory = DB::table('operation_order_details')
                            ->whereIn('operation_order_id', $operation_order_store_ids)
                            ->whereIn('item_id', $request->items)->get();

        // $to_factory = DB::table('operation_orders')
        //                     ->whereIn('id', $operation_order_ids)->get();
                            
        // $to_factory = DB::table('operation_order_details' )
        //                 ->select(\DB::raw('operation_order_details.*, (SELECT date FROM operation_orders WHERE operation_orders.id=operation_order_details.operation_order_id and ) AS date'))
        //                 ->get();
        // dd($to_factory);
        $operation_ord_ids = DB::table('operation_order_details')
                                ->whereIn('operation_order_id', $operation_order_store_ids)
                                ->whereIn('out_item_id', $request->items)->pluck('id')->toArray();
                                 
        $from_factory = DB::table('operation_order_results')
                            ->whereIn('order_details_id', $operation_ord_ids)                           
                            ->get();
        

        $operation_ord_item_ids = DB::table('operation_order_details')
                                ->whereIn('item_id', $request->items)->pluck('id')->toArray();

        $scrap_factory = DB::table('operation_order_result_details')
                                ->whereIn('order_details_id', $operation_ord_item_ids)
                                ->where('damage_type', 'scrap')                           
                                ->get();    

        $pieces_factory = DB::table('operation_order_result_details')
                                ->whereIn('order_details_id', $operation_ord_item_ids)
                                ->where('damage_type', 'pieces')                           
                                ->get();    
        // dd($from_factory);  
        // get inn and out from table OrderDetail in and out and from factory and to factory and order factory ...
        $initQun = 0;
        
        $startDat = OrderDetail::with('order.ownerable', 'item')

                        ->where(function ($q) use ($request) {

                            if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {

                                $q->whereIn('store_id', $request->stores_id);

                            }

                        })

                        ->whereHas('order', function ($q) use ($request) {

                            $q->whereBetween('date', [$request->date_from, $request->date_to]);

                        })->whereIn('item_id', $request->items)->get();
                    
        // dd($startDat);
        $resources = Quantity::with('item')

                        ->where('ownerable_type', 'App\Models\Store')

                        ->whereIn('ownerable_id', $request->stores_id)

                        ->whereIn('item_id', $request->items)->get();

//        return $resources;

//        return $request->all();

        return view('reports.item_all_report', compact('orders_in', 'orders_in_return', 'orders_out', 'orders_in_return', 'orders_out_return', 'loads_from', 'loads_to','to_factory','from_factory', 'scrap_factory','pieces_factory','resources','initQun'));

    }
    
//     public function new_item_all_report(Request $request) {
//         // dd($request);
//         $this->validate($request, [

//             'items' => 'required',

//             'date_from' => 'required',

//             'date_to' => 'required',

//         ]);

//         $orders_in = OrderDetail::with('order.ownerable', 'item')

//                         ->where(function ($q) use ($request) {

//                             if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {

//                                 $q->whereIn('store_id', $request->stores_id);

//                             }

//                         })

// //            ->where('price_pending', 0)

// //            ->where('load_pending', 0)

//                         ->whereHas('order', function ($q) use ($request) {

//                             $q->whereBetween('date', [$request->date_from, $request->date_to])

//                             ->where('is_return', 0)

//                             ->where('type', 'in');

//                         })->whereIn('item_id', $request->items)->get();
//         // dd($orders_in);
//         $orders_in_return = OrderDetail::with('order.ownerable', 'item')

//                         ->where(function ($q) use ($request) {

//                             if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {

//                                 $q->whereIn('store_id', $request->stores_id);

//                             }

//                         })

//                         ->whereHas('order', function ($q) use ($request) {

//                             $q->whereBetween('date', [$request->date_from, $request->date_to]);

//                             $q->where('is_return', true);

//                             $q->where('type', 'out');

//                         })

//                         ->whereIn('item_id', $request->items)->get();

//         $orders_out = OrderDetail::with('order.ownerable', 'item')

//                         ->where(function ($q) use ($request) {

//                             if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {

//                                 $q->whereIn('store_id', $request->stores_id);

//                             }

//                         })

// //            ->where('price_pending', 0)

// //            ->where('load_pending', 0)

//                         ->whereHas('order', function ($q) use ($request) {

//                             $q->whereBetween('date', [$request->date_from, $request->date_to])

//                             ->where('is_return', 0)

//                             ->where('type', 'out');

//                         })->whereIn('item_id', $request->items)->get();


//         // dd($orders_out);
//         $orders_out_return = OrderDetail::with('order.ownerable', 'item')

//                         ->where(function ($q) use ($request) {

//                             if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {

//                                 $q->whereIn('store_id', $request->stores_id);

//                             }

//                         })

//                         ->whereHas('order', function ($q) use ($request) {

//                             $q->whereBetween('date', [$request->date_from, $request->date_to])

//                             ->where('is_return', 1)

//                             ->where('type', 'in');

//                         })->whereIn('item_id', $request->items)->get();



//         $loads_from = LoadDetail::with('parent', 'item')

//                 ->where(function ($q) use ($request) {

//                     if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {

//                         $q->whereHas('parent', function ($q2) use ($request) {

//                             $q2->whereIn('from_id', $request->stores_id);

// //                        $q2->OrWhereIn('to_id', $request->stores_id);

//                         });

//                     }

//                 })

// //            ->where('pending', 0)

//                 ->whereHas('parent', function ($q) use ($request) {

//                     $q->whereBetween('date', [$request->date_from, $request->date_to]);

//                 })->whereIn('item_id', $request->items)

//                 ->get();



//         $loads_to = LoadDetail::with('parent', 'item')

//                 ->where(function ($q) use ($request) {

//                     if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {

//                         $q->whereHas('parent', function ($q2) use ($request) {

// //                        $q2->whereIn('from_id', $request->stores_id);

//                             $q2->whereIn('to_id', $request->stores_id);

//                         });

//                     }

//                 })

// //            ->where('pending', 0)

//                 ->whereHas('parent', function ($q) use ($request) {

//                     $q->whereBetween('date', [$request->date_from, $request->date_to]);

//                 })->whereIn('item_id', $request->items)

//                 ->get();
            
//         $operation_order_store_ids = DB::table('operation_orders')
//                                         ->whereIn('store_id', $request->stores_id)->pluck('id')->toArray();


//         $to_factory = DB::table('operation_order_details')
//                             ->whereIn('operation_order_id', $operation_order_store_ids)
//                             ->whereIn('item_id', $request->items)->get();

//         // $to_factory = DB::table('operation_orders')
//         //                     ->whereIn('id', $operation_order_ids)->get();
                            
//         // $to_factory = DB::table('operation_order_details' )
//         //                 ->select(\DB::raw('operation_order_details.*, (SELECT date FROM operation_orders WHERE operation_orders.id=operation_order_details.operation_order_id and ) AS date'))
//         //                 ->get();
//         // dd($to_factory);
//         $operation_ord_ids = DB::table('operation_order_details')
//                                 ->whereIn('operation_order_id', $operation_order_store_ids)
//                                 ->whereIn('out_item_id', $request->items)->pluck('id')->toArray();
                                 
//         $from_factory = DB::table('operation_order_results')
//                             ->whereIn('order_details_id', $operation_ord_ids)                           
//                             ->get();
        

//         $operation_ord_item_ids = DB::table('operation_order_details')
//                                 ->whereIn('item_id', $request->items)->pluck('id')->toArray();

//         $scrap_factory = DB::table('operation_order_result_details')
//                                 ->whereIn('order_details_id', $operation_ord_item_ids)
//                                 ->where('damage_type', 'scrap')                           
//                                 ->get();    

//         $pieces_factory = DB::table('operation_order_result_details')
//                                 ->whereIn('order_details_id', $operation_ord_item_ids)
//                                 ->where('damage_type', 'pieces')                           
//                                 ->get();    
//         // dd($from_factory);  
//         // get inn and out from table OrderDetail in and out and from factory and to factory and order factory ...
//         $initQun = 0;
        
//         $startDats = QuantityDailies::with('item')
            
//             ->where('ownerable_type', 'App\Models\Store')
//             ->whereIn('ownerable_id', $request->stores_id)
//             ->whereBetween('created_at', [$request->date_from, $request->date_to])
//             ->whereIn('item_id', $request->items)->get();
        
        
//         foreach ($startDats as $startDat) {
//             if ($startDat->type == 0 || $startDat->type == 2 || $startDat->type == 4 || $startDat->type == 6 ) {
//                 $initQun+= $startDat->quantity;
//             } else {
//                  $initQun -= $startDat->quantity;
//             }
//         }
//         // dd($startDats);
//         $resources = Quantity::with('item')

//             ->where('ownerable_type', 'App\Models\Store')

//             ->whereIn('ownerable_id', $request->stores_id)

//             ->whereIn('item_id', $request->items)->get();

// //        return $resources;

// //        return $request->all();

//         return view('reports.Newitem_all_report', compact('orders_in', 'orders_in_return', 'orders_out', 'orders_in_return', 'orders_out_return', 'loads_from', 'loads_to','to_factory','from_factory', 'scrap_factory','pieces_factory','resources','initQun'));

//     }
    
    // public function new_item_all_report(Request $request) {
    //     $this->validate($request, [
    //         'items' => 'required',
    //         'date_from' => 'required',
    //         'date_to' => 'required',
    //     ]);

    //     $orders_in = OrderDetail::with('order.ownerable', 'item')
    //                     ->where(function ($q) use ($request) {
    //                         if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                             $q->whereIn('store_id', $request->stores_id);
    //                         }
    //                     })
    //                     ->whereHas('order', function ($q) use ($request) {
    //                         $q->whereBetween('date', [$request->date_from, $request->date_to])
    //                         ->where('is_return', 0)
    //                         ->where('type', 'in');
    //                     })->whereIn('item_id', $request->items)->get();

    //     $orders_in_return = OrderDetail::with('order.ownerable', 'item')
    //                     ->where(function ($q) use ($request) {
    //                         if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                             $q->whereIn('store_id', $request->stores_id);
    //                         }
    //                     })
    //                     ->whereHas('order', function ($q) use ($request) {
    //                         $q->whereBetween('date', [$request->date_from, $request->date_to])
    //                         ->where('is_return', true)
    //                         ->where('type', 'out');
    //                     })
    //                     ->whereIn('item_id', $request->items)->get();

    //     $orders_out = OrderDetail::with('order.ownerable', 'item')
    //                     ->where(function ($q) use ($request) {
    //                         if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                             $q->whereIn('store_id', $request->stores_id);
    //                         }
    //                     })
    //                     ->whereHas('order', function ($q) use ($request) {
    //                         $q->whereBetween('date', [$request->date_from, $request->date_to])
    //                         ->where('is_return', 0)
    //                         ->where('type', 'out');
    //                     })->whereIn('item_id', $request->items)->get();

    //     $orders_out_return = OrderDetail::with('order.ownerable', 'item')
    //                     ->where(function ($q) use ($request) {
    //                         if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                             $q->whereIn('store_id', $request->stores_id);
    //                         }
    //                     })
    //                     ->whereHas('order', function ($q) use ($request) {
    //                         $q->whereBetween('date', [$request->date_from, $request->date_to])
    //                         ->where('is_return', 1)
    //                         ->where('type', 'in');
    //                     })->whereIn('item_id', $request->items)->get();

    //     $loads_from = LoadDetail::with('parent', 'item')
    //             ->where(function ($q) use ($request) {
    //                 if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                     $q->whereHas('parent', function ($q2) use ($request) {
    //                         $q2->whereIn('from_id', $request->stores_id);
    //                         // $q2->OrWhereIn('to_id', $request->stores_id);
    //                     });
    //                 }
    //             })
    //             ->whereHas('parent', function ($q) use ($request) {
    //                 $q->whereBetween('date', [$request->date_from, $request->date_to]);
    //             })->whereIn('item_id', $request->items)->get();

    //     $loads_to = LoadDetail::with('parent', 'item')
    //             ->where(function ($q) use ($request) {
    //                 if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                     $q->whereHas('parent', function ($q2) use ($request) {
    //                         // $q2->whereIn('from_id', $request->stores_id);
    //                         $q2->whereIn('to_id', $request->stores_id);
    //                     });
    //                 }
    //             })
    //             ->whereHas('parent', function ($q) use ($request) {
    //                 $q->whereBetween('date', [$request->date_from, $request->date_to]);
    //             })->whereIn('item_id', $request->items)->get();


    //     $coll_orders_in = OrderDetail::with('order.ownerable', 'item')
    //                     ->where(function ($q) use ($request) {
    //                         if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                             $q->whereIn('store_id', $request->stores_id);
    //                         }
    //                     })
    //                     ->whereHas('order', function ($q) use ($request) {
    //                         $q->where('is_return', 0)
    //                         ->where('type', 'in');
    //                     })->whereIn('item_id', $request->items)->get();

    //     $coll_orders_in_return = OrderDetail::with('order.ownerable', 'item')
    //                     ->where(function ($q) use ($request) {
    //                         if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                             $q->whereIn('store_id', $request->stores_id);
    //                         }
    //                     })
    //                     ->whereHas('order', function ($q) use ($request) {
    //                         $q->where('is_return', true);
    //                         $q->where('type', 'out');
    //                     })
    //                     ->whereIn('item_id', $request->items)->get();

    //     $coll_orders_out = OrderDetail::with('order.ownerable', 'item')
    //                     ->where(function ($q) use ($request) {
    //                         if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                             $q->whereIn('store_id', $request->stores_id);
    //                         }
    //                     })
    //                     ->whereHas('order', function ($q) use ($request) {
    //                         $q->where('is_return', 0)
    //                         ->where('type', 'out');
    //                     })->whereIn('item_id', $request->items)->get();

    //     $coll_orders_out_return = OrderDetail::with('order.ownerable', 'item')
    //                     ->where(function ($q) use ($request) {
    //                         if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                             $q->whereIn('store_id', $request->stores_id);
    //                         }
    //                     })
    //                     ->whereHas('order', function ($q) use ($request) {
    //                         $q->where('is_return', 1)
    //                         ->where('type', 'in');
    //                     })->whereIn('item_id', $request->items)->get();

    //     $coll_loads_from = LoadDetail::with('parent', 'item')
    //             ->where(function ($q) use ($request) {
    //                 if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                     $q->whereHas('parent', function ($q2) use ($request) {
    //                         $q2->whereIn('from_id', $request->stores_id);
    //                         // $q2->OrWhereIn('to_id', $request->stores_id);
    //                     });
    //                 }
    //             })
    //             ->whereHas('parent', function ($q) use ($request) {
    //                 // $q->whereBetween('date', [$request->date_from, $request->date_to]);
    //             })->whereIn('item_id', $request->items)->get();

    //     $coll_loads_to = LoadDetail::with('parent', 'item')
    //             ->where(function ($q) use ($request) {
    //                 if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                     $q->whereHas('parent', function ($q2) use ($request) {
    //                         // $q2->whereIn('from_id', $request->stores_id);
    //                         $q2->whereIn('to_id', $request->stores_id);
    //                     });
    //                 }
    //             })
    //             ->whereHas('parent', function ($q) use ($request) {
    //                 // $q->whereBetween('date', [$request->date_from, $request->date_to]);
    //             })->whereIn('item_id', $request->items)->get();
            

    //     $operation_order_store_ids = DB::table('operation_orders')
    //                                     ->whereIn('store_id', $request->stores_id)->pluck('id')->toArray();


    //     $to_factory = DB::table('operation_order_details')
    //                     ->whereIn('operation_order_id', $operation_order_store_ids)
    //                     ->whereIn('item_id', $request->items)->get();


    //     $operation_ord_ids = DB::table('operation_order_details')
    //                             ->whereIn('operation_order_id', $operation_order_store_ids)
    //                             ->whereIn('out_item_id', $request->items)->pluck('id')->toArray();
                                 
    //     $from_factory = DB::table('operation_order_results')
    //                         ->whereIn('order_details_id', $operation_ord_ids)                           
    //                         ->get();
        

    //     $operation_ord_item_ids = DB::table('operation_order_details')
    //                                 ->whereIn('item_id', $request->items)->pluck('id')->toArray();

    //     $scrap_factory = DB::table('operation_order_result_details')
    //                         ->whereIn('order_details_id', $operation_ord_item_ids)
    //                         ->where('damage_type', 'scrap')                           
    //                         ->get();    

    //     $pieces_factory = DB::table('operation_order_result_details')
    //                         ->whereIn('order_details_id', $operation_ord_item_ids)
    //                         ->where('damage_type', 'pieces')                           
    //                         ->get();

    //     $startDate = Carbon::parse($request->date_from);
    //     $endDate = Carbon::parse($request->date_to);

    //     $period = CarbonPeriod::create($startDate, $endDate);

    //     $dates = [];
    //     foreach($period as $date) {
    //         $dates[] = $date->toDateString();
    //     }

    //     $resources = Quantity::with('item')
    //         ->where('ownerable_type', 'App\Models\Store')
    //         ->whereIn('ownerable_id', $request->stores_id)
    //         ->whereIn('item_id', $request->items)->get();

            
    //     return view('reports.new_item_all_report', compact('orders_in', 'orders_in_return', 'orders_out', 'orders_in_return', 'orders_out_return', 'loads_from', 'loads_to', 'coll_orders_in', 'coll_orders_in_return', 'coll_orders_out', 'coll_orders_in_return', 'coll_orders_out_return', 'coll_loads_from', 'coll_loads_to', 'to_factory', 'from_factory', 'scrap_factory', 'pieces_factory', 'resources', 'dates'));
    // }
    
        public function new_item_all_report(Request $request) {
        $this->validate($request, [
            'items' => 'required',
            'date_from' => 'required',
            'date_to' => 'required',
        ]);

        $orders_in = OrderDetail::with('order.ownerable', 'item')
                        ->where(function ($q) use ($request) {
                            if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
                                $q->whereIn('store_id', $request->stores_id);
                            }
                        })
                        ->whereHas('order', function ($q) use ($request) {
                            $q->where('is_return', 0)
                            ->where('type', 'in');
                        })->whereIn('item_id', $request->items)->get();

        $orders_in_return = OrderDetail::with('order.ownerable', 'item')
                        ->where(function ($q) use ($request) {
                            if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
                                $q->whereIn('store_id', $request->stores_id);
                            }
                        })
                        ->whereHas('order', function ($q) use ($request) {
                            $q->where('is_return', true)
                            ->where('type', 'out');
                        })
                        ->whereIn('item_id', $request->items)->get();

        $orders_out = OrderDetail::with('order.ownerable', 'item')
                        ->where(function ($q) use ($request) {
                            if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
                                $q->whereIn('store_id', $request->stores_id);
                            }
                        })
                        ->whereHas('order', function ($q) use ($request) {
                            $q->where('is_return', 0)
                            ->where('type', 'out');
                        })->whereIn('item_id', $request->items)->get();

        $orders_out_return = OrderDetail::with('order.ownerable', 'item')
                        ->where(function ($q) use ($request) {
                            if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
                                $q->whereIn('store_id', $request->stores_id);
                            }
                        })
                        ->whereHas('order', function ($q) use ($request) {
                            $q->where('is_return', 1)
                            ->where('type', 'in');
                        })->whereIn('item_id', $request->items)->get();

        $loads_from = LoadDetail::with('parent', 'item')
                ->where(function ($q) use ($request) {
                    if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
                        $q->whereHas('parent', function ($q2) use ($request) {
                            $q2->whereIn('from_id', $request->stores_id);
                            // $q2->OrWhereIn('to_id', $request->stores_id);
                        });
                    }
                })
                ->whereIn('item_id', $request->items)->get();

        $loads_to = LoadDetail::with('parent', 'item')
                ->where(function ($q) use ($request) {
                    if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
                        $q->whereHas('parent', function ($q2) use ($request) {
                            // $q2->whereIn('from_id', $request->stores_id);
                            $q2->whereIn('to_id', $request->stores_id);
                        });
                    }
                })
                ->whereIn('item_id', $request->items)->get();


        $coll_orders_in = OrderDetail::with('order.ownerable', 'item')
                        ->where(function ($q) use ($request) {
                            if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
                                $q->whereIn('store_id', $request->stores_id);
                            }
                        })
                        ->whereHas('order', function ($q) use ($request) {
                            $q->where('is_return', 0)
                              ->where('type', 'in');
                        })->whereIn('item_id', $request->items)->get();

        $coll_orders_in_return = OrderDetail::with('order.ownerable', 'item')
                        ->where(function ($q) use ($request) {
                            if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
                                $q->whereIn('store_id', $request->stores_id);
                            }
                        })
                        ->whereHas('order', function ($q) use ($request) {
                            $q->where('is_return', true)
                              ->where('type', 'out');
                        })
                        ->whereIn('item_id', $request->items)->get();

        $coll_orders_out = OrderDetail::with('order.ownerable', 'item')
                        ->where(function ($q) use ($request) {
                            if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
                                $q->whereIn('store_id', $request->stores_id);
                            }
                        })
                        ->whereHas('order', function ($q) use ($request) {
                            $q->where('is_return', 0)
                              ->where('type', 'out');
                        })->whereIn('item_id', $request->items)->get();

        $coll_orders_out_return = OrderDetail::with('order.ownerable', 'item')
                        ->where(function ($q) use ($request) {
                            if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
                                $q->whereIn('store_id', $request->stores_id);
                            }
                        })
                        ->whereHas('order', function ($q) use ($request) {
                            $q->where('is_return', 1)
                              ->where('type', 'in');
                        })->whereIn('item_id', $request->items)->get();

        $coll_loads_from = LoadDetail::with('parent', 'item')
                ->where(function ($q) use ($request) {
                    if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
                        $q->whereHas('parent', function ($q2) use ($request) {
                            $q2->whereIn('from_id', $request->stores_id);
                            // $q2->OrWhereIn('to_id', $request->stores_id);
                        });
                    }
                })
                ->whereHas('parent', function ($q) use ($request) {
                })->whereIn('item_id', $request->items)->get();

        $coll_loads_to = LoadDetail::with('parent', 'item')
                ->where(function ($q) use ($request) {
                    if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
                        $q->whereHas('parent', function ($q2) use ($request) {
                            // $q2->whereIn('from_id', $request->stores_id);
                            $q2->whereIn('to_id', $request->stores_id);
                        });
                    }
                })
                ->whereHas('parent', function ($q) use ($request) {
                })->whereIn('item_id', $request->items)->get();
            

        $operation_order_store_ids = DB::table('operation_orders')
                                        ->whereIn('store_id', $request->stores_id)->pluck('id')->toArray();


        $to_factory = DB::table('operation_order_details')
                        ->whereIn('operation_order_id', $operation_order_store_ids)
                        ->whereIn('item_id', $request->items)->get();


        $operation_ord_ids = DB::table('operation_order_details')
                                ->whereIn('operation_order_id', $operation_order_store_ids)
                                ->whereIn('out_item_id', $request->items)->pluck('id')->toArray();
                                 
        $from_factory = DB::table('operation_order_results')
                            ->whereIn('order_details_id', $operation_ord_ids)                           
                            ->get();
        

        $operation_ord_item_ids = DB::table('operation_order_details')
                                    ->whereIn('item_id', $request->items)->pluck('id')->toArray();

        $scrap_factory = DB::table('operation_order_result_details')
                            ->whereIn('order_details_id', $operation_ord_item_ids)
                            ->where('damage_type', 'scrap')                           
                            ->get();    

        $pieces_factory = DB::table('operation_order_result_details')
                            ->whereIn('order_details_id', $operation_ord_item_ids)
                            ->where('damage_type', 'pieces')                           
                            ->get();

        $startDate = Carbon::parse($request->date_from);
        $endDate = Carbon::parse($request->date_to);

        $period = CarbonPeriod::create($startDate, $endDate);

        $dates = [];
        foreach($period as $date) {
            $dates[] = $date->toDateString();
        }

        $resources = Quantity::with('item')
            ->where('ownerable_type', 'App\Models\Store')
            ->whereIn('ownerable_id', $request->stores_id)
            ->whereIn('item_id', $request->items)->get();

            
        return view('reports.new_item_all_report', compact('orders_in', 'orders_in_return', 'orders_out', 'orders_in_return', 'orders_out_return', 'loads_from', 'loads_to', 'coll_orders_in', 'coll_orders_in_return', 'coll_orders_out', 'coll_orders_in_return', 'coll_orders_out_return', 'coll_loads_from', 'coll_loads_to', 'to_factory', 'from_factory', 'scrap_factory', 'pieces_factory', 'resources', 'dates'));
    }
    
    // Momaher
    // Old
    //     public function item_movements_report(Request $request) {
    //     $this->validate($request, [
    //         'items' => 'required',
    //         'date_from' => 'required',
    //         'date_to' => 'required',
    //     ]);

    //     $orders_in = OrderDetail::with('order.ownerable', 'item')
    //                     ->where(function ($q) use ($request) {
    //                         if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                             $q->whereIn('store_id', $request->stores_id);
    //                         }
    //                     })
    //                     ->whereHas('order', function ($q) use ($request) {
    //                         $q->whereBetween('date', [$request->date_from, $request->date_to])
    //                         ->where('is_return', 0)
    //                         ->where('type', 'in');
    //                     })->whereIn('item_id', $request->items)->get();

    //     $orders_in_return = OrderDetail::with('order.ownerable', 'item')
    //                     ->where(function ($q) use ($request) {
    //                         if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                             $q->whereIn('store_id', $request->stores_id);
    //                         }
    //                     })
    //                     ->whereHas('order', function ($q) use ($request) {
    //                         $q->whereBetween('date', [$request->date_from, $request->date_to])
    //                         ->where('is_return', true)
    //                         ->where('type', 'out');
    //                     })
    //                     ->whereIn('item_id', $request->items)->get();

    //     $orders_out = OrderDetail::with('order.ownerable', 'item')
    //                     ->where(function ($q) use ($request) {
    //                         if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                             $q->whereIn('store_id', $request->stores_id);
    //                         }
    //                     })
    //                     ->whereHas('order', function ($q) use ($request) {
    //                         $q->whereBetween('date', [$request->date_from, $request->date_to])
    //                         ->where('is_return', 0)
    //                         ->where('type', 'out');
    //                     })->whereIn('item_id', $request->items)->get();

    //     $orders_out_return = OrderDetail::with('order.ownerable', 'item')
    //                     ->where(function ($q) use ($request) {
    //                         if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                             $q->whereIn('store_id', $request->stores_id);
    //                         }
    //                     })
    //                     ->whereHas('order', function ($q) use ($request) {
    //                         $q->whereBetween('date', [$request->date_from, $request->date_to])
    //                         ->where('is_return', 1)
    //                         ->where('type', 'in');
    //                     })->whereIn('item_id', $request->items)->get();

    //     $loads_from = LoadDetail::with('parent', 'item')
    //             ->where(function ($q) use ($request) {
    //                 if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                     $q->whereHas('parent', function ($q2) use ($request) {
    //                         $q2->whereIn('from_id', $request->stores_id);
    //                     });
    //                 }
    //             })
    //             ->whereHas('parent', function ($q) use ($request) {
    //                 $q->whereBetween('date', [$request->date_from, $request->date_to]);
    //             })->whereIn('item_id', $request->items)->get();

    //     $loads_to = LoadDetail::with('parent', 'item')
    //             ->where(function ($q) use ($request) {
    //                 if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                     $q->whereHas('parent', function ($q2) use ($request) {
    //                         $q2->whereIn('to_id', $request->stores_id);
    //                     });
    //                 }
    //             })
    //             ->whereHas('parent', function ($q) use ($request) {
    //                 $q->whereBetween('date', [$request->date_from, $request->date_to]);
    //             })->whereIn('item_id', $request->items)->get();

    //     $coll_orders_in = OrderDetail::with('order.ownerable', 'item')
    //                     ->where(function ($q) use ($request) {
    //                         if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                             $q->whereIn('store_id', $request->stores_id);
    //                         }
    //                     })
    //                     ->whereHas('order', function ($q) use ($request) {
    //                         $q->where('is_return', 0)
    //                           ->where('type', 'in');
    //                     })->whereIn('item_id', $request->items)->get();

    //     $coll_orders_in_return = OrderDetail::with('order.ownerable', 'item')
    //                     ->where(function ($q) use ($request) {
    //                         if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                             $q->whereIn('store_id', $request->stores_id);
    //                         }
    //                     })
    //                     ->whereHas('order', function ($q) use ($request) {
    //                         $q->where('is_return', true)
    //                           ->where('type', 'out');
    //                     })
    //                     ->whereIn('item_id', $request->items)->get();

    //     $coll_orders_out = OrderDetail::with('order.ownerable', 'item')
    //                     ->where(function ($q) use ($request) {
    //                         if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                             $q->whereIn('store_id', $request->stores_id);
    //                         }
    //                     })
    //                     ->whereHas('order', function ($q) use ($request) {
    //                         $q->where('is_return', 0)
    //                           ->where('type', 'out');
    //                     })->whereIn('item_id', $request->items)->get();

    //     $coll_orders_out_return = OrderDetail::with('order.ownerable', 'item')
    //                     ->where(function ($q) use ($request) {
    //                         if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                             $q->whereIn('store_id', $request->stores_id);
    //                         }
    //                     })
    //                     ->whereHas('order', function ($q) use ($request) {
    //                         $q->where('is_return', 1)
    //                           ->where('type', 'in');
    //                     })->whereIn('item_id', $request->items)->get();

    //     $coll_loads_from = LoadDetail::with('parent', 'item')
    //             ->where(function ($q) use ($request) {
    //                 if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                     $q->whereHas('parent', function ($q2) use ($request) {
    //                         $q2->whereIn('from_id', $request->stores_id);
    //                     });
    //                 }
    //             })
    //             ->whereIn('item_id', $request->items)->get();

    //     $coll_loads_to = LoadDetail::with('parent', 'item')
    //             ->where(function ($q) use ($request) {
    //                 if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                     $q->whereHas('parent', function ($q2) use ($request) {
    //                         $q2->whereIn('to_id', $request->stores_id);
    //                     });
    //                 }
    //             })
    //             ->whereIn('item_id', $request->items)->get();
            

    //     // Filter operation orders by date range
    //     $operation_order_store_ids = DB::table('operation_orders')
    //                                     ->whereIn('store_id', $request->stores_id)
    //                                     ->whereBetween('date', [$request->date_from, $request->date_to])
    //                                     ->pluck('id')->toArray();


    //     $to_factory = DB::table('operation_order_details')
    //                     ->whereIn('operation_order_id', $operation_order_store_ids)
    //                     ->whereIn('item_id', $request->items)->get();


    //     $operation_ord_ids = DB::table('operation_order_details')
    //                             ->whereIn('operation_order_id', $operation_order_store_ids)
    //                             ->whereIn('out_item_id', $request->items)->pluck('id')->toArray();
                                 
    //     $from_factory = DB::table('operation_order_results')
    //                         ->whereIn('order_details_id', $operation_ord_ids)                           
    //                         ->get();
        
    //     // Get operation order details IDs for scrap and pieces (filtered by date through operation_order_store_ids)
    //     $operation_ord_item_ids = DB::table('operation_order_details')
    //                                 ->whereIn('operation_order_id', $operation_order_store_ids)
    //                                 ->whereIn('item_id', $request->items)
    //                                 ->pluck('id')->toArray();

    //     $scrap_factory = DB::table('operation_order_result_details')
    //                         ->whereIn('order_details_id', $operation_ord_item_ids)
    //                         ->where('damage_type', 'scrap')                           
    //                         ->get();    

    //     $pieces_factory = DB::table('operation_order_result_details')
    //                         ->whereIn('order_details_id', $operation_ord_item_ids)
    //                         ->where('damage_type', 'pieces')                           
    //                         ->get();

    //     $startDate = Carbon::parse($request->date_from);
    //     $endDate = Carbon::parse($request->date_to);

    //     $period = CarbonPeriod::create($startDate, $endDate);

    //     $dates = [];
    //     foreach($period as $date) {
    //         $dates[] = $date->toDateString();
    //     }

    //     $resources = Quantity::with('item')
    //         ->where('ownerable_type', 'App\Models\Store')
    //         ->whereIn('ownerable_id', $request->stores_id)
    //         ->whereIn('item_id', $request->items)->get();

    //     // Calculate opening balance (balance before date_from)
    //     $openingBalance = 0;
    //     $previousDate = Carbon::parse($request->date_from)->subDay()->format('Y-m-d');
    //     if (count($resources) == 1) {
    //         $initBalance = floatval($resources[0]->init);
    //         $dateBefore = $previousDate;
            
    //         // Get all transactions before date_from
    //         $prev_orders_in = OrderDetail::with('order.ownerable', 'item')
    //                         ->where(function ($q) use ($request) {
    //                             if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                                 $q->whereIn('store_id', $request->stores_id);
    //                             }
    //                         })
    //                         ->whereHas('order', function ($q) use ($request) {
    //                             $q->where('date', '<', $request->date_from)
    //                             ->where('is_return', 0)
    //                             ->where('type', 'in');
    //                         })->whereIn('item_id', $request->items)->get();

    //         $prev_orders_out = OrderDetail::with('order.ownerable', 'item')
    //                         ->where(function ($q) use ($request) {
    //                             if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                                 $q->whereIn('store_id', $request->stores_id);
    //                             }
    //                         })
    //                         ->whereHas('order', function ($q) use ($request) {
    //                             $q->where('date', '<', $request->date_from)
    //                             ->where('is_return', 0)
    //                             ->where('type', 'out');
    //                         })->whereIn('item_id', $request->items)->get();

    //         $prev_orders_in_return = OrderDetail::with('order.ownerable', 'item')
    //                         ->where(function ($q) use ($request) {
    //                             if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                                 $q->whereIn('store_id', $request->stores_id);
    //                             }
    //                         })
    //                         ->whereHas('order', function ($q) use ($request) {
    //                             $q->where('date', '<', $request->date_from)
    //                             ->where('is_return', true)
    //                             ->where('type', 'out');
    //                         })
    //                         ->whereIn('item_id', $request->items)->get();

    //         $prev_orders_out_return = OrderDetail::with('order.ownerable', 'item')
    //                         ->where(function ($q) use ($request) {
    //                             if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                                 $q->whereIn('store_id', $request->stores_id);
    //                             }
    //                         })
    //                         ->whereHas('order', function ($q) use ($request) {
    //                             $q->where('date', '<', $request->date_from)
    //                             ->where('is_return', 1)
    //                             ->where('type', 'in');
    //                         })->whereIn('item_id', $request->items)->get();

    //         $prev_loads_from = LoadDetail::with('parent', 'item')
    //                 ->where(function ($q) use ($request) {
    //                     if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                         $q->whereHas('parent', function ($q2) use ($request) {
    //                             $q2->whereIn('from_id', $request->stores_id);
    //                         });
    //                     }
    //                 })
    //                 ->whereHas('parent', function ($q) use ($request) {
    //                     $q->where('date', '<', $request->date_from);
    //                 })->whereIn('item_id', $request->items)->get();

    //         $prev_loads_to = LoadDetail::with('parent', 'item')
    //                 ->where(function ($q) use ($request) {
    //                     if ($request->has('stores_id') && $request->stores_id != null && $request->stores_id != '') {
    //                         $q->whereHas('parent', function ($q2) use ($request) {
    //                             $q2->whereIn('to_id', $request->stores_id);
    //                         });
    //                     }
    //                 })
    //                 ->whereHas('parent', function ($q) use ($request) {
    //                     $q->where('date', '<', $request->date_from);
    //                 })->whereIn('item_id', $request->items)->get();

    //         $prev_operation_order_store_ids = DB::table('operation_orders')
    //                                         ->whereIn('store_id', $request->stores_id)
    //                                         ->where('date', '<', $request->date_from)
    //                                         ->pluck('id')->toArray();

    //         $prev_to_factory = DB::table('operation_order_details')
    //                         ->whereIn('operation_order_id', $prev_operation_order_store_ids)
    //                         ->whereIn('item_id', $request->items)->get();

    //         $prev_operation_ord_ids = DB::table('operation_order_details')
    //                                 ->whereIn('operation_order_id', $prev_operation_order_store_ids)
    //                                 ->whereIn('out_item_id', $request->items)->pluck('id')->toArray();
                                     
    //         $prev_from_factory = DB::table('operation_order_results')
    //                             ->whereIn('order_details_id', $prev_operation_ord_ids)                           
    //                             ->get();

    //         $prev_operation_ord_item_ids = DB::table('operation_order_details')
    //                                     ->whereIn('item_id', $request->items)
    //                                     ->whereIn('operation_order_id', $prev_operation_order_store_ids)
    //                                     ->pluck('id')->toArray();

    //         $prev_scrap_factory = DB::table('operation_order_result_details')
    //                             ->whereIn('order_details_id', $prev_operation_ord_item_ids)
    //                             ->where('damage_type', 'scrap')                           
    //                             ->get();    

    //         $prev_pieces_factory = DB::table('operation_order_result_details')
    //                             ->whereIn('order_details_id', $prev_operation_ord_item_ids)
    //                             ->where('damage_type', 'pieces')                           
    //                             ->get();

    //         // Calculate opening balance
    //         $openingBalance = $initBalance;
            
    //         // Subtract sales (orders_in)
    //         foreach($prev_orders_in as $item) {
    //             $openingBalance -= floatval($item->quantity);
    //         }
            
    //         // Add purchases (orders_out)
    //         foreach($prev_orders_out as $item) {
    //             $openingBalance += floatval($item->quantity);
    //         }
            
    //         // Add sales returns (orders_in_return)
    //         foreach($prev_orders_in_return as $item) {
    //             $openingBalance += floatval($item->quantity);
    //         }
            
    //         // Subtract purchase returns (orders_out_return)
    //         foreach($prev_orders_out_return as $item) {
    //             $openingBalance -= floatval($item->quantity);
    //         }
            
    //         // Subtract loads from
    //         foreach($prev_loads_from as $item) {
    //             $openingBalance -= floatval($item->quantity);
    //         }
            
    //         // Add loads to
    //         foreach($prev_loads_to as $item) {
    //             $openingBalance += floatval($item->quantity);
    //         }
            
    //         // Subtract to factory
    //         foreach($prev_to_factory as $item) {
    //             $result = DB::table('operation_order_results')
    //                         ->where('order_details_id', $item->id)                           
    //                         ->first();
    //             if($result){
    //                 if($result->old_item_quantity){
    //                     $resultQnt = floatval($result->old_item_quantity);
    //                 } else {
    //                     $resultQnt = floatval($item->old_item_quantity);
    //                 }
    //             } else {
    //                 $resultQnt = floatval($item->old_item_quantity);
    //             }
    //             $openingBalance -= $resultQnt;
    //         }
            
    //         // Add from factory
    //         foreach($prev_from_factory as $item) {
    //             $openingBalance += floatval($item->actual_output);
    //         }
            
    //         // Subtract scrap
    //         foreach($prev_scrap_factory as $item) {
    //             $openingBalance -= floatval($item->damage_weight);
    //         }
            
    //         // Subtract pieces
    //         foreach($prev_pieces_factory as $item) {
    //             $openingBalance -= floatval($item->damage_weight);
    //         }
            
    //         // Calculate balance at date_to (not current balance)
    //         // This is: opening balance + all transactions up to date_to
    //         $balanceAtDateTo = $openingBalance;
            
    //         // Add all transactions in the date range
    //         foreach($orders_in as $item) {
    //             $balanceAtDateTo -= floatval($item->quantity);
    //         }
    //         foreach($orders_out as $item) {
    //             $balanceAtDateTo += floatval($item->quantity);
    //         }
    //         foreach($orders_in_return as $item) {
    //             $balanceAtDateTo += floatval($item->quantity);
    //         }
    //         foreach($orders_out_return as $item) {
    //             $balanceAtDateTo -= floatval($item->quantity);
    //         }
    //         foreach($loads_from as $item) {
    //             $balanceAtDateTo -= floatval($item->quantity);
    //         }
    //         foreach($loads_to as $item) {
    //             $balanceAtDateTo += floatval($item->quantity);
    //         }
            
    //         // Factory transactions
    //         foreach($to_factory as $item) {
    //             $operationOrder = DB::table('operation_orders')->where('id', $item->operation_order_id)->first();
    //             if (!$operationOrder) continue;
    //             $orderDate = Carbon::parse($operationOrder->date);
    //             if ($orderDate->between(Carbon::parse($request->date_from), Carbon::parse($request->date_to))) {
    //                 $result = DB::table('operation_order_results')
    //                             ->where('order_details_id', $item->id)                           
    //                             ->first();
    //                 if($result){
    //                     if($result->old_item_quantity){
    //                         $resultQnt = floatval($result->old_item_quantity);
    //                     } else {
    //                         $resultQnt = floatval($item->old_item_quantity);
    //                     }
    //                 } else {
    //                     $resultQnt = floatval($item->old_item_quantity);
    //                 }
    //                 $balanceAtDateTo -= $resultQnt;
    //             }
    //         }
    //         foreach($from_factory as $item) {
    //             $operationOrder = DB::table('operation_orders')->where('id', $item->operation_order_id)->first();
    //             if (!$operationOrder) continue;
    //             $orderDate = Carbon::parse($operationOrder->date);
    //             if ($orderDate->between(Carbon::parse($request->date_from), Carbon::parse($request->date_to))) {
    //                 $balanceAtDateTo += floatval($item->actual_output);
    //             }
    //         }
    //         foreach($scrap_factory as $item) {
    //             $operationOrder = DB::table('operation_orders')->where('id', $item->operation_order_id)->first();
    //             if (!$operationOrder) continue;
    //             $orderDate = Carbon::parse($operationOrder->date);
    //             if ($orderDate->between(Carbon::parse($request->date_from), Carbon::parse($request->date_to))) {
    //                 $balanceAtDateTo -= floatval($item->damage_weight);
    //             }
    //         }
    //         foreach($pieces_factory as $item) {
    //             $operationOrder = DB::table('operation_orders')->where('id', $item->operation_order_id)->first();
    //             if (!$operationOrder) continue;
    //             $orderDate = Carbon::parse($operationOrder->date);
    //             if ($orderDate->between(Carbon::parse($request->date_from), Carbon::parse($request->date_to))) {
    //                 $balanceAtDateTo -= floatval($item->damage_weight);
    //             }
    //         }
    //     } else {
    //         $balanceAtDateTo = null;
    //     }

            
    //     return view('reports.item_movements_report', compact('orders_in', 'orders_in_return', 'orders_out', 'orders_in_return', 'orders_out_return', 'loads_from', 'loads_to', 'coll_orders_in', 'coll_orders_in_return', 'coll_orders_out', 'coll_orders_in_return', 'coll_orders_out_return', 'coll_loads_from', 'coll_loads_to', 'to_factory', 'from_factory', 'scrap_factory', 'pieces_factory', 'resources', 'dates', 'openingBalance', 'previousDate', 'balanceAtDateTo'));
    // }
    
    // New
/**
 * Net signed stock movement for the given items/stores between two dates (inclusive).
 *
 * Signs match the report body exactly, so that
 *     opening + netStockMovement(from, to) == closing
 * always holds:
 *   sale (in, not return)          -> stock decreases
 *   purchase (out, not return)     -> stock increases
 *   return from client (out, ret)  -> stock increases
 *   return to supplier (in, ret)   -> stock decreases
 *   transfer out / in              -> decreases / increases
 *   consumed by factory, scrap, offcuts -> decreases
 *   produced by factory            -> increases
 *
 * Aggregated in SQL on purpose: the range can span years, and we must never
 * load the rows just to add up their quantities.
 */
private function netStockMovement(array $itemIds, array $storeIds, $from, $to) {
    if (empty($itemIds)) return 0.0;
    $hasStores = !empty($storeIds);
    $net = 0.0;

    // --- Invoices ---------------------------------------------------------
    $orderRows = DB::table('order_details as od')
        ->join('orders as o', 'o.id', '=', 'od.order_id')
        ->whereIn('od.item_id', $itemIds)
        ->whereBetween('o.date2', [$from, $to])
        ->when($hasStores, function ($q) use ($storeIds) {
            return $q->whereIn('od.store_id', $storeIds);
        })
        ->groupBy('o.type', 'o.is_return')
        ->select('o.type', 'o.is_return', DB::raw('SUM(od.quantity) as qty'))
        ->get();

    foreach ($orderRows as $r) {
        $qty = (float) $r->qty;
        if ($r->type == 'out') {
            $net += $qty;               // purchase, or return from client
        } else {
            $net -= $qty;               // sale, or return to supplier
        }
    }

    // --- Transfers between stores ----------------------------------------
    if ($hasStores) {
        $net -= (float) DB::table('load_details as ld')
            ->join('loads as l', 'l.id', '=', 'ld.load_id')
            ->whereIn('ld.item_id', $itemIds)
            ->whereIn('l.from_id', $storeIds)
            ->whereBetween('l.date', [$from, $to])
            ->sum('ld.quantity');

        $net += (float) DB::table('load_details as ld')
            ->join('loads as l', 'l.id', '=', 'ld.load_id')
            ->whereIn('ld.item_id', $itemIds)
            ->whereIn('l.to_id', $storeIds)
            ->whereBetween('l.date', [$from, $to])
            ->sum('ld.quantity');
    }

    // --- Factory ----------------------------------------------------------
    // Everything below joins operation_orders and filters on its date/store
    // directly. Collecting ids with pluck() and feeding them to whereIn()
    // degrades badly once the window is long — and the opening balance always
    // asks for "from-date .. today", so a long window is the normal case.

    // Raw material consumed. Mirrors the display loop: prefer the result row's
    // old_item_quantity when it is set and non-zero, else the detail's own.
    // The latest result per detail is resolved with one GROUP BY pass plus a
    // join on the winning id. The correlated `WHERE id = (SELECT MAX(id) ...
    // WHERE order_details_id = r1.order_details_id)` form re-runs for every one
    // of the ~13.5k result rows and measured 38s on its own.
    $net -= (float) DB::table('operation_order_details as d')
        ->join('operation_orders as oo', 'oo.id', '=', 'd.operation_order_id')
        ->leftJoin(DB::raw('(SELECT res.order_details_id, res.old_item_quantity
                               FROM operation_order_results res
                               JOIN (SELECT order_details_id, MAX(id) AS mx
                                       FROM operation_order_results
                                      GROUP BY order_details_id) pick
                                 ON pick.mx = res.id) as r'),
                  'r.order_details_id', '=', 'd.id')
        ->whereBetween('oo.date', [$from, $to])
        ->when($hasStores, function ($q) use ($storeIds) {
            return $q->whereIn('oo.store_id', $storeIds);
        })
        ->whereIn('d.item_id', $itemIds)
        ->sum(DB::raw('COALESCE(NULLIF(r.old_item_quantity, 0), d.old_item_quantity)'));

    // Finished output produced for these items.
    $net += (float) DB::table('operation_order_results as r')
        ->join('operation_order_details as d', 'd.id', '=', 'r.order_details_id')
        ->join('operation_orders as oo', 'oo.id', '=', 'd.operation_order_id')
        ->whereBetween('oo.date', [$from, $to])
        ->when($hasStores, function ($q) use ($storeIds) {
            return $q->whereIn('oo.store_id', $storeIds);
        })
        ->whereIn('d.out_item_id', $itemIds)
        ->sum('r.actual_output');

    // Scrap (خردة) and offcuts (الفضل) both leave the store.
    $net -= (float) DB::table('operation_order_result_details as rd')
        ->join('operation_order_details as d', 'd.id', '=', 'rd.order_details_id')
        ->join('operation_orders as oo', 'oo.id', '=', 'd.operation_order_id')
        ->whereBetween('oo.date', [$from, $to])
        ->when($hasStores, function ($q) use ($storeIds) {
            return $q->whereIn('oo.store_id', $storeIds);
        })
        ->whereIn('d.item_id', $itemIds)
        ->whereIn('rd.damage_type', ['scrap', 'pieces'])
        ->sum('rd.damage_weight');

    return $net;
}

public function item_movements_report(Request $request) {
    // 1. INCREASE MEMORY LIMIT TEMPORARILY
    // This script handles heavy data, so we request more RAM just for this execution.
    ini_set('memory_limit', '512M'); 
    set_time_limit(300); // Increase timeout to 5 minutes

    $this->validate($request, [
        'items' => 'required',
        'date_from' => 'required',
        'date_to' => 'required',
    ]);

    // --- Date Logic ---
    // Use the requested from-date as given. (Previously this silently rewrote any
    // date_from earlier than 2025-12-26 to 2025-12-22, so historical searches
    // returned a date range the user never asked for.)
    $startDateStr = $request->date_from;
    
    $reqStoreIds = $request->stores_id;
    $hasStores = ($request->has('stores_id') && !empty($reqStoreIds));
    $reqItems = $request->items;
    $fromDate = $request->date_from;
    $toDate = $request->date_to;

    // Helper to apply store filter
    $storeFilter = function($q) use ($hasStores, $reqStoreIds) {
        if ($hasStores) $q->whereIn('store_id', $reqStoreIds);
    };

    // =========================================================================
    // STEP 1: FETCH "DATE RANGE" DATA (Fast & Memory Safe)
    // We fetch ONE collection for the date range and split it in PHP.
    // This is much faster than 4 separate queries but safer than fetching "All Time".
    // =========================================================================

    // Optimization: Select only needed columns to save RAM
    $orders_in_range = OrderDetail::with(['order' => function($q) {
            $q->select('id', 'date', 'is_return', 'type', 'ownerable_id', 'ownerable_type');
        }, 'order.ownerable', 'item:id,name']) // Select specific columns
        ->where($storeFilter)
        ->whereIn('item_id', $reqItems)
        ->whereHas('order', function ($q) use ($fromDate, $toDate) {
            // Filter on date2 (DATE), not date (DATETIME): comparing a DATETIME
            // against a bare 'Y-m-d' end bound truncates it to 00:00:00 and drops
            // every movement recorded on the final day.
            $q->whereBetween('date2', [$fromDate, $toDate]);
        })
        ->get();

    // Split in Memory (Fast)
    $orders_in = $orders_in_range->filter(fn($d) => $d->order->is_return == 0 && $d->order->type == 'in');
    $orders_out = $orders_in_range->filter(fn($d) => $d->order->is_return == 0 && $d->order->type == 'out');
    $orders_in_return = $orders_in_range->filter(fn($d) => $d->order->is_return == 1 && $d->order->type == 'out');
    $orders_out_return = $orders_in_range->filter(fn($d) => $d->order->is_return == 1 && $d->order->type == 'in');

    // --- LOADS (Date Range) ---
    $loads_in_range = LoadDetail::with(['parent', 'item:id,name'])
        ->whereIn('item_id', $reqItems)
        ->whereHas('parent', function ($q) use ($fromDate, $toDate, $hasStores, $reqStoreIds) {
            $q->whereBetween('date', [$fromDate, $toDate]);
            if ($hasStores) {
                $q->where(function($sub) use ($reqStoreIds) {
                    $sub->whereIn('from_id', $reqStoreIds)
                        ->orWhereIn('to_id', $reqStoreIds);
                });
            }
        })
        ->get();

    $loads_from = $loads_in_range->filter(function($d) use ($hasStores, $reqStoreIds) {
        if(!$hasStores) return true;
        return in_array($d->parent->from_id, $reqStoreIds);
    });

    $loads_to = $loads_in_range->filter(function($d) use ($hasStores, $reqStoreIds) {
        if(!$hasStores) return true;
        return in_array($d->parent->to_id, $reqStoreIds);
    });


    // NOTE: The six "$coll_*" all-time collections that used to be built here were
    // passed to the view but never rendered by it. Each one loaded the item's entire
    // history (tens of thousands of order_details rows with eager-loaded relations),
    // which is what forced the 512M memory_limit and 300s time limit above.

    // =========================================================================
    // STEP 3: FACTORY DATA (Optimized: No N+1 Loop Queries)
    // =========================================================================

    // 1. Get Operation Order IDs (Raw DB is fastest here)
    $operation_order_ids = DB::table('operation_orders')
        ->whereIn('store_id', $reqStoreIds)
        ->whereBetween('date', [$fromDate, $toDate])
        ->pluck('id')->toArray();

    // 2. Fetch all raw details in bulk
    $to_factory = DB::table('operation_order_details')
        ->whereIn('operation_order_id', $operation_order_ids)
        ->whereIn('item_id', $reqItems)->get();

    // Prepare IDs for output/results
    $out_item_details_ids = DB::table('operation_order_details')
        ->whereIn('operation_order_id', $operation_order_ids)
        ->whereIn('out_item_id', $reqItems)->pluck('id')->toArray();
        
    $from_factory = DB::table('operation_order_results')
        ->whereIn('order_details_id', $out_item_details_ids)->get();

    // Prepare IDs for scrap/pieces
    $in_item_details_ids = DB::table('operation_order_details')
        ->whereIn('operation_order_id', $operation_order_ids)
        ->whereIn('item_id', $reqItems)->pluck('id')->toArray();

    $scrap_factory = DB::table('operation_order_result_details')
        ->whereIn('order_details_id', $in_item_details_ids)
        ->where('damage_type', 'scrap')->get();    

    $pieces_factory = DB::table('operation_order_result_details')
        ->whereIn('order_details_id', $in_item_details_ids)
        ->where('damage_type', 'pieces')->get();

    // Map Results to Old Quantity for the input loop
    $resultsMap = DB::table('operation_order_results')
        ->whereIn('order_details_id', $to_factory->pluck('id')->toArray())
        ->get()->keyBy('order_details_id');


    // =========================================================================
    // STEP 4: CALCULATIONS (Pure Memory, No DB access)
    // =========================================================================

    // Live stock for the selected item(s)/store(s), as it stands right now.
    $liveStock = (float) Quantity::where('ownerable_type', 'App\Models\Store')
        ->whereIn('ownerable_id', $reqStoreIds)
        ->whereIn('item_id', $reqItems)
        ->sum('quantity');

    $rangeMovements = 0;

    // Helper closure to avoid repeating loop code
    $calc = function($collection, $sign) use (&$rangeMovements) {
        foreach($collection as $item) {
            $rangeMovements += (floatval($item->quantity) * $sign);
        }
    };

    // Run standard loops
    $calc($orders_in, -1);
    $calc($orders_out, 1);
    $calc($orders_in_return, 1);
    $calc($orders_out_return, -1);
    $calc($loads_from, -1);
    $calc($loads_to, 1);

    // Factory Loops (Using Maps)
    foreach($to_factory as $item) {
        // Resolve Qty
        $res = $resultsMap[$item->id] ?? null;
        $qty = $res ? (floatval($res->old_item_quantity) ?: floatval($item->old_item_quantity)) : floatval($item->old_item_quantity);
        $rangeMovements -= $qty;
    }

    foreach($from_factory as $item) {
        $rangeMovements += floatval($item->actual_output);
    }

    foreach($scrap_factory as $item) {
        $rangeMovements -= floatval($item->damage_weight);
    }

    foreach($pieces_factory as $item) {
        $rangeMovements -= floatval($item->damage_weight);
    }

    // --- Opening balance (رصيد أول المدة) --------------------------------
    // The true stock the item had at the START of the selected period: take the
    // live stock and unwind everything that moved on/after the from-date.
    // Derived rather than read from a stored snapshot, so it is correct for any
    // date the user picks and self-corrects as stock changes.
    $today = Carbon::now()->format('Y-m-d');
    $movementsSinceStart = $this->netStockMovement($reqItems, $reqStoreIds, $startDateStr, $today);
    $openingBalance = $liveStock - $movementsSinceStart;

    // Closing balance at the end of the selected period (رصيد آخر المدة).
    $balanceAtDateTo = $openingBalance + $rangeMovements;

    // Resources Query
    $resources = Quantity::with('item')
        ->where('ownerable_type', 'App\Models\Store')
        ->whereIn('ownerable_id', $reqStoreIds)
        ->whereIn('item_id', $reqItems)->get();

    $previousDate = Carbon::parse($request->date_from)->subDay()->format('Y-m-d');
    $periodFrom = Carbon::parse($request->date_from)->format('Y-m-d');
    $periodTo = Carbon::parse($request->date_to)->format('Y-m-d');
    $dates = [];
    foreach(CarbonPeriod::create($request->date_from, $request->date_to) as $d) {
        $dates[] = $d->toDateString();
    }

    return view('reports.item_movements_report', compact(
        'orders_in', 'orders_in_return', 'orders_out', 'orders_out_return',
        'loads_from', 'loads_to',
        'to_factory', 'from_factory', 'scrap_factory', 'pieces_factory',
        'resources', 'dates', 'openingBalance', 'previousDate', 'balanceAtDateTo',
        'liveStock', 'rangeMovements', 'periodFrom', 'periodTo'
    ));
}
    // Momaher 
    
    public function getLastBalanceOfReposite(Request $request) {
        
        $this->validate($request, [

            'date_from' => 'required|date',

            'date_to' => 'required|date',

        ]);
        
        $request->date_to = Carbon::parse($request->date_to)->subDays(1);
        
        $request->reposite_id = [$request->reposite_id];

        $report_type = $request->report_type;

        $data = [];

        $orders_in = Order::select('id', 'cost', 'rest', 'ownerable_id', 'ownerable_type')->with('ownerable')//'orderDetails',

//            ->whereHas('orderDetails', function ($q) use ($request) {

//                $q->where('price_pending', 0)

//                    ->where('load_pending', 0);

//            })

                ->where(function ($q) use ($request) {

                    if ($request->has('reposite_id') && $request->reposite_id != null) {

                        $q->whereIn('reposite_id', $request->reposite_id);

                    }

                })

                ->whereBetween('date', [Carbon::parse($request->date_from), Carbon::parse($request->date_to)])

                ->where('is_return', 0)

                ->where('type', 'in')

                ->get();



        $orders_out = Order::select('id', 'cost', 'rest', 'ownerable_id', 'ownerable_type')->with('ownerable')//'orderDetails',

            ->where(function ($q) use ($request) {

                if ($request->has('reposite_id') && $request->reposite_id != null) {

                    $q->whereIn('reposite_id', $request->reposite_id);

                }

            })

//            ->whereHas('orderDetails', function ($q) use ($request) {

//                $q->where('price_pending', 0)

//                    ->where('load_pending', 0);

//            })

                ->whereBetween('date', [Carbon::parse($request->date_from), Carbon::parse($request->date_to)])

                ->where('is_return', 0)

                ->where('type', 'out')

                ->get();



        $pay_in = Account::with('accountable')

                ->where('pending', 0)

                ->where('type', 'in')

                ->where('order_id', '=', null)

                ->whereBetween('date', [Carbon::parse($request->date_from), Carbon::parse($request->date_to)])

                ->where(function ($q) use ($request) {

                    if ($request->has('reposite_id') && $request->reposite_id != []) {

                        $q->whereIn('reposite_id', $request->reposite_id);

                    }

                })

                ->get();

        $pay_out = Account::with('accountable')

                ->where('pending', 0)

                ->where('type', 'out')

                ->where('order_id', '=', null)

                ->whereBetween('date', [Carbon::parse($request->date_from), Carbon::parse($request->date_to)])

                ->where(function ($q) use ($request) {

                    if ($request->has('reposite_id') && $request->reposite_id != []) {

                        $q->whereIn('reposite_id', $request->reposite_id);

                    }

                })

                ->get();

        $salaries = Salary::whereIn('reposite_id', $request->reposite_id)

                ->whereBetween('date', [Carbon::parse($request->date_from), Carbon::parse($request->date_to)])

                ->get();

        $loans = Loan::whereIn('reposite_id', $request->reposite_id)

                ->whereBetween('date', [Carbon::parse($request->date_from), Carbon::parse($request->date_to)])

                ->get();

        $dialies_out = Daily::whereIn('reposite_id', $request->reposite_id)

                ->where('dailies.type', 'out')

                ->whereBetween('date', [Carbon::parse($request->date_from), Carbon::parse($request->date_to)])->

                leftJoin('trees', 'trees.id', '=', 'dailies.tree_id')

                ->get();

//        return $dialies_out;

        $dialies_in = Daily::whereIn('reposite_id', $request->reposite_id)

                ->where('dailies.type', 'in')

                ->whereBetween('date', [Carbon::parse($request->date_from), Carbon::parse($request->date_to)])->

                leftJoin('trees', 'trees.id', '=', 'dailies.tree_id')

                ->get();

        $transactions_from = Transaction::with('from', 'to')->whereIn('from_id', $request->reposite_id)

                ->whereBetween('date', [Carbon::parse($request->date_from), Carbon::parse($request->date_to)])

                ->get();

//        return $transactions_from;

        $transactions_to = Transaction::with('from', 'to')->whereIn('to_id', $request->reposite_id)

                ->whereBetween('date', [Carbon::parse($request->date_from), Carbon::parse($request->date_to)])

                ->get();

        $resource = Reposite::whereIn('id', $request->reposite_id)->first();

//        return $dialies;
          
        $balance = ($resource->balance - ($orders_in->sum('cost') + $pay_in->sum('cost')+ $dialies_in->sum('cost')+$transactions_to->sum('cost') - $orders_out->sum('cost') - $pay_out->sum('cost') - $dialies_out->sum('cost') - $transactions_from->sum('cost'))) + $orders_in->sum('cost') + $pay_in->sum('cost')+ $dialies_in->sum('cost')+$transactions_to->sum('cost') - $orders_out->sum('cost') - $pay_out->sum('cost') - $dialies_out->sum('cost') - $transactions_from->sum('cost');
    
    //dd($request->date_to);
        return $balance;
    }

    public function safe_report(Request $request) {

        $this->validate($request, [

            'date_from' => 'required|date',

            'date_to' => 'required|date',

        ]);
        $request->reposite_id = [$request->reposite_id];

        $report_type = $request->report_type;

        $data = [];

        $orders_in = Order::select('id', 'cost', 'rest', 'ownerable_id', 'ownerable_type')->with('ownerable')//'orderDetails',

//            ->whereHas('orderDetails', function ($q) use ($request) {

//                $q->where('price_pending', 0)

//                    ->where('load_pending', 0);

//            })

                ->where(function ($q) use ($request) {

                    if ($request->has('reposite_id') && $request->reposite_id != null) {

                        $q->whereIn('reposite_id', $request->reposite_id);

                    }

                })

                ->whereBetween('date2', [Carbon::parse($request->date_from), Carbon::parse($request->date_to)])

                ->where('is_return', 0)

                ->where('type', 'in')

                ->get();



        $orders_out = Order::select('id', 'cost', 'rest', 'ownerable_id', 'ownerable_type')->with('ownerable')//'orderDetails',

            ->where(function ($q) use ($request) {

                if ($request->has('reposite_id') && $request->reposite_id != null) {

                    $q->whereIn('reposite_id', $request->reposite_id);

                }

            })

//            ->whereHas('orderDetails', function ($q) use ($request) {

//                $q->where('price_pending', 0)

//                    ->where('load_pending', 0);

//            })

                ->whereBetween('date2', [Carbon::parse($request->date_from), Carbon::parse($request->date_to)])

                ->where('is_return', 0)

                ->where('type', 'out')

                ->get();



        $pay_in = Account::with('accountable')

                ->where('pending', 0)

                ->where('type', 'in')

                ->where('order_id', '=', null)

                ->whereBetween('date', [Carbon::parse($request->date_from), Carbon::parse($request->date_to)])

                ->where(function ($q) use ($request) {

                    if ($request->has('reposite_id') && $request->reposite_id != []) {

                        $q->whereIn('reposite_id', $request->reposite_id);

                    }

                })

                ->get();

        $pay_out = Account::with('accountable')

                ->where('pending', 0)

                ->where('type', 'out')

                ->where('order_id', '=', null)

                ->whereBetween('date', [Carbon::parse($request->date_from), Carbon::parse($request->date_to)])

                ->where(function ($q) use ($request) {

                    if ($request->has('reposite_id') && $request->reposite_id != []) {

                        $q->whereIn('reposite_id', $request->reposite_id);

                    }

                })

                ->get();

        $salaries = Salary::whereIn('reposite_id', $request->reposite_id)

                ->whereBetween('created_at', [Carbon::parse($request->date_from), Carbon::parse($request->date_to)->addDays(1)])

                ->get();

        $loans = Loan::whereIn('reposite_id', $request->reposite_id)

                ->whereBetween('date', [Carbon::parse($request->date_from), Carbon::parse($request->date_to)])

                ->get();

        $dialies_out = Daily::whereIn('reposite_id', $request->reposite_id)

                ->where('dailies.type', 'out')

                ->whereBetween('date', [Carbon::parse($request->date_from), Carbon::parse($request->date_to)])->

                leftJoin('trees', 'trees.id', '=', 'dailies.tree_id')

                ->get();

//        return $dialies_out;

        $dialies_in = Daily::whereIn('reposite_id', $request->reposite_id)

                ->where('dailies.type', 'in')

                ->whereBetween('date', [Carbon::parse($request->date_from), Carbon::parse($request->date_to)])->

                leftJoin('trees', 'trees.id', '=', 'dailies.tree_id')

                ->get();

        $transactions_from = Transaction::with('from', 'to')->whereIn('from_id', $request->reposite_id)

                ->whereBetween('date', [Carbon::parse($request->date_from), Carbon::parse($request->date_to)])

                ->get();

//        return $transactions_from;

        $transactions_to = Transaction::with('from', 'to')->whereIn('to_id', $request->reposite_id)

                ->whereBetween('date', [Carbon::parse($request->date_from), Carbon::parse($request->date_to)])

                ->get();

        $resource = Reposite::whereIn('id', $request->reposite_id)->first();

//        return $dialies;
         

        $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($request->date_to)));
        $lastBalance = DB::table('reposite_balances')->where('date', $yesterday)->where('reposite_id', $request->reposite_id)->latest()->first();

        $todayBalance =   optional(DB::table('reposite_balances')->where('date', $request->date_to)->where('reposite_id', $request->reposite_id)->latest()->first())->balance;
        
       // return dd();
        //return dd($yesterday);
         $lastBalance =   optional($lastBalance)->balance ?? 0; // Ensure it's numeric
    //   return  dd($todayBalance, $lastBalance,$transactions_from, $transactions_to,$orders_in,$pay_in,$pay_out);

        return view('reports.safe_report', compact('todayBalance', 'lastBalance', 'loans', 'salaries', 'dialies_in', 'dialies_out', 'transactions_from', 'transactions_to', 'data', 'orders_in', 'orders_out', 'pay_in', 'pay_out', 'report_type', 'resource'));

    }



    public function transaction_report(Request $request) {

        $this->validate($request, [

            'date_from' => 'required',

            'date_to' => 'required'

        ]);

        $resources = Transaction::with('from', 'to')->where(function ($q) use ($request) {

                            if ($request->has('from') && $request->from != null && $request->from != '') {

                                $q->where('from_id', $request->from);

                            }

                            if ($request->has('to') && $request->to != null && $request->to != '') {

                                $q->where('to_id', $request->to);

                            }

                        })

                        ->whereBetween('date', [$request->date_from, $request->date_to])->get();

//        return $resources;



        return view('reports.transaction_report', compact('resources'));

    }



    public function employee_report(Request $request) {

//        return 'here';

        $this->validate($request, [

            'user_id' => 'required|numeric',

            'date_from' => 'required',

            'date_to' => 'required',

        ]);

        $user = User::findOrFail($request->user_id);

//        return $user;

        $dialies = Daily::where('user_id', $request->user_id)

                ->whereBetween('date', [$request->date_from, $request->date_to])

                ->get();

        $orders_in = Order::where('user_id', $request->user_id)

                        ->whereBetween('created_at', [$request->date_from, $request->date_to])

                        ->where('type', 'in')

                        ->orderBy('created_at', 'DESC')->get();

        $orders_out = Order::where('user_id', $request->user_id)

                        ->whereBetween('created_at', [$request->date_from, $request->date_to])

                        ->where('type', 'out')

                        ->orderBy('created_at', 'DESC')->get();

        $orders_in_return = Order::where('user_id', $request->user_id)

                        ->whereBetween('created_at', [$request->date_from, $request->date_to])

                        ->where('is_return', true)->where('type', 'out')

                        ->orderBy('created_at', 'DESC')->get();

        $orders_out_return = Order::where('user_id', $request->user_id)

                        ->whereBetween('created_at', [$request->date_from, $request->date_to])

                        ->where('is_return', true)->where('type', 'in')

                        ->orderBy('created_at', 'DESC')->get();

        $transactions = Transaction::where('user_id', $request->user_id)->whereBetween('created_at', [$request->date_from, $request->date_to])->get();

        $loads = Load::where('user_id', $request->user_id)->whereBetween('created_at', [$request->date_from, $request->date_to])->get();

        $accounts = Account::where('user_id', $request->user_id)->whereBetween('created_at', [Carbon::parse($request->date_from), Carbon::parse($request->date_to)->addDay(1)])->get();



        return view('reports.employee_report', compact('user', 'dialies', 'orders_in', 'orders_in_return', 'orders_out', 'orders_out_return', 'transactions', 'loads', 'accounts'));

    }
    
// Mo.Maher
        function inventory_report(Request $request) {

        // return $request->all();

        $resources = Item::with(['group', 'quantities' => function ($q) use ($request) {

                        if ($request->has('stores') && $request->stores != '' && $request->stores != null) {

                            $q->whereIn('ownerable_id', $request->stores);

                        }

                        $q->where('ownerable_type', 'App\Models\Store');

                    }])->whereHas('quantities', function ($q) use ($request) {
                        if ($request->has('stores') && $request->stores != '' && $request->stores != null) {
                            $q->whereIn('ownerable_id', $request->stores);
                        }
                        $q->where('ownerable_type', 'App\Models\Store');
                        if ($request->has('group_id') && (int) $request->group_id === 63 || (int) $request->group_id === 62) {
                            $q->where('quantity', '>', 0);
                        }
                    })

                ->where(function ($q) use ($request) {

                    if ($request->has('group_id') && $request->group_id != '' && $request->group_id != null) {

                        $q->where('group_id', $request->group_id);

                    }

                    if ($request->has('items') && $request->items != '' && $request->items != null) {

                        $q->whereIn('id', $request->items);

                    }

                })
                ->where("active", 1)

                ->get();
                // dd($resources);

        $stores = Store::whereIn('id', $request->stores)->get();

        // $quantities = Quantity::where('ownerable_type', 'App\Models\Store')
        //     ->where('quantity', '<', 1)->where('quantity', '>', 0)
        //     ->update(['quantity' => 0]);
        return view('reports.inventory_report', compact('resources', 'stores'));
    }
// Mo.Maher
    
    //     function inventory_report(Request $request) {

    //     // return $request->all();

    //     $resources = Item::with(['group', 'quantities' => function ($q) use ($request) {

    //                     if ($request->has('stores') && $request->stores != '' && $request->stores != null) {

    //                         $q->whereIn('ownerable_id', $request->stores);

    //                     }

    //                     $q->where('ownerable_type', 'App\Models\Store');

    //                 }])->whereHas('quantities')

    //             ->where(function ($q) use ($request) {

    //                 if ($request->has('group_id') && $request->group_id != '' && $request->group_id != null) {

    //                     $q->where('group_id', $request->group_id);

    //                 }

    //                 if ($request->has('items') && $request->items != '' && $request->items != null) {

    //                     $q->whereIn('id', $request->items);

    //                 }

    //             })
    //             ->where("active", 1)

    //             ->get();
    //             // dd($resources);

    //     $stores = Store::whereIn('id', $request->stores)->get();

    //     // $quantities = Quantity::where('ownerable_type', 'App\Models\Store')
    //     //     ->where('quantity', '<', 1)->where('quantity', '>', 0)
    //     //     ->update(['quantity' => 0]);
            
    //     return view('reports.inventory_report', compact('resources', 'stores'));
    // }

// function inventory_report(Request $request)
//     {

//         // return $request->all();

//         $resources = Item::with(['group', 'quantities' => function ($q) use ($request) {

//             if ($request->has('stores') && $request->stores != '' && $request->stores != null) {

//                 $q->whereIn('ownerable_id', $request->stores);
//             }

//             $q->where('ownerable_type', 'App\Models\Store');
//         }])
//             ->whereHas('quantities')

//             ->where(function ($q) use ($request) {

//                 if ($request->has('group_id') && $request->group_id != '' && $request->group_id != null) {

//                     $q->where('group_id', $request->group_id);
//                 }

//                 if ($request->has('items') && $request->items != '' && $request->items != null) {

//                     $q->whereIn('id', $request->items);
//                 }
//             })
//             ->where("active", 1)
//             ->with(['quantities' => function ($q) {
//                 $q->where('quantity', '!=', 0);
//             }])->whereHas('quantities', function ($q) {
//                 $q->where('quantity', '!=', 0);
//             })
//             ->get();

//         $stores = Store::whereIn('id', $request->stores)->get();


//         return view('reports.inventory_report', compact('resources', 'stores'));
//     }



    
    function inventory_report_case(Request $request) {

        // return $request->all();
        
        $date_from = $request->date_from;

        $resources = Item::with(['group', 'quantities' => function ($q) use ($request) {

                        if ($request->has('stores') && $request->stores != '' && $request->stores != null) {

                            $q->whereIn('ownerable_id', $request->stores);

                        }

                        $q->where('ownerable_type', 'App\Models\Store');

                    }])->whereHas('quantities')

                ->where(function ($q) use ($request) {

                    if ($request->has('group_id') && $request->group_id != '' && $request->group_id != null) {

                        $q->where('group_id', $request->group_id);

                    }

                    if ($request->has('items') && $request->items != '' && $request->items != null) {

                        $q->whereIn('id', $request->items);

                    }

                })
                ->where("active", 1)

                ->get();
                // dd($resources);

        $stores = Store::whereIn('id', $request->stores)->get();

//        return $resources;

        return view('reports.inventory_report_case', compact('resources', 'stores','date_from'));

    }
    
    
    public function migrateQuantities()
    {
        ini_set('max_execution_time', 0);
        set_time_limit(0);

        $quantities = Quantity::where('ownerable_type', 'like', '%Store%')->get();
        $count = 0;

        foreach ($quantities as $quantity) {
            DB::table('new_quantities')->updateOrInsert(
                [
                    'branch_id' => $quantity->ownerable_id,
                    'item_id' => $quantity->item_id,
                ],
                [
                    'quantity' => $quantity->quantity,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
            $count++;
        }

        return "Data migrated successfully. " . $count . " records processed.";
    }



}

