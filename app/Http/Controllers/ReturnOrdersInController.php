<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\OrderDetail;
use App\Models\Quantity;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Client;
use App\Models\Supplier;
use App\Models\Group;
use App\Models\Store;
use App\Models\QuantityDailies;
use App\Models\Reposite;
use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Pusher\Pusher;
use App\Models\Job;
use App\Models\Employee;
use App\Models\Setting;
use App\Models\Transport;


class ReturnOrdersInController extends Controller
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
    //     $query = Order::with('orderDetails', 'ownerable', 'reposite', 'user')->where('is_return',true)->where('type', 'out');
        
    //     if (request()->search) {
    //         $query
    //         ->where('date', 'like', '%'. request()->search .'%')
    //         ->orWhere('id', 'like', '%'. request()->search .'%');
    //     }
        
    //     $resources = $query->orderBy('date', 'DESC')->paginate(10);
        
        
    //     return view('return-orders-in.index', compact('resources'));

    // }
    
    public function index() {
        $query = Order::with('orderDetails', 'ownerable', 'reposite', 'user')->where('is_return',true)->where('type', 'out');
        
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
            $resources = $query->orderBy('created_at', 'DESC')->get();
        } else {
            $resources = $query->orderBy('created_at', 'DESC')->paginate(10);
        }
        
        return view('return-orders-in.index', compact('resources'));
    }

    public function create(Order $order)
    {
        $clientCount = Client::whereHas('quantities', function ($query) {
            $query->where('quantity', '>', '0');
        })->count();
        $supplierCount = Supplier::whereHas('quantities', function ($query) {
            $query->where('quantity', '>', '0');
        })->count();

        $groups = Group::whereHas('items')->get();
        $clients = Client::get();

        $buyersCount = $clientCount + $supplierCount;
        $stores = Store::select('id', 'name')->get();
        if (auth()->user()->id != 1) {
            $stores = Store::where(function ($query) {
                $query->where('user_id', auth()->user()->id);
                })->get();
        }else{
            $stores = Store::all();
        }

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

        return view('return-orders-in.create', compact('clientCount',
            'supplierCount', 'groups',
            'buyersCount', 'reposites',
            'stores','clients','drivers'
        ));
    }

//     public function store(Request $request)
//     {
// // return $request->all();
//         $validator = validator()->make($request->all(), [
//             'date' => 'required',
//             'ownerable_id' => 'required|numeric',
//             'cost' => 'required',
//             'quantity.*' => 'required|numeric',
//             'price.*' => 'required|numeric',
//             'store_id' => 'required',
//         ], [
//             'date.required' => 'من فضلط اختر التاريخ',
//             'ownerable_id.required' => 'من فضلك اختر المشتري ',
//             'ownerable_id.numeric' => 'من فضلك اختر المشتري ',
//             'cost.required' => 'من فضللك ادخل المبلغ المدفوع',
//             'quantity.required' => '',
//             'store_id.required' => 'من فضلك اختر لمخزن',
//         ]);
//         if ($validator->fails()) {
//             flash($validator->errors()->first())->error();
//             return back();
//         }
//         DB::beginTransaction();
//         $order = Order::create([
//             'mandator_id' => $request->mandator_id,
//             'is_return' => true,
//             'type' => 'out',
//             'date' => $request->date,
//             'ownerable_id' => $request->ownerable_id,
//             'ownerable_type' => 'App\Models\\' . $request->ownerable_type,
//             'total' => $request->total,
//             'vat' => $request->vat,
//             'discount' => $request->discount,
//             'final_total' => $request->final_total,
//             'rest' => $request->rest,
//             'cost' => $request->cost,
//             'notes' => $request->notes,
//             'user_id' => auth()->user()->id,
//             'branch_id' => auth()->user()->branch_id,
//             'reposite_id' => $request->reposite_id,
//         ]);

//         if($request->driver_id){
//             $order->driver_id = $request->driver_id;
//             $order->save();

//             $transport_percent = Setting::first() ? Setting::first()->transport_percent : 0 ;
//             $transport = Transport::create([
//                 'employee_id'   => $request->driver_id,
//                 'user_id'   => auth()->user()->id,
//                 'branch_id'   => auth()->user()->branch_id,
//                 'order_id'   => $order->id,
//                 'date'   => date('Y-m-d'),
//                 'type'   => 'out',
//                 'cost'   => $request->driving_cost,
//                 'rate'   => ($request->driving_cost * $transport_percent)/100,
//                 'percent'   => $transport_percent
//             ]);
//         }
//         if($request->driving_cost){
//             $order->driving_cost = $request->driving_cost;
//             $order->save();
//         }



//         $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'return-orders-in', $order->id, $this->dateNow, $this->timeNow, null, null );

        

//         if($request->ownerable_type == 'Client'){
//             $client=Client::where('id',$request->ownerable_id)->first();
//             if ($client){
//                 $client->decrement('balance',($request->rest));
//             }
//         }else{
//             $client=Supplier::where('id',$request->ownerable_id)->first();
//             if ($client){
//                 $client->increment('balance',($request->rest));
//             }
//         }
        
//         $reposite=Reposite::where('id',$request->reposite_id)->first();
//         if ($reposite){
//             $this->push_notification(['user_id'=>$reposite->user_id,'url'=>url('pending-pays')]);
//         }
//         $counter = 0;
//         if ($request->cost > 0) {
//             Account::create([
//                 'type' => 'out',
//                 'order_id' => $order->id,
//                 'cost' => $order->cost,
//                 'date' => $order->date,
//                 'reposite_id' => $order->reposite_id,
//                 'accountable_id' => $request->ownerable_id,
//                 'accountable_type' => 'App\Models\\' . $request->ownerable_type,
//             ]);
//         }
//         $user_store = Store::where('user_id', Auth()->user()->id)->first();
//         foreach ($request->group_id as $item) {
//             $load_pending = 1;
//             $price_pending = 0;
//             if ($user_store) {
//                 if ($user_store->id == $request->store_id[$counter]) {
//                     $load_pending = 0;
//                 }
//             }

//             $quantity = Quantity::where('item_id', $request->item_id[$counter])
//                 ->where('ownerable_id', $request->store_id[$counter])
//                 ->where('ownerable_type', 'App\Models\Store')->first();
//             OrderDetail::create([
//                 'order_id' => $order->id,
//                 'item_id' => $request->item_id[$counter],
//                 'discount' => $request->item_discount[$counter],
//                 'quantity' => $request->quantity[$counter],
//                 'unite_price' => $request->price[$counter],
//                 'store_id' => $request->store_id[$counter],
//                 'load_pending' => $load_pending,
//                 'price_pending' => $price_pending,
//             ]);
//             if ($load_pending == 0) {
//                 $quantity->increment('quantity', $request->quantity[$counter]);
//             }
//             $store=Store::where('id',$request->store_id[$counter])->first();
//             if ($store){
//                 $this->push_notification(['user_id'=>$store->user_id,'url'=>url('pending-loads')]);
//             }
//             QuantityDailies::create([
//                 'ownerable_id'=>$request->store_id[$counter],
//                 'ownerable_type'=>'App\Models\Store',
//                 'item_id'=>$request->item_id[$counter],
//                 'quantity'=>$request->quantity[$counter],
//                 'type' => '2', // 
//             ]);
//             $counter++;
//         }
//         DB::commit();
//         flash('تمت العمليه بنجاح')->success();
//         return redirect()->route('return-orders-in.index');
//     }

    public function store(Request $request) {
        $validator = validator()->make($request->all(), [
            'date' => 'required',
            'ownerable_id' => 'required|numeric',
            'cost' => 'required',
            'quantity.*' => 'required|numeric',
            'price.*' => 'required|numeric',
            'store_id' => 'required',
        ], [
            'date.required' => 'من فضلط اختر التاريخ',
            'ownerable_id.required' => 'من فضلك اختر المشتري ',
            'ownerable_id.numeric' => 'من فضلك اختر المشتري ',
            'cost.required' => 'من فضللك ادخل المبلغ المدفوع',
            'quantity.required' => '',
            'store_id.required' => 'من فضلك اختر لمخزن',
        ]);

        if($validator->fails()) {
            flash($validator->errors()->first())->error();
            return back();
        }

        DB::beginTransaction();
        $order = Order::create([
            'mandator_id' => $request->mandator_id,
            'is_return' => true,
            'type' => 'out',
            'date' => $request->date,
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
            'role' => 'return-in',
        ]);

        if($request->driver_id) {
            $order->driver_id = $request->driver_id;
            $order->save();

            $transport_percent = Setting::first() ? Setting::first()->transport_percent : 0 ;
            $transport = Transport::create([
                'employee_id'   => $request->driver_id,
                'user_id'   => auth()->user()->id,
                'branch_id'   => auth()->user()->branch_id,
                'order_id'   => $order->id,
                'date'   => date('Y-m-d'),
                'type'   => 'out',
                'cost'   => $request->driving_cost,
                'rate'   => ($request->driving_cost * $transport_percent)/100,
                'percent'   => $transport_percent
            ]);
        }

        if($request->driving_cost){
            $order->driving_cost = $request->driving_cost;
            $order->save();
        }

        $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'return-orders-in', $order->id, $this->dateNow, $this->timeNow, null, null );

        if($request->ownerable_type == 'Client'){
            $client = Client::where('id',$request->ownerable_id)->first();
            if ($client){
                $client->decrement('balance',($request->rest));
            }
        } else {
            $client=Supplier::where('id',$request->ownerable_id)->first();
            if ($client){
                $client->increment('balance',($request->rest));
            }
        }
        
        $reposite = Reposite::where('id',$request->reposite_id)->first();
        if($reposite){
            $this->push_notification(['user_id'=>$reposite->user_id,'url'=>url('pending-pays')]);
        }

        $counter = 0;
        if($request->cost > 0) {
            Account::create([
                'type' => 'out',
                'order_id' => $order->id,
                'cost' => $order->cost,
                'date' => $order->date,
                'reposite_id' => $order->reposite_id,
                'accountable_id' => $request->ownerable_id,
                'accountable_type' => 'App\Models\\' . $request->ownerable_type,
            ]);
        }

        $user_store = Store::where('user_id', Auth()->user()->id)->first();
        foreach($request->group_id as $item) {
            $load_pending = 1;
            $price_pending = 0;
            if ($user_store) {
                if ($user_store->id == $request->store_id[$counter]) {
                    $load_pending = 0;
                }
            }

            $quantity = Quantity::where('item_id', $request->item_id[$counter])
                ->where('ownerable_id', $request->store_id[$counter])
                ->where('ownerable_type', 'App\Models\Store')->first();

            OrderDetail::create([
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
            //     $quantity->increment('quantity', $request->quantity[$counter]);
            // }

            $store = Store::where('id',$request->store_id[$counter])->first();
            if ($store){
                $this->push_notification(['user_id'=>$store->user_id,'url'=>url('pending-loads')]);
            }

            // QuantityDailies::create([
            //     'ownerable_id'=>$request->store_id[$counter],
            //     'ownerable_type'=>'App\Models\Store',
            //     'item_id'=>$request->item_id[$counter],
            //     'quantity'=>$request->quantity[$counter],
            //     'type' => '2',
            // ]);

            $counter++;
        }
        DB::commit();
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('return-orders-in.index');
    }

    public function show($id) {
        $type = 'invoice';
        $resource = Order::with('orderDetails', 'ownerable', 'reposite')->findOrFail($id);
        return view('return-orders-in.show', compact('resource', 'type'));
    }

    public function print_license(int $id) {
        $type = 'license';
        $resource = Order::with('orderDetails', 'ownerable', 'reposite')->findOrFail($id);
        return view('return-orders-in.show', compact('resource', 'type'));
    }

    public function print_store_license(int $id) {
        $type = 'store';
        $resource = Order::with('orderDetails', 'ownerable', 'reposite')->findOrFail($id);
        return view('return-orders-in.storeShow', compact('resource', 'type'));
    }
    
    public function quickview(Order $resource) {
        return view("return-orders-in.quickview", compact("resource"));
    }
    
    public function loadData() {
        $resources = Order::with('orderDetails', 'ownerable', 'reposite', 'user')->where('is_return',true)->where('type', 'out')->orderBy('created_at', 'DESC')->get();
        return view('return-orders-in.data', compact('resources'));
    }

    // public function edit($id)
    // {
    //     $resource = Order::with('orderDetails', 'ownerable')->where('type', 'out')->where('id', $id)->first();
    //     return view('return-orders-in.edit', compact('resource'));
    // }
    
    public function edit($id)
    {
        $jobDriver = Job::where('name', 'سائق')->first();
        if($jobDriver){
            $drivers = Employee::where('job_id', $jobDriver->id)->latest()->get();
        }else{
            $drivers = Employee::latest()->get();
        }
        $resource = Order::with('orderDetails', 'ownerable')->where('type', 'out')->where('id', $id)->first();
        return view('return-orders-in.edit', compact('resource', 'drivers'));
    }

    // public function update(Request $request, $id)
    // {
    //     $oldData = Order::where('type', 'out')->where('id', $id)->first();
    //     $resource = Order::where('type', 'out')->where('id', $id)->first();
    //     $resource->update(['mandator_id' => $request->mandator_id]);
    //     if ($resource->ownerable_type == 'App\Models\Client') {
    //         $customer = Client::where('id', $resource->ownerable_id)->first();
    //     } else {
    //         $customer = Supplier::where('id', $resource->ownerable_id)->first();
    //     }

    //     $counter = 0;
    //     $total = 0;
    //     DB::beginTransaction();
    //     foreach ($request->detail_id as $item) {
    //         $detail = OrderDetail::where('id', $request->detail_id[$counter])->first();
    //         if ($detail) {
    //             $quantity = Quantity::where('item_id', $detail->item_id)
    //                 ->where('ownerable_id', $detail->store_id)
    //                 ->where('ownerable_type', 'App\Models\Store')->first();
    //             if ($quantity) {
    //                 $quantity->decrement('quantity', $detail->quantity);
    //                 $detail->update([
    //                     'quantity' => $request->quantity[$counter],
    //                     'unite_price' => $request->price[$counter],
    //                     'discount' => $request->discount[$counter],
    //                 ]);
    //                 $quantity->increment('quantity', $request->quantity[$counter]);
    //                 $total = $total + ($request->quantity[$counter] * $request->price[$counter]) - $request->discount[$counter];
    //             }
    //         }
    //         $counter++;
    //     }
    //     $customer->increment('balance', $resource->final_total);
    //     $customer->decrement('balance', $total - $resource->discount);
    //     $resource->update([
    //         'total' => $total,
    //         'final_total' => $total - $resource->discount,
    //         'notes' => $request->resource_note
    //     ]);
           
    //     $newData = $request->all();
    //     $properties = [
    //         'old_data' => $oldData,
    //         'new_data' => $newData
    //     ];

    //     $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'return-orders-in', $resource->id, $this->dateNow, $this->timeNow, $properties, null );

    //     DB::commit();
    //     flash('تم الحفظ بنجاح')->success();
    //     return back();
    // }
    
    public function update(Request $request, $id) {
        $oldData = Order::where('type', 'out')->where('id', $id)->first();
        $resource = Order::where('type', 'out')->where('id', $id)->first();

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
            foreach ($request->detail_id as $item) {
                $detail = OrderDetail::where('id', $request->detail_id[$counter])->first();
                if($detail) {
                    $quantity = Quantity::where('item_id', $detail->item_id)
                        ->where('ownerable_id', $detail->store_id)
                        ->where('ownerable_type', 'App\Models\Store')->first();

                    if($quantity) {
                        // $quantity->decrement('quantity', $detail->quantity);
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
                        // $quantity->increment('quantity', $request->quantity[$counter]);
                        $total = $total + ($request->quantity[$counter] * $request->price[$counter]) - $request->discount[$counter];
                    }
                }
                $counter++;
            }

            $total = $total + ($request->driving_cost);

            $customer->increment('balance', $resource->final_total);
            $customer->decrement('balance', $total - $resource->discount);

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
                    'type'        => 'out',
                    'cost'        => $request->driving_cost,
                    'rate'        => ($request->driving_cost * $transport_percent)/100,
                    'percent'     => $transport_percent
                ]);
            } else {
                Transport::where('order_id', $resource->id)->delete();

                $resource->driver_id = $request->driver_id;
                $resource->save();

                $transport_percent = Setting::first() ? Setting::first()->transport_percent : 0 ;
                $transport = Transport::create([
                    'employee_id' => $request->driver_id,
                    'user_id'     => auth()->user()->id,
                    'branch_id'   => auth()->user()->branch_id,
                    'order_id'    => $resource->id,
                    'date'        => date('Y-m-d'),
                    'type'        => 'out',
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
                        // $quantity->decrement('quantity', $detail->quantity);
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

        $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'return-orders-in', $resource->id, $this->dateNow, $this->timeNow, $properties, null );
        if($pendingRefuedOrders) {
            $this->push_notification_update(['user_id' => $resource->receiver_id, 'url' => url('return-orders-in'), 'message' => 'تم اجراء تعديل فى فاتورة رقم ' . $resource->id]);
        }
        DB::commit();
        flash('تم الحفظ بنجاح')->success();
        return redirect()->route('return-orders-in.index');
    }

    public function destroy($id)
    {
        $record = Order::where('id', $id)->first();
        if ($record) {
            $reposte = Reposite::where('id', $record->reposite_id)->first();
            if ($reposte) {
                $reposte->increment('balance', $record->cost);
            }
            if ($record->ownerable_type == 'App\Models\Client') {
                $customer = Client::where('id', $record->ownerable_id)->first();
            } else {
                $customer = Supplier::where('id', $record->ownerable_id)->first();
            }
            if ($customer) {
                $customer->increment('balance', ($record->final_total - $record->cost));
                $account = Account::where('order_id', $id)->first();
                if ($account) {
//                    $customer->increment('balance', $account->cost);
                    $account->delete();
                }
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
                    $quantity->decrement('quantity', $detail->quantity);
                }
            }
            $detail->delete();
        }
        
        $oldData = Order::where('id', $id)->first();
        $properties = [
            'old_data' => $oldData,
        ];
        $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'return-orders-in', $record->id, $this->dateNow, $this->timeNow, $properties, null );

        $record->delete();
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('return-orders-in.index');
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
