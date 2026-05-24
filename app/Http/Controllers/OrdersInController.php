<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\OrderDetail;
use App\Models\Quantity;
use Illuminate\Http\Request;

use App\Models\Client;
use App\Models\Store;
use App\Models\QuantityDailies;
use App\Models\Order;
use App\Models\Group;
use App\Models\Supplier;
use App\Models\Reposite;
use App\Models\Mandator;

use App\DataTables\OrdersInDataTable;

use App\DataTables\OrderDetailsDataTable;
use App\Models\Employee;
use App\Models\Job;
use App\Models\Setting;
use App\Models\Transport;
use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Pusher\Pusher;

class OrdersInController extends Controller
{
    protected $util;
    protected $dateNow;
    protected $timeNow;

    public function __construct(Util $util)
    {
        $this->util = $util;
        $this->dateNow = Carbon::now()->format('Y-m-d');
        $this->timeNow = Carbon::now()->format('H:i:s');
    }
    
    // public function index()
    // {
    //     $query = Order::with('orderDetails', 'ownerable', 'reposite', 'user')
    //         ->where('type', 'in')
    //         ->orderBy('created_at', 'DESC');
            
    //     if (request()->id > 0)
    //         $query->where('id', request()->id);
            
    //     if (request()->ids)
    //         $query->whereIn('id', request()->ids);
           

    //     $resources = $query->paginate(10);

    //     return view('orders-in.index', compact('resources'));
    // }
    
    public function index() {
        $query = Order::with('orderDetails', 'ownerable', 'reposite', 'user')
            ->where('type', 'in')
            ->orderBy('id', 'DESC');
            
        if(request()->id > 0)
            $query->where('id', request()->id);
            
        if(request()->ids)
            $query->whereIn('id', request()->ids);
            
        if(request()->search) 
            $query->where('id', request()->search);
            
        if(request()->status) {
            $statuses = [
                2 => 'pending',
                3 => 'refused'
            ];
        
            if(isset($statuses[request()->status])) {
                $query->join('order_details', 'orders.id', '=', 'order_details.order_id')
                      ->where('order_details.status', $statuses[request()->status])
                      ->distinct()
                      ->select('orders.*');
            }
            
            if(request()->status == 3) {
                $query->where('date', '>=', '2024-07-18');
            }
        }
        
        if(auth()->user()->hasRole('store_respons') || auth()->user()->hasRole('store_factor_response'))
            $query->where('branch_id', auth()->user()->branch_id);

        if(request()->status) {
            $resources = $query->get();
        } else {
            $resources = $query->paginate(10);
        }
    
        return view('orders-in.index', compact('resources'));
    }

    public function create()
    {
        $groups = Group::whereHas('items', function ($q) {
            $q->whereHas('quantities', function ($query) {
                $query
                // $query->where('quantity', '>=', '1')
                    ->where('ownerable_type', 'App\Models\Store');
            });
        })->get();
        $clientCount = Client::count();
        $supplierCount = Supplier::count();
        $buyersCount = $clientCount + $supplierCount;
        $mandators = Mandator::all();
        $clients = Client::get();
        $storesCount = Store::whereHas('quantities', function ($query) {
            $query->where('quantity', '>', '0');
        })
            ->select('id', 'name')
            ->count();

        // $reposites = Reposite::all();

        if (auth()->user()->id != 1) {
            $reposites = Reposite::where(function ($query) {
                $query->where('branch_id', auth()->user()->branch_id)
                ->orWhere('mainly', 1);
                })->orderby('mainly')->latest()->get();
        }else{
            $reposites = Reposite::all();
        }
        
        $jobDriver = Job::where('name', 'سائق')->first();
        if($jobDriver){
            $drivers = Employee::where('job_id', $jobDriver->id)->latest()->get();
        }else{
            $drivers = Employee::latest()->get();
        }
        // dd($drivers);
        return view('orders-in.newCreate', compact('clients','reposites', 'mandators', 'drivers', 'supplierCount', 'clientCount', 'storesCount', 'groups', 'buyersCount'));
    }

    // public function store(Request $request)
    // {
    //     // dd($request->all());
    //     $validator = validator()->make($request->all(), [
    //         'date' => 'required',
    //         'ownerable_id' => 'required|numeric|min:1',
    //         'cost' => 'required',
    //         'quantity.*' => 'required|numeric',
    //         'price.*' => 'required|numeric',
    //         'item_discount.*' => 'nullable|numeric',
    //         'store_id.*' => 'required',
    //     ], [
    //         'date.required' => 'من فضلط اختر التاريخ',
    //         'ownerable_id.required' => 'من فضلك اختر المشتري ',
    //         'ownerable_id.numeric' => 'من فضلك اختر المشتري ',
    //         'ownerable_id.min:1' => 'من فضلك اختر المشتري ',
    //         'cost.required' => 'من فضللك ادخل المبلغ المدفوع',
    //         'quantity.required' => 'من فضلك اختر اصناف ',
    //         'quantity.numeric' => 'من فضلك ادخل كميات صحيحة',
    //         'price.numeric' => 'من فضلك ادخل اسعار صحيحة',
    //         'price.required' => 'من فضلك اختر اصناف ',
    //         'store_id.required' => 'من فضلك اختر لمخزن',
    //     ]);

    //     if ($validator->fails()) {
    //         flash($validator->errors()->first())->error();
    //         return back();
    //     }

    //     DB::beginTransaction();
    //     $order = Order::create([
    //         'type' => 'in',
    //         'date' => date("Y-m-d H:i:s"),
    //         'created_at' => date("Y-m-d H:i:s"),
    //         'ownerable_id' => $request->ownerable_id,
    //         'ownerable_type' => 'App\Models\\' . $request->ownerable_type,
    //         'total' => $request->total,
    //         'vat' => $request->vat,
    //         'discount' => $request->discount,
    //         'final_total' => $request->final_total,
    //         'rest' => $request->rest,
    //         'cost' => $request->cost,
    //         'notes' => $request->notes,
    //         'user_id' => auth()->user()->id,
    //         'branch_id' => auth()->user()->branch_id,
    //         'reposite_id' => $request->reposite_id,
    //         'mandator_id' => $request->mandator_id
    //     ]);

    //     if(!$request->mandator_id && auth()->user()->id !=1){
    //         $mandator = Mandator::where('branch_id', auth()->user()->branch_id)->where('store_id', auth()->user()->store_id)->first();
    //         if($mandator){
    //             $order->mandator_id = $mandator->id;
    //             $order->save();
    //         }
    //     }

    //     if($request->driver_id){
    //         $order->driver_id = $request->driver_id;
    //         $order->save();

    //         $transport_percent = Setting::first() ? Setting::first()->transport_percent : 0 ;
    //         $transport = Transport::create([
    //             'employee_id'   => $request->driver_id,
    //             'user_id'   => auth()->user()->id,
    //             'branch_id'   => auth()->user()->branch_id,
    //             'order_id'   => $order->id,
    //             'date'   => date('Y-m-d'),
    //             'type'   => 'in',
    //             'cost'   => $request->driving_cost,
    //             'rate'   => ($request->driving_cost * $transport_percent)/100,
    //             'percent'   => $transport_percent
    //         ]);
    //     }

    //     if($request->driving_cost){
    //         $order->driving_cost = $request->driving_cost;
    //         $order->save();
    //     }

    //     $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'orders-in', $order->id, $this->dateNow, $this->timeNow, null, null );

    //     $counter = 0;

    //     $reposite = Reposite::where('id', $request->reposite_id)->first();
    //     if ($reposite && Auth()->user()->id != 1) {
    //         $this->push_notification(['user_id' => $reposite->user_id,'url'=>url('pending-pays')]);
    //     }
    //     $user_store = Store::where('user_id', Auth()->user()->id)->first();
    //     foreach ($request->group_id as $item) {
    //         $load_pending = 1;
    //         $price_pending = 0;
    //         if ($user_store) {
    //             if ($user_store->id == $request->store_id[$counter]) {
    //                 $load_pending = 0;
    //             }
    //         }
    //         if (Auth()->user()->id == 1) {
    //             $load_pending = 0;
    //         }
    //         $store = Store::where('id', $request->store_id[$counter])->first();
    //         if ($store && Auth()->user()->id != 1) {
    //             $this->push_notification(['user_id' => $store->user_id,'url'=>url('pending-load')]);
    //         }
    //         $quantity = Quantity::where('item_id', $request->item_id[$counter])
    //             ->where('ownerable_id', $request->store_id[$counter])
    //             ->where('ownerable_type', 'App\Models\Store')->first();
    //         if ($quantity) {
    //             if ($quantity->quantity < $request->quantity[$counter]) {
    //                 flash('الكمية المطلوبة أكبرمن المتاحة في المخزن')->error();
    //                 return back();
    //             }
    //         }
    //         $order_detail = OrderDetail::create([
    //             'order_id' => $order->id,
    //             'item_id' => $request->item_id[$counter],
    //             'discount' => $request->item_discount[$counter],
    //             'quantity' => $request->quantity[$counter],
    //             'unite_price' => $request->price[$counter],
    //             'store_id' => $request->store_id[$counter],
    //             'load_pending' => $load_pending,
    //             'price_pending' => $price_pending,
    //         ]);
    //         if ($load_pending == 0) {
    //             $quantity->decrement('quantity', $request->quantity[$counter]);
    //             $order_detail->update(['load_pending' => 0]);
    //         }
    //         QuantityDailies::create([
    //             'ownerable_id'=>$request->store_id[$counter],
    //             'ownerable_type'=>'App\Models\Store',
    //             'item_id'=>$request->item_id[$counter],
    //             'quantity'=>$request->quantity[$counter],
    //             'type' => '1',
    //             'type_order'     => '1',
    //             'order_id' => $order_detail->id,
    //         ]);
    //         $counter++;
    //     }

    //     if ($request->cost > 0 ) {
    //         $pending_account=1;
    //         if (Auth()->user()->id == 1){
    //             $pending_account=0;
    //         }
    //         $account = Account::create([
    //             'type' => 'in',
    //             'order_id' => $order->id,
    //             'cost' => $order->cost,
    //             'date' => $order->date,
    //             'reposite_id' => $order->reposite_id,
    //             'accountable_id' => $request->ownerable_id,
    //             'accountable_type' => 'App\Models\\' . $request->ownerable_type,
    //             'pending' => $pending_account,
    //         ]);

    //         $reposite = Reposite::where('id', $request->reposite_id)->first();
    //         if ($reposite) {
                
    //             if (Auth()->user()->id == 1) {
    //                 $reposite->increment('balance', $request->cost);
    //             }
    //             if (Auth()->user()->id != 1) {
    //                 $this->push_notification(['user_id' => $reposite->user_id,'url'=>url('pending-pays')]);
    //             }
    //         }
    //         if ($request->ownerable_type == 'Client') {
    //             $customer = Client::where('id', $request->ownerable_id)->first();
    //         } else {
    //             $customer = Supplier::where('id', $request->ownerable_id)->first();
    //         }
    //         if ($customer) {
    //             if (Auth()->user()->id == 1){
    //                 $customer->increment('balance', $request->rest);
    //             }else{
    //                 $customer->increment('balance', $request->final_total);
    //             }
    //         }
    //     }
    //     if ($request->cost <= 0) {
    //         if ($request->ownerable_type == 'Client') {
    //             $customer = Client::where('id', $request->ownerable_id)->first();
    //             if ($customer) {
    //                 $customer->increment('balance', $request->rest);
    //             }
    //         } else {
    //             $customer = Supplier::where('id', $request->ownerable_id)->first();
    //             if ($customer) {
    //                 $customer->decrement('balance', $request->rest);
    //             }
    //         }

    //     }

    //     DB::commit();
    //     flash('تمت العمليه بنجاح')->success();
    //     return redirect()->route('orders-in.index');
    // }
    
    public function store(Request $request) {
        $validator = validator()->make($request->all(), [
            'date' => 'required',
            'ownerable_id' => 'required|numeric|min:1',
            'cost' => 'required',
            'quantity.*' => 'required|numeric',
            'price.*' => 'required|numeric',
            'item_discount.*' => 'nullable|numeric',
            'store_id.*' => 'required',
        ], [
            'date.required' => 'من فضلط اختر التاريخ',
            'ownerable_id.required' => 'من فضلك اختر المشتري ',
            'ownerable_id.numeric' => 'من فضلك اختر المشتري ',
            'ownerable_id.min:1' => 'من فضلك اختر المشتري ',
            'cost.required' => 'من فضللك ادخل المبلغ المدفوع',
            'quantity.required' => 'من فضلك اختر اصناف ',
            'quantity.numeric' => 'من فضلك ادخل كميات صحيحة',
            'price.numeric' => 'من فضلك ادخل اسعار صحيحة',
            'price.required' => 'من فضلك اختر اصناف ',
            'store_id.required' => 'من فضلك اختر لمخزن',
        ]);

        if($validator->fails()) {
            flash($validator->errors()->first())->error();
            return back();
        }

        DB::beginTransaction();
        $order = Order::create([
            'type' => 'in',
            'date' => date("Y-m-d H:i:s"),
            'created_at' => date("Y-m-d H:i:s"),
            'ownerable_id' => $request->ownerable_id,
            'ownerable_type' => 'App\Models\\' . $request->ownerable_type,
            'total' => $request->total,
            'vat' => $request->vat,
            'discount' => $request->discount,
            'final_total' => $request->final_total,
            'rest' => $request->rest,
            'cost' => $request->cost,
            'notes' => $request->notes,
            'user_id' => auth()->user()->id,
            'branch_id' => auth()->user()->branch_id,
            'reposite_id' => $request->reposite_id,
            'mandator_id' => $request->mandator_id,
            'role' => 'in',
        ]);

        if(!$request->mandator_id && auth()->user()->id !=1){
            $mandator = Mandator::where('branch_id', auth()->user()->branch_id)->where('store_id', auth()->user()->store_id)->first();
            if($mandator){
                $order->mandator_id = $mandator->id;
                $order->save();
            }
        }

        if($request->driver_id){
            $order->driver_id = $request->driver_id;
            $order->save();

            $transport_percent = Setting::first() ? Setting::first()->transport_percent : 0 ;
            $transport = Transport::create([
                'employee_id'   => $request->driver_id,
                'user_id'   => auth()->user()->id,
                'branch_id'   => auth()->user()->branch_id,
                'order_id'   => $order->id,
                'date'   => date('Y-m-d'),
                'type'   => 'in',
                'cost'   => $request->driving_cost,
                'rate'   => ($request->driving_cost * $transport_percent)/100,
                'percent'   => $transport_percent
            ]);
        }

        if($request->driving_cost){
            $order->driving_cost = $request->driving_cost;
            $order->save();
        }

        $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'orders-in', $order->id, $this->dateNow, $this->timeNow, null, null );

        $reposite = Reposite::where('id', $request->reposite_id)->first();
        if($reposite && Auth()->user()->id != 1) {
            $this->push_notification(['user_id' => $reposite->user_id,'url'=>url('pending-pays')]);
        }
        
        $counter = 0;
        $user_store = Store::where('user_id', Auth()->user()->id)->first();
        foreach($request->group_id as $item) {
            $load_pending = 1;
            $price_pending = 0;

            if($user_store) {
                if($user_store->id == $request->store_id[$counter]) {
                    $load_pending = 0;
                }
            }

            if(Auth()->user()->id == 1) {
                $load_pending = 0;
            }

            $store = Store::where('id', $request->store_id[$counter])->first();
            if($store && Auth()->user()->id != 1) {
                $this->push_notification(['user_id' => $store->user_id,'url'=>url('pending-load')]);
            }

            $quantity = Quantity::where('item_id', $request->item_id[$counter])
                ->where('ownerable_id', $request->store_id[$counter])
                ->where('ownerable_type', 'App\Models\Store')->first();

            if($quantity) {
                if ($quantity->quantity < $request->quantity[$counter]) {
                    flash('الكمية المطلوبة أكبرمن المتاحة في المخزن')->error();
                    return back();
                }
            }

            $order_detail = OrderDetail::create([
                'order_id' => $order->id,
                'item_id' => $request->item_id[$counter],
                'discount' => $request->item_discount[$counter],
                'quantity' => $request->quantity[$counter],
                'unite_price' => $request->price[$counter],
                'store_id' => $request->store_id[$counter],
                'load_pending' => $load_pending,
                'price_pending' => $price_pending,
            ]);

            // if($load_pending == 0) {
            //     $quantity->decrement('quantity', $request->quantity[$counter]);
            //     $order_detail->update(['load_pending' => 0]);
            // }
            
            // QuantityDailies::create([
            //     'ownerable_id'=>$request->store_id[$counter],
            //     'ownerable_type'=>'App\Models\Store',
            //     'item_id'=>$request->item_id[$counter],
            //     'quantity'=>$request->quantity[$counter],
            //     'type' => '1',
            //     'type_order'     => '1',
            //     'order_id' => $order_detail->id,
            // ]);
            $counter++;
        }

        if($request->cost > 0 ) {
            $pending_account = 1;

            if(Auth()->user()->id == 1){
                $pending_account=0;
            }

            $account = Account::create([
                'type' => 'in',
                'order_id' => $order->id,
                'cost' => $order->cost,
                'date' => $order->date,
                'reposite_id' => $order->reposite_id,
                'accountable_id' => $request->ownerable_id,
                'accountable_type' => 'App\Models\\' . $request->ownerable_type,
                'pending' => $pending_account,
            ]);

            $reposite = Reposite::where('id', $request->reposite_id)->first();
            if($reposite) {
                if(Auth()->user()->id == 1) {
                    $reposite->increment('balance', $request->cost);
                }
                if(Auth()->user()->id != 1) {
                    $this->push_notification(['user_id' => $reposite->user_id,'url'=>url('pending-pays')]);
                }
            }

            if($request->ownerable_type == 'Client') {
                $customer = Client::where('id', $request->ownerable_id)->first();
            } else {
                $customer = Supplier::where('id', $request->ownerable_id)->first();
            }

            if($customer) {
                if(Auth()->user()->id == 1){
                    $customer->increment('balance', $request->rest);
                } else {
                    $customer->increment('balance', $request->final_total);
                }
            }
        }

        if($request->cost <= 0) {
            if($request->ownerable_type == 'Client') {
                $customer = Client::where('id', $request->ownerable_id)->first();
                if($customer) {
                    $customer->increment('balance', $request->rest);
                }
            } else {
                $customer = Supplier::where('id', $request->ownerable_id)->first();
                if ($customer) {
                    $customer->decrement('balance', $request->rest);
                }
            }

        }

        DB::commit();
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('orders-in.index');
    }

    public function show($id)
    {
        $type = 'invoice';
        $resource = Order::with('orderDetails', 'ownerable', 'reposite')->findOrFail($id);
//return $resource ;
        return view('orders-in.show', compact('resource', 'type'));
    }

    public function print_license($id)
    {
        $type = 'license';
        $resource = Order::with('orderDetails', 'ownerable', 'reposite')->findOrFail($id);
        return view('orders-in.show', compact('resource', 'type'));
    }

    public function print_store_license($id)
    {
        $type = 'store';
        $resource = Order::with('orderDetails', 'ownerable', 'reposite')->findOrFail($id);
        return view('orders-in.storeShow', compact('resource', 'type'));
    }
    
    public function edit($id)
    {
        $jobDriver = Job::where('name', 'سائق')->first();
        if($jobDriver){
            $drivers = Employee::where('job_id', $jobDriver->id)->latest()->get();
        }else{
            $drivers = Employee::latest()->get();
        }
        $resource = Order::with('orderDetails', 'ownerable')->where('type', 'in')->where('id', $id)->first();
        return view('orders-in.edit', compact('resource', 'drivers'));
    }

    // public function update(Request $request, $id)
    // {
    //     $oldData = Order::where('type', 'in')->where('id', $id)->first();

    //     $resource = Order::where('type', 'in')->where('id', $id)->first();
    //     $resource->update(['mandator_id' => $request->mandator_id]);
    //     if ($resource->ownerable_type == 'App\Models\Client') {
    //         $customer = Client::where('id', $resource->ownerable_id)->first();
    //     } else {
    //         $customer = Supplier::where('id', $resource->ownerable_id)->first();
    //     }

    //     $counter = 0;
    //     $total = 0;
    //     DB::beginTransaction();
    //     if (!empty($request->detail_id)) {
    //         foreach ($request->detail_id as $item) {
    //             $detail = OrderDetail::where('id', $request->detail_id[$counter])->first();
    //             if ($detail) {
    //                 $quantity = Quantity::where('item_id', $detail->item_id)
    //                     ->where('ownerable_id', $detail->store_id)
    //                     ->where('ownerable_type', 'App\Models\Store')->first();
    //                 if ($quantity) {
    //                     $quantity->increment('quantity', $detail->quantity);
    //                     if ($quantity->quantity < $request->quantity[$counter]) {
    //                         flash('الكمية المطلوبة أكبرمن المتاحة في المخزن')->error();
    //                         return back();
    //                     }
    //                     $detail->update([
    //                         'quantity' => $request->quantity[$counter],
    //                         'unite_price' => $request->price[$counter],
    //                         'discount' => $request->discount[$counter],
    //                     ]);
    //                     $quantity->decrement('quantity', $request->quantity[$counter]);
    //                     $total = $total + ($request->quantity[$counter] * $request->price[$counter]) - $request->discount[$counter];
    //                 }
    //             }
    //             $counter++;
    //         }
    //     }
    //     $total = $total + ($request->driving_cost);

    //     $customer->decrement('balance', $resource->final_total);
    //     $customer->increment('balance', $total - $resource->discount);
    //     $resource->update([
    //         'total' => $total,
    //         'driving_cost' => $request->driving_cost,
    //         'final_total' => $total - $resource->discount,
    //         'notes' => $request->resource_note
    //     ]);

    //     $newData = $request->all();
    //     $properties = [
    //         'old_data' => $oldData,
    //         'new_data' => $newData
    //     ];

    //     $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'orders-in', $resource->id, $this->dateNow, $this->timeNow, $properties, null );

    //     DB::commit();
    //     flash('تم الحفظ بنجاح')->success();
    //     return back();
    // }
    
    public function update(Request $request, $id) {
        $oldData = Order::where('type', 'in')->where('id', $id)->first();
        $resource = Order::where('type', 'in')->where('id', $id)->first();

        // $resource->update(['mandator_id' => $request->mandator_id]);
        
        if($resource->ownerable_type == 'App\Models\Client') {
            $customer = Client::where('id', $resource->ownerable_id)->first();
        } else {
            $customer = Supplier::where('id', $resource->ownerable_id)->first();
        }

        $total = 0;
        $counter = 0;
        DB::beginTransaction();
        if(!empty($request->detail_id)) {
            foreach($request->detail_id as $item) {
                $detail = OrderDetail::where('id', $request->detail_id[$counter])->first();
                if($detail) {
                    $quantity = Quantity::where('item_id', $detail->item_id)
                        ->where('ownerable_id', $detail->store_id)
                        ->where('ownerable_type', 'App\Models\Store')->first();

                    if($quantity) {
                        // $quantity->increment('quantity', $detail->quantity);
                        // if($quantity->quantity < $request->quantity[$counter]) {
                        //     flash('الكمية المطلوبة أكبر من المتاحة في المخزن')->error();
                        //     return back();
                        // }

                        $detail->update([
                            'quantity' => $request->quantity[$counter],
                            'unite_price' => $request->price[$counter],
                            'discount' => $request->discount[$counter],
                        ]);
                        if($detail->status != 'accepted') {
                            $detail->update(['status' => 'pending']);
                        }
                        // $quantity->decrement('quantity', $request->quantity[$counter]);
                        $total = $total + ($request->quantity[$counter] * $request->price[$counter]) - $request->discount[$counter];
                    }
                }
                $counter++;
            }

            $total = $total + ($request->driving_cost);

            $customer->decrement('balance', $resource->final_total);
            $customer->increment('balance', $total - $resource->discount);

            $resource->update([
                'total' => $total,
                'driving_cost' => $request->driving_cost,
                'final_total' => $total - $resource->discount,
            ]);

            // $resource->status = 'pending';
            $resource->save();
        }
            
        // newUpdate
        if($request->driver_id) {
            $transport = Transport::where('order_id', $resource->id)->first();
            
            if(!$transport) {
                $resource->driver_id = $request->driver_id;
                $resource->save();

                $transport_percent = Setting::first() ? Setting::first()->transport_percent : 0 ;
                $transport = Transport::create([
                    'employee_id' => $request->driver_id,
                    'user_id'     => auth()->user()->id,
                    'branch_id'   => auth()->user()->branch_id,
                    'order_id'    => $resource->id,
                    'date'        => date('Y-m-d'),
                    'type'        => 'in',
                    'cost'        => $request->driving_cost,
                    'rate'        => ($request->driving_cost * $transport_percent)/100,
                    'percent'     => $transport_percent
                ]);
            } else {
                $resource->driver_id = $request->driver_id;
                $resource->save();

                Transport::where('order_id', $resource->id)->delete();

                $transport_percent = Setting::first() ? Setting::first()->transport_percent : 0 ;
                $transport = Transport::create([
                    'employee_id' => $request->driver_id,
                    'user_id'     => auth()->user()->id,
                    'branch_id'   => auth()->user()->branch_id,
                    'order_id'    => $resource->id,
                    'date'        => date('Y-m-d'),
                    'type'        => 'in',
                    'cost'        => $request->driving_cost,
                    'rate'        => ($request->driving_cost * $transport_percent)/100,
                    'percent'     => $transport_percent
                ]);
            }
        }

        // newUpdate
        $itemsCounter = 0;
        if(!empty($request->selected_items)) {
            foreach($request->selected_items as $item) {
                $detail = OrderDetail::where('id', $request->selected_items[$itemsCounter])->first();
                $resource = Order::where('id', $detail->order_id)->first();

                if($resource->ownerable_type == 'App\Models\Client') {
                    $customer = Client::where('id', $resource->ownerable_id)->first();
                } else {
                    $customer = Supplier::where('id', $resource->ownerable_id)->first();
                }
        
                $total = 0;
                if($detail) {
                    $quantity = Quantity::where('item_id', $detail->item_id)
                        ->where('ownerable_id', $detail->store_id)
                        ->where('ownerable_type', 'App\Models\Store')->first();
        
                    if($quantity) {
                        // $quantity->increment('quantity', $detail->quantity);
                        $total = $resource->final_total - ($detail->quantity * $detail->unite_price) - $detail->discount;
                    }
                }
        
                $customer->decrement('balance', $resource->final_total);
                $customer->increment('balance', $total - $resource->discount);
        
                $resource->update([
                    'total' => $total,
                    'final_total' => $total - $resource->discount
                ]);
        
                $detail->delete();

                $itemsCounter++;
            }
        }

        $resource->notes = $request->resource_note;
        $resource->save();
        
        $pendingRefuedOrders = OrderDetail::where('order_id', $resource->id)
            ->whereIn('status', ['pending', 'refused'])
            ->exists();

        if($pendingRefuedOrders) {
            $resource->status = 'pending';
            $resource->save();
        } else {
            $resource->status = 'checked';
            $resource->save();
        }

        $newData = $request->all();
        $properties = [
            'old_data' => $oldData,
            'new_data' => $newData
        ];

        $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'orders-in', $resource->id, $this->dateNow, $this->timeNow, $properties, null );
        if($pendingRefuedOrders) {
            $this->push_notification_update(['user_id' => $resource->receiver_id, 'url' => url('orders-in'), 'message' => 'تم اجراء تعديل فى فاتورة رقم ' . $resource->id]);
        }
        DB::commit();
        flash('تم الحفظ بنجاح')->success();
        return redirect()->route('orders-in.index');
    }

    public function destroy($id)
    {
        $record = Order::where('id', $id)->first();
        if ($record) {
            if ($record->ownerable_type == 'App\Models\Client') {
                $customer = Client::where('id', $record->ownerable_id)->first();
            } else {
                $customer = Supplier::where('id', $record->ownerable_id)->first();
            }
            if ($customer) {
                $account = Account::where('order_id', $id)->first();
                if ($account) {
                    $account->delete();
                }
                $customer->decrement('balance', $record->final_total);
            }

            $reposite = Reposite::where('id', $record->reposite_id)->first();
            if ($reposite) {
                $reposite->decrement('balance', $record->cost);               
            }

            if($record->driver_id > 0 && $record->driving_cost >0 ){
                $transport_percent = Setting::first() ? Setting::first()->transport_percent : 0 ;
                
                $lastTransport = Transport::where('employee_id', $record->driver_id)
                                 ->where('order_id', $id)->latest()->first();

                $lastTransport->decrement('cost', $record->driving_cost);
                $lastTransport->save();
                $lastTransCost = $lastTransport->cost;
                $lastTransport->rate = ($lastTransCost * $transport_percent)/100;
                $lastTransport->save();
                $lastTransport->delete();
            }
        }
        $details = OrderDetail::where('order_id', $record->id)->get();
        foreach ($details as $detail) {
            if ($detail->load_pending == 0) {
                $quantity = Quantity::where('item_id', $detail->item_id)
                    ->where('ownerable_id', $detail->store_id)
                    ->where('ownerable_type', 'App\Models\Store')->first();
                if ($quantity) {
                    $quantity->increment('quantity', $detail->quantity);
                }
            }
            $detail->delete();
        }
        
        $oldData = Order::where('id', $id)->first();
        $properties = [
            'old_data' => $oldData,
        ];
        $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'orders-in', $record->id, $this->dateNow, $this->timeNow, $properties, null );

        $record->delete();
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('orders-in.index');
    }

    // public function order_delete_item($id)
    // {

    //     $detail = OrderDetail::where('id', $id)->first();
    //     $resource = Order::where('id', $detail->order_id)->first();
    //     if ($resource->ownerable_type == 'App\Models\Client') {
    //         $customer = Client::where('id', $resource->ownerable_id)->first();
    //     } else {
    //         $customer = Supplier::where('id', $resource->ownerable_id)->first();
    //     }
    //     $total = 0;
    //     DB::beginTransaction();
    //     if ($detail) {
    //         $quantity = Quantity::where('item_id', $detail->item_id)
    //             ->where('ownerable_id', $detail->store_id)
    //             ->where('ownerable_type', 'App\Models\Store')->first();
    //         if ($quantity) {
    //             $quantity->increment('quantity', $detail->quantity);
    //             $total = $resource->final_total - ($detail->quantity * $detail->unite_price) - $detail->discount;
    //         }
    //     }
    //     $customer->decrement('balance', $resource->final_total);
    //     $customer->increment('balance', $total - $resource->discount);
    //     $resource->update([
    //         'total' => $total,
    //         'final_total' => $total - $resource->discount
    //     ]);
    //     $detail->delete();
    //     DB::commit();
    //     flash()->success('تم الحذف بنجاح');
    //     return back();
    // }
    
    public function order_delete_item($id) {
        $detail = OrderDetail::where('id', $id)->first();
        $resource = Order::where('id', $detail->order_id)->first();
        
        if($resource->ownerable_type == 'App\Models\Client') {
            $customer = Client::where('id', $resource->ownerable_id)->first();
        } else {
            $customer = Supplier::where('id', $resource->ownerable_id)->first();
        }

        $total = 0;
        DB::beginTransaction();
        if($detail) {
            $quantity = Quantity::where('item_id', $detail->item_id)
                ->where('ownerable_id', $detail->store_id)
                ->where('ownerable_type', 'App\Models\Store')->first();

            if($quantity) {
                // $quantity->increment('quantity', $detail->quantity);
                $total = $resource->final_total - ($detail->quantity * $detail->unite_price) - $detail->discount;
            }
        }

        $customer->decrement('balance', $resource->final_total);
        $customer->increment('balance', $total - $resource->discount);

        $resource->update([
            'total' => $total,
            'final_total' => $total - $resource->discount
        ]);

        $detail->delete();
        DB::commit();

        flash()->success('تم الحذف بنجاح');
        return back();
    }

    public function push_notification($message) {
        $options = array(
            'cluster' => 'eu',
            'useTLS' => true
        );
        $pusher = new Pusher(
            'e75d58425f4b10f93cfb',
            '49edd2fdb43527c84354',
            '417914',
            $options
        );
        $data['message'] = $message;
        $pusher->trigger('my-channel', 'my-event', $data);
        return true;
    }
    
    public function push_notification_update($message) {
        $options = array(
            'cluster' => 'eu',
            'useTLS' => true
        );
        $pusher = new Pusher(
            'e75d58425f4b10f93cfb',
            '49edd2fdb43527c84354',
            '417914',
            $options
        );
        $data['message'] = $message;
        $pusher->trigger('my-channel2', 'my-event2', $data);
        return true;
    }
}
