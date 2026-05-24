<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Account;
use App\Models\Order;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use DB;

class ClientController extends Controller {

    private $balance = 0;

    function getLastDateOfZeroBalance(Request $request) {
        $clients = Client::select('name', 'id')->latest()->get();
        $request->from = "2018-01-01";
        $request->to = date("Y-m-d");
        $orders_out = [];
        $orders_in = [];
        $pay_in = [];
        $pay_out = [];
        $returns = [];
        $client = '';
        $from = '';
        $to = '';
        $client_accounts = [];
        $s_balance = 0;
        $zeroDate = "";

        $time1 = time();
        $times = 0;
        $days = 0;
        $c = null;
        $dbZeroDate = null;
        $start = true;
        
        $balances = [];
 
        
        try {
            $c = Client::find($request->client_id);
            $dbZeroDate = $c->zeroDate;
        } catch (\Exception $ex) {
            
        }
    // && ($dbZeroDate != "0000-00-00")
        if ($dbZeroDate != null && !$request->has('renew')) {
            $zeroDate = $dbZeroDate;  
            return $dbZeroDate;
        }


        if ($request->from != null && $request->to != null && $request->client_id != null) {

            $from = $request->from;
            $to = $request->to;
            $client = Client::where('id', $request->client_id)->first();
            $s_balance = $client->init;
            $days = Carbon::parse($request->from)->diffInDays(Carbon::parse($request->to));
            for ($i = 0; $i <= $days; $i++) {
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

                $pin = Account::with('reposite')->where('type', 'in')
                        ->whereDate('date', Carbon::parse($request->from)->addDays($i))
                        ->where('pending', false)
                        ->where('accountable_id', $request->client_id)
                        ->where('accountable_type', 'App\Models\Client')
                        ->get();
                $pout = Account::with('reposite')->where('accounts.type', 'out')
                        ->whereDate('date', Carbon::parse($request->from)->addDays($i))
                        ->where('pending', false)
                        ->where('accountable_id', $request->client_id)
                        ->where('accountable_type', 'App\Models\Client')
                        ->get();
                if ($pout->sum('cost') > 0 || $pin->sum('cost') || $in->sum('final_total') > 0 || $order_return->sum('final_total') > 0 || $out->sum('final_total') > 0) {
//                    $s_balance= $s_balance + ( $out->sum('final_total') - $in->sum('final_total') ) + ($pin->sum('cost') - $pout->sum('cost'))  ;
                    $s_balance = $s_balance + ( $in->sum('final_total') - $out->sum('final_total') - $order_return->sum('final_total') - ($pin->sum('cost') ) + $pout->sum('cost'));
                    
                    $s_balance = round($s_balance);
                     
                    if (($s_balance) == 0) {
                        $times = $i;
                        $zeroDate = Carbon::parse($request->from)->addDays($i)->format('Y-m-d');
                    }
                }
            }
        }

        try {
            DB::statement(" update clients set zeroDate='$zeroDate' where id='$request->client_id' ");
        } catch (\Exception $exc) {
            
        }

        return $zeroDate;
    }

    public function dateBalanceZeroApi(Request $request) {
        echo "";//$this->getLastDateOfZeroBalance($request);
    }
    
    public function getStartDateOfDaily($client_id) { 
        $startDailyDate = "";
        try{
            $date = Client::join("orders", "clients.id", "=", "ownerable_id")
                ->join("accounts", "clients.id", "=", 'accountable_id')
                ->where('accounts.accountable_type', 'App\Models\Client')
                ->where('orders.ownerable_type', 'App\Models\Client')
                ->where("clients.id", $client_id)
                ->first(["orders.date as d1", "accounts.date as d2"]); 
        
            $startDailyDate = min(strtotime($date->d1), strtotime($date->d2));
            
            return date("Y-m-d", $startDailyDate);
        }catch(\Exception $e){
            // start date of the system
            return "2018-07-01";
        }
    }
    
    public function getLastBalance(Request $request) {
        $clients = Client::select('name', 'id')->latest()->get();
        $orders_out = [];
        $orders_in = [];
        $pay_in = [];
        $pay_out = [];
        $returns = [];
        $client = '';
        $from = '';
        $to = '';
        $client_accounts = [];
        $s_balance = 0;
        $rclone = new Request(); 
        $r = "";
        

      
        if ($request->from != null && $request->to != null && $request->client_id != null) {
            $from = $request->from;
            $to = $request->to;
            $client = Client::where('id', $request->client_id)->first();
 
            $s_balance = $client->init;  

            if (strtotime($request->to) <= strtotime($request->from))
                return (float)$s_balance;
                 
                $out = Order::where('type', 'out')
                                ->where('is_return', false)
                                ->whereBetween('date2', [Carbon::parse($request->from), Carbon::parse($request->to)])
                                ->where([
                                    'ownerable_type' => 'App\Models\Client',
                                    'ownerable_id' => $request->client_id,
                                ])->get();
 
                $order_return = Order::where('type', 'out')
                                ->where('is_return', true)
                                ->whereBetween('date2', [Carbon::parse($request->from), Carbon::parse($request->to)])
                                ->where([
                                    'ownerable_type' => 'App\Models\Client',
                                    'ownerable_id' => $request->client_id,
                                ])->get();


                $in = Order::where('type', 'in')
                        ->where('is_return', false)
                        ->whereBetween('date2', [Carbon::parse($request->from), Carbon::parse($request->to)])
                        ->where([
                            'ownerable_type' => 'App\Models\Client',
                            'ownerable_id' => $request->client_id,
                        ])
                        ->get();

                 

                $pin = Account::with('reposite')->where('type', 'in')
                        ->whereBetween('date', [Carbon::parse($request->from), Carbon::parse($request->to)])
                        ->where('pending', false)
                        ->where('accountable_id', $request->client_id)
                        ->where('accountable_type', 'App\Models\Client')
                        ->get();
                        


                $pout = Account::with('reposite')->where('accounts.type', 'out')
                        ->whereBetween('date', [Carbon::parse($request->from), Carbon::parse($request->to)])
                        ->where('pending', false)
                        ->where('accountable_id', $request->client_id)
                        ->where('accountable_type', 'App\Models\Client')
                        ->get();
                // dd($pout->sum('cost'));

                    if ($pout->sum('cost') > 0 || $pin->sum('cost') || $in->sum('final_total') > 0 || $order_return->sum('final_total') > 0 || $out->sum('final_total') > 0) {
//                    $s_balance= $s_balance + ( $out->sum('final_total') - $in->sum('final_total') ) + ($pin->sum('cost') - $pout->sum('cost'));
                    //$s_balance = number_format($s_balance, 2) + 0;
                    $s_balance = $s_balance + ( $in->sum('final_total') - $out->sum('final_total') - $order_return->sum('final_total') - ($pin->sum('cost') ) + $pout->sum('cost'));
 
                } 
                
            
        }
        
        // dd($s_balance);
        
        return $s_balance;
    }

    public function index(Request $request) {
        $clients = Client::select('name', 'id')->latest()->get();
        $orders_out = [];
        $orders_in = [];
        $pay_in = [];
        $pay_out = [];
        $returns = [];
        $client = '';
        $from = '';
        $to = '';
        $client_accounts = [];
        $s_balance = 0;
        $rclone = new Request(); 
        //$afterDateOfZero = date('Y-m-d', strtotime($this->getLastDateOfZeroBalance($rclone) . ' + 1 days'));


        if ($request->has('from') && $request->from != null && $request->has('to') && $request->to != null && $request->has('client_id') && $request->client_id != null) {
            $from = $request->from;
            $to = $request->to;


            $client = Client::where('id', $request->client_id)->first();
            $startDate = $this->getStartDateOfDaily($request->client_id); 
                         
            $rclone->from = $startDate;
             

            $rclone->to =  date('Y-m-d',(strtotime ( '-1 day' , strtotime ($request->from) ) ));// date("Y-m-d", $request->from);
            $rclone->client_id = $request->client_id;
            $s_balance = $this->getLastBalance($rclone);
            // dd($s_balance);
             
            
              
            for ($i = 0; $i <= Carbon::parse($request->from)->diffInDays(Carbon::parse($request->to)); $i++) {
                $out = Order::where('type', 'out')
                                ->where('is_return', false)
                                ->whereDate('date2', Carbon::parse($request->from)->addDays($i))
                                /* ->whereHas('orderDetails', function ($query) {
                                  $query->where('load_pending', false)->orWhere('price_pending', false);
                                  }) */
                                ->where([
                                    'ownerable_type' => 'App\Models\Client',
                                    'ownerable_id' => $request->client_id,
                                ])->get();

                if (count($out) > 0) {
                    foreach ($out as $item) {
                        array_push($orders_out, $item);
                    }
                }

                $order_return = Order::where('type', 'out')
                                ->where('is_return', true)
                                ->whereDate('date2', Carbon::parse($request->from)->addDays($i))
                                // ->whereHas('orderDetails', function ($query) {
                                //     $query->where('load_pending', false)->orWhere('price_pending', false);
                                // })
                                ->where([
                                    'ownerable_type' => 'App\Models\Client',
                                    'ownerable_id' => $request->client_id,
                                ])->get();

                if (count($order_return) > 0) {
                    foreach ($order_return as $item) {
                        array_push($returns, $item);
                    }
                }
                
               

                $in = Order::where('type', 'in')
                        ->where('is_return', false)
                        ->whereDate('date2', Carbon::parse($request->from)->addDays($i))
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
                if (count($in) > 0) {
                    foreach ($in as $item) {
                        array_push($orders_in, $item);
                    }
                }

                $pin = Account::with('reposite')->where('type', 'in')
                        ->whereDate('date', Carbon::parse($request->from)->addDays($i))
                        ->where('pending', false)
                        ->where('accountable_id', $request->client_id)
                        ->where('accountable_type', 'App\Models\Client')
                        ->get();
                if (count($pin) > 0) {
                    foreach ($pin as $item) {
                        array_push($pay_in, $item);
                    }
                }
                $pout = Account::with('reposite')->where('accounts.type', 'out')
                        ->whereDate('date', Carbon::parse($request->from)->addDays($i))
                        ->where('pending', false)
                        ->where('accountable_id', $request->client_id)
                        ->where('accountable_type', 'App\Models\Client')
                        ->get();
                if (count($pout) > 0) {
                    foreach ($pout as $item) {
                        array_push($pay_out, $item);
                    }
                }
                if ($pout->sum('cost') > 0 || $pin->sum('cost') || $in->sum('final_total') > 0 || $order_return->sum('final_total') > 0 || $out->sum('final_total') > 0) {
//                    $s_balance= $s_balance + ( $out->sum('final_total') - $in->sum('final_total') ) + ($pin->sum('cost') - $pout->sum('cost'));
                    //$s_balance = number_format($s_balance, 2) + 0;
                    $s_balance = $s_balance + ( $in->sum('final_total') - $out->sum('final_total') - $order_return->sum('final_total') - ($pin->sum('cost') ) + $pout->sum('cost'));

                    array_push($client_accounts, [
                        'date' => Carbon::parse($request->from)->addDays($i)->format('Y-m-d'),
                        'order_out' => $out->sum('final_total'),
                        'order_in' => $in->sum('final_total'),
                        'return' => $order_return->sum('final_total'),
                        'pay_in' => $pin->sum('cost'),
                        'pay_out' => $pout->sum('cost'),
                        'balance' => $s_balance//number_format((float)$s_balance, 2)//round($s_balance, 2)//
                    ]);
                }
            }
        }
        return view('reports.client', compact('from', 'to', 'client', 'clients', 'orders_in', 'orders_out', 'pay_in', 'pay_out', 'returns', 'client_accounts'));
    }

    public function getClientBalance(Request $request) { 
        $orders_out = [];
        $orders_in = [];
        $pay_in = [];
        $pay_out = [];
        $returns = [];
        $client = '';
        $from = '';
        $to = '';
        $client_accounts = [];
        $s_balance = 0;
        $rclone = new Request(); 
        //$afterDateOfZero = date('Y-m-d', strtotime($this->getLastDateOfZeroBalance($rclone) . ' + 1 days'));


        if ($request->has('from') && $request->from != null && $request->has('to') && $request->to != null && $request->has('client_id') && $request->client_id != null) {
            $from = $request->from;
            $to = $request->to;
            $client = Client::where('id', $request->client_id)->first();
 
            $startDate = $this->getStartDateOfDaily($request->client_id); 
              
            $rclone->from = $startDate;
            
            $rclone->to =  date('Y-m-d',(strtotime ( '-1 day' , strtotime ($request->from) ) ));// date("Y-m-d", $request->from);
            $rclone->client_id = $request->client_id;
            $s_balance = $this->getLastBalance($rclone);
             
              
            for ($i = 0; $i <= Carbon::parse($request->from)->diffInDays(Carbon::parse($request->to)); $i++) {
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

                if (count($out) > 0) {
                    foreach ($out as $item) {
                        array_push($orders_out, $item);
                    }
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

                if (count($order_return) > 0) {
                    foreach ($order_return as $item) {
                        array_push($returns, $item);
                    }
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
                if (count($in) > 0) {
                    foreach ($in as $item) {
                        array_push($orders_in, $item);
                    }
                }

                $pin = Account::with('reposite')->where('type', 'in')
                        ->whereDate('date', Carbon::parse($request->from)->addDays($i))
                        ->where('pending', false)
                        ->where('accountable_id', $request->client_id)
                        ->where('accountable_type', 'App\Models\Client')
                        ->get();
                if (count($pin) > 0) {
                    foreach ($pin as $item) {
                        array_push($pay_in, $item);
                    }
                }
                $pout = Account::with('reposite')->where('accounts.type', 'out')
                        ->whereDate('date', Carbon::parse($request->from)->addDays($i))
                        ->where('pending', false)
                        ->where('accountable_id', $request->client_id)
                        ->where('accountable_type', 'App\Models\Client')
                        ->get();
                if (count($pout) > 0) {
                    foreach ($pout as $item) {
                        array_push($pay_out, $item);
                    }
                }
                if ($pout->sum('cost') > 0 || $pin->sum('cost') || $in->sum('final_total') > 0 || $order_return->sum('final_total') > 0 || $out->sum('final_total') > 0) {
//                    $s_balance= $s_balance + ( $out->sum('final_total') - $in->sum('final_total') ) + ($pin->sum('cost') - $pout->sum('cost'));
                    //$s_balance = number_format($s_balance, 2) + 0;
                    $s_balance = $s_balance + ( $in->sum('final_total') - $out->sum('final_total') - $order_return->sum('final_total') - ($pin->sum('cost') ) + $pout->sum('cost'));
                    
                    $index = strtotime(Carbon::parse($request->from)->addDays($i)) . '';
                    $client_accounts[$index] = [
                        'date' => Carbon::parse($request->from)->addDays($i)->format('Y-m-d'),
                        'order_out' => $out->sum('final_total'),
                        'order_in' => $in->sum('final_total'),
                        'return' => $order_return->sum('final_total'),
                        'pay_in' => $pin->sum('cost'),
                        'pay_out' => $pout->sum('cost'),
                        'balance' => $s_balance//number_format((float)$s_balance, 2)//round($s_balance, 2)//
                    ];
                    
                }
            }
        }
        
        return [
           "from" => $from,
           "to" => $to,
           "client" => $client, 
           "orders_in" => $orders_in,
           "orders_out" => $orders_out,
           "pay_in" => $pay_in,
           "pay_out" => $pay_out,
           "returns" => $returns,
           "client_accounts" => $client_accounts  
        ];
        //return view('reports.client', compact('from', 'to', 'client', 'clients', 'orders_in', 'orders_out', 'pay_in', 'pay_out', 'returns', 'client_accounts'));
    }

    public function ordersIn(Request $request) {
        $query = Order::
                select('orders.id', 'orders.created_at', 'date', 'final_total', 'clients.name')
                ->join('clients', function ($join) {
                    $join->on('clients.id', '=', 'orders.ownerable_id')
                    ->where('orders.ownerable_type', 'App\Models\Client');
                })
                ->where('type', 'in')
                ->whereBetween('date', [
                    $request->from,
                    $request->to
                ])
                ->whereHas('orderDetails', function ($query) {
                    $query
                    ->where('load_pending', false)
                    ->orWhere('price_pending', false);
                })
                ->where([
                    'ownerable_type' => 'App\Models\Client',
                    'ownerable_id' => $request->client_id,
                ])
                ->latest();

        return Datatables::of($query)
                        ->editColumn('date', function (Order $order) {
                            return optional($order->date)->toDateString();
                        })
                        ->addColumn('rest', function (Order $order) {
                            return $order->max;
                        })
                        ->addColumn('payed', function (Order $order) {
                            return $order->payed;
                        })
                        ->make(true);
    }

    public function ordersOut(Request $request) {

        $query = Order::
                select('orders.id', 'orders.created_at', 'date', 'final_total', 'clients.name')
                ->join('clients', function ($join) {
                    $join->on('clients.id', '=', 'orders.ownerable_id')
                    ->where('orders.ownerable_type', 'App\Models\Client');
                })
                ->where([
                    'ownerable_type' => 'App\Models\Client',
                    'ownerable_id' => $request->client_id,
                ])
                ->whereHas('orderDetails', function ($query) {
                    $query
                    ->where('load_pending', false)
                    ->orWhere('price_pending', false);
                })
                ->where('type', 'out')
                ->whereBetween('date', [
                    $request->from,
                    $request->to
                ])
                ->latest();


        return Datatables::of($query)
                        ->editColumn('date', function (Order $order) {
                            return optional($order->date)->toDateString();
                        })
                        ->addColumn('rest', function (Order $order) {
                            return $order->max;
                        })
                        ->addColumn('payed', function (Order $order) {
                            return $order->payed;
                        })
                        ->make(true);
    }

    public function accountsIn(Request $request) {
        $query = Account::
                select('accounts.id', 'accounts.created_at', 'accounts.date', 'reposites.name', 'accounts.cost', 'orders.id as order', 'clients.name as client')
                ->join('clients', function ($join) {
                    $join->on('clients.id', '=', 'accounts.accountable_id')
                    ->where('accounts.accountable_type', 'App\Models\Client');
                })
                ->where('accounts.type', 'in')
                ->whereBetween('accounts.date', [
                    $request->from,
                    $request->to
                ])
                ->where('accountable_id', $request->client_id)
                ->where('pending', false)
                ->leftJoin('orders', 'orders.id', '=', 'accounts.order_id')
                ->leftJoin('reposites', 'reposites.id', '=', 'accounts.reposite_id')
                ->latest();

        return Datatables::of($query)
                        ->editColumn('date', function (Account $account) {
                            return optional($account->date)->toDateString();
                        })
                        ->make(true);
    }

    public function accountsOut(Request $request) {
        $query = Account::select('accounts.id', 'accounts.created_at', 'accounts.date', 'reposites.name', 'accounts.cost', 'orders.id as order', 'clients.name as client')
                ->join('clients', function ($join) {
                    $join->on('clients.id', '=', 'accounts.accountable_id')
                    ->where('accounts.accountable_type', 'App\Models\Client');
                })
                ->where('accounts.type', 'out')
                ->whereBetween('accounts.date', [
                    $request->from,
                    $request->to
                ])
                ->where('pending', false)
                ->where('accountable_id', $request->client_id)
                ->leftJoin('orders', 'orders.id', '=', 'accounts.order_id')
                ->leftJoin('reposites', 'reposites.id', '=', 'accounts.reposite_id')
                ->latest();
        return Datatables::of($query)
                        ->editColumn('date', function (Account $account) {
                            return optional($account->date)->toDateString();
                        })
                        ->make(true);
    }

    public function account(Request $request) {

        $collect = collect();
        $client = null;
        if ($request->client_id) {
            $from = Carbon::parse($request->from);
            $to = Carbon::parse($request->to);
            $client = Client::find($request->client_id);
            $balance = $client->init;
            for ($date = $from; $date->lte($to); $date->addDay()) {
                $d = $date->toDateString();
                $orderIn = $client->orders()
                        ->where('type', 'in')
                        ->whereDate('date', $d)
                        ->whereHas('orderDetails', function ($query) {
                            $query
                            ->where('load_pending', false)
                            ->orWhere('price_pending', false);
                        })
                        ->sum('final_total');
                $orderOut = $client->orders()
                                ->where('type', 'out')
                                ->whereHas('orderDetails', function ($query) {
                                    $query
                                    ->where('load_pending', false)
                                    ->orWhere('price_pending', false);
                                })
                                ->whereDate('date', $d)->sum('final_total');


                $cost = $client->accounts()
                        ->where('type', 'in')
                        ->where('pending', false)
                        ->whereDate('date', $d)
                        ->sum('cost');

                $balance = $balance + ($orderIn - ($orderOut + $cost));
//if($orderIn - ($orderOut + $cost) ){
                if ($orderIn || $orderOut || $cost) {
                    $collect->push([
                        'date' => $d,
                        'order_in' => $orderIn,
                        'order_out' => $orderOut,
                        'cost' => $cost,
                        'balance' => $balance,
                    ]);
                }
            }
            $this->balance = $collect->last()['balance'];
        }
        return Datatables::of($collect)
                        ->with(['balance' => $this->balance, 'client' => $client])
                        ->make(true);
    }

    public function client_account_report(Request $request) {
        $this->validate($request, [
            'client_id' => 'required',
            'from' => 'required',
            'to' => 'required',
        ]);
        $client = Client::where('id', $request->client_id)->first();
        $orders_in = Order::where('ownerable_type', 'App\Models\Client')
                ->where('ownerable_id', $request->client_id)
                ->where('type', 'in')
                ->whereHas('orderDetails', function ($query) {
                    $query
                    ->where('load_pending', false)
                    ->orWhere('price_pending', false);
                })
                ->whereDate('date', '>=', Carbon::parse($request->from))
                ->whereDate('date', '<=', Carbon::parse($request->to))
                ->orderBy('date', 'DESC')
                ->get();
        $orders_out = Order::where('ownerable_type', 'App\Models\Client')
                ->where('ownerable_id', $request->client_id)
                ->where('type', 'out')
                ->whereHas('orderDetails', function ($query) {
                    $query
                    ->where('load_pending', false)
                    ->orWhere('price_pending', false);
                })
                ->whereDate('date', '>=', Carbon::parse($request->from))
                ->whereDate('date', '<=', Carbon::parse($request->to))
                ->orderBy('date', 'DESC')
                ->get();
        $accounts_in = Account::where('accountable_type', 'App\Models\Client')
                ->where('accountable_id', $request->client_id)
                ->where('type', 'in')
                ->where('pending', false)
                ->orderBy('date', 'DESC')
                ->get();
        $accounts_out = Account::where('accountable_type', 'App\Models\Client')
                ->where('accountable_id', $request->client_id)
                ->where('type', 'out')
                ->where('pending', false)
                ->orderBy('date', 'DESC')
                ->get();
        $dates = ['from' => $request->from, 'to' => $request->to];

        return view('reports.client.index', compact('client', 'orders_out', 'orders_in', 'accounts_in', 'accounts_out', 'dates'));
    }

}
