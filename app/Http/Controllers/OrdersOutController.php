<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\OrderDetail;
use App\Models\Quantity;
use App\Models\QuantityDailies;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Store;
use App\Models\Order;
use App\Models\Reposite;
use App\Models\Group;
use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use function PHPSTORM_META\type;
use Pusher\Pusher;


class OrdersOutController extends Controller
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
    //         ->where('is_return', false)->where('type', 'out');
            
    //     if (request()->search) {
    //         $query
    //         ->where('date', 'like', '%'. request()->search .'%')
    //         ->orWhere('id', 'like', '%'. request()->search .'%');
    //     }
            
    //     $resources = $query->orderBy('date', 'DESC')->paginate(10);
    //     return view('orders-out.index', compact('resources'));
    // }
    
    public function index() {
        $query = Order::with('orderDetails', 'ownerable', 'reposite', 'user')
            ->where('is_return', false)->where('type', 'out');
            
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
        
        return view('orders-out.index', compact('resources'));
    }

    public function create()
    {
        //
        $groups = Group::has('items')->get();
        $suppliers = Supplier::select('id', 'name')->get();
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
        return view('orders-out.create', compact('groups', 'suppliers', 'stores', 'reposites'));
    }

//     public function store(Request $request)
//     {

//         $validator = validator()->make($request->all(), [
//             'date' => 'required',
//             'supplier_id' => 'required|numeric',
//             'cost' => 'required',
//             'quantity.*' => 'required|numeric',
//             'price.*' => 'required|numeric',
//             'item_discount.*' => 'numeric',
//             'item_id.*' => 'numeric',
//             'store_id' => 'required',
//         ], [
//             'date.required' => 'من فضلط اختر التاريخ',
//             'supplier_id.required' => 'من فضلك اختر المشتري ',
//             'supplier_id.numeric' => 'من فضلك اختر المشتري ',
//             'cost.required' => 'من فضللك ادخل المبلغ المدفوع',
//             'quantity.required' => '',
//             'store_id.required' => 'من فضلك اختر لمخزن',
//             'item_id.numeric' => 'اسم الصنف اجبالى',
//         ]);

//         if ($validator->fails()) {
//             flash($validator->errors()->first())->error();
//             return back();
//         }
//         DB::beginTransaction();
//         $order = Order::create([
//             'type' => 'out',
//             'date' => $request->date,
//             'ownerable_id' => $request->supplier_id,
//             'ownerable_type' => 'App\Models\Supplier',
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
        
//         $reposite = Reposite::where('id', $request->reposite_id)->first();
//         if ($reposite) {
//             //$reposite->decrement('balance', $request->cost);
// //            if (Auth()->user()->id != 1) {
//                 $this->push_notification(['user_id' => $reposite->user_id,'url'=>url('pending-pays')]);
// //            }
//         }
//         $counter = 0;
//         $pending=0;
//         if ($request->cost > 0 && Auth()->user()->id == 1) {
//             $pending=0;
//             $customer = Supplier::where('id', $request->supplier_id)->first();
//             if ($customer) {
//                 $customer->increment('balance', $request->rest);
//             }
//         }
//         if($request->cost >= 0){
//             $customer = Supplier::where('id', $request->supplier_id)->first();
//             if ($customer) {
//                 $customer->increment('balance', $request->rest);
//             }
//         }
//         $accountCreate = Account::create([
//             'type' => 'out',
//             'order_id' => $order->id,
//             'cost' => $order->cost,
//             'date' => $order->date,
//             'reposite_id' => $order->reposite_id,
//             'accountable_id' => $request->supplier_id,
//             'accountable_type' => 'App\Models\Supplier',
//             'pending' => 1
//         ]);
//         $properties = [
//             'order' => $order,
//             'accountCreate' => $accountCreate
//         ];
//         $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'orders-out', $order->id, $this->dateNow, $this->timeNow, $properties, null );

//         $user_store = Store::where('user_id', Auth()->user()->id)->first();
//         foreach ($request->group_id as $item) {
//             $group= DB::table('groups')->where('id', $item)->first();
//             if($group->name == "NO4"){
//                 $order->is_oper_supplies=1;
//                 $order->save();
//             }  
//             if($group->name == "NO4"){
//                 $accountCreate->is_oper_supplies=1;
//                 $accountCreate->save();
//             } 
            
//             if (isset($request->item_id[$counter])) {
                
//                 $load_pending = 1;
//                 $price_pending = 0;
    
//                 if ($user_store) {
//                     if ($user_store->id == $request->store_id[$counter]) {
//                         $load_pending = 0;
//                     }
//                 }
//                 if (Auth()->user()->id == 1) {
//                     $load_pending = 0;
//                 }
//                 $store = Store::where('id', $request->store_id[$counter])->first();
//                 if ($store) {
//                     $this->push_notification(['user_id' => $store->user_id,'url'=>url('pending-loads')]);
//                 }
//                 $quantity = Quantity::where('item_id', $request->item_id[$counter])
//                     ->where('ownerable_id', $request->store_id[$counter])
//                     ->where('ownerable_type', 'App\Models\Store')->first();
//                 $orderDetailCreate = OrderDetail::create([
//                     'order_id' => $order->id,
//                     'item_id' => $request->item_id[$counter],
//                     'discount' => $request->item_discount[$counter],
//                     'quantity' => $request->quantity[$counter],
//                     'unite_price' => $request->price[$counter],
//                     'store_id' => $request->store_id[$counter],
//                     'load_pending' => $load_pending,
//                     'price_pending' => $price_pending,
//                 ]);

//                 if($group->name == "NO4"){
//                     $orderDetailCreate->is_oper_supplies=1;
//                     $orderDetailCreate->save();
//                 }
    
//                 if ($load_pending == 0) {
//                     if (isset($quantity) && $group->name != "NO4") {
//                         $quantity->increment('quantity', $request->quantity[$counter]);
//                     } else {
//                         if($group->name == "NO4"){
//                             $operSuppQnt = $request->quantity[$counter];
//                             DB::table('supplies')
//                             ->where('id', $request->item_id[$counter])
//                             ->update([
//                                 'quantity' => DB::raw('quantity + '.$operSuppQnt)
//                             ]);
//                         }else{
//                             Quantity::create([
//                                 'ownerable_id'=>$request->store_id[$counter],
//                                 'ownerable_type'=>'App\Models\Store',
//                                 'item_id'=>$request->item_id[$counter],
//                                 'quantity'=>$request->quantity[$counter]
//                             ]);
//                         }
                        
//                     }
//                 }
//             }
//             QuantityDailies::create([
//                 'ownerable_id'=>$request->store_id[$counter],
//                 'ownerable_type'=>'App\Models\Store',
//                 'item_id'=>$request->item_id[$counter],
//                 'quantity'=>$request->quantity[$counter],
//                 'type' => '0',
//             ]);
//             $counter++;
//         }
//         DB::commit();
//         flash('تمت العمليه بنجاح')->success();
//         return redirect()->route('orders-out.index');
//     }

    public function store(Request $request) {
        $validator = validator()->make($request->all(), [
            'date' => 'required',
            'supplier_id' => 'required|numeric',
            'cost' => 'required',
            'quantity.*' => 'required|numeric',
            'price.*' => 'required|numeric',
            'item_discount.*' => 'numeric',
            'item_id.*' => 'numeric',
            'store_id' => 'required',
        ], [
            'date.required' => 'من فضلط اختر التاريخ',
            'supplier_id.required' => 'من فضلك اختر المشتري ',
            'supplier_id.numeric' => 'من فضلك اختر المشتري ',
            'cost.required' => 'من فضللك ادخل المبلغ المدفوع',
            'quantity.required' => '',
            'store_id.required' => 'من فضلك اختر لمخزن',
            'item_id.numeric' => 'اسم الصنف اجبالى',
        ]);

        if($validator->fails()) {
            flash($validator->errors()->first())->error();
            return back();
        }

        DB::beginTransaction();
        $order = Order::create([
            'type' => 'out',
            'date' => $request->date,
            'ownerable_id' => $request->supplier_id,
            'ownerable_type' => 'App\Models\Supplier',
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
            'role' => 'out',
        ]);
        
        $reposite = Reposite::where('id', $request->reposite_id)->first();
        if($reposite) {
            //$reposite->decrement('balance', $request->cost);
//            if (Auth()->user()->id != 1) {
                $this->push_notification(['user_id' => $reposite->user_id,'url'=>url('pending-pays')]);
//            }
        }

        $counter = 0;
        $pending = 0;
        if($request->cost > 0 && Auth()->user()->id == 1) {
            $pending=0;
            $customer = Supplier::where('id', $request->supplier_id)->first();
            if ($customer) {
                $customer->increment('balance', $request->rest);
            }
        }

        if($request->cost >= 0){
            $customer = Supplier::where('id', $request->supplier_id)->first();
            if ($customer) {
                $customer->increment('balance', $request->rest);
            }
        }
        
        $accountCreate = Account::create([
            'type' => 'out',
            'order_id' => $order->id,
            'cost' => $order->cost,
            'date' => $order->date,
            'reposite_id' => $order->reposite_id,
            'accountable_id' => $request->supplier_id,
            'accountable_type' => 'App\Models\Supplier',
            'pending' => 1
        ]);

        $properties = [
            'order' => $order,
            'accountCreate' => $accountCreate
        ];

        $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'orders-out', $order->id, $this->dateNow, $this->timeNow, $properties, null );

        $user_store = Store::where('user_id', Auth()->user()->id)->first();
        foreach($request->group_id as $item) {
            $group= DB::table('groups')->where('id', $item)->first();
            if($group->name == "NO4"){
                $order->is_oper_supplies=1;
                $order->save();
            }

            if($group->name == "NO4"){
                $accountCreate->is_oper_supplies=1;
                $accountCreate->save();
            } 
            
            if (isset($request->item_id[$counter])) {
                $load_pending = 1;
                $price_pending = 0;
    
                if ($user_store) {
                    if ($user_store->id == $request->store_id[$counter]) {
                        $load_pending = 0;
                    }
                }

                if (Auth()->user()->id == 1) {
                    $load_pending = 0;
                }

                $store = Store::where('id', $request->store_id[$counter])->first();
                if ($store) {
                    $this->push_notification(['user_id' => $store->user_id,'url'=>url('pending-loads')]);
                }

                $quantity = Quantity::where('item_id', $request->item_id[$counter])
                    ->where('ownerable_id', $request->store_id[$counter])
                    ->where('ownerable_type', 'App\Models\Store')->first();

                $orderDetailCreate = OrderDetail::create([
                    'order_id' => $order->id,
                    'item_id' => $request->item_id[$counter],
                    'discount' => $request->item_discount[$counter],
                    'quantity' => $request->quantity[$counter],
                    'unite_price' => $request->price[$counter],
                    'store_id' => $request->store_id[$counter],
                    'load_pending' => $load_pending,
                    'price_pending' => $price_pending,
                ]);

                if($group->name == "NO4"){
                    $orderDetailCreate->is_oper_supplies=1;
                    $orderDetailCreate->save();
                }
    
                // if($load_pending == 0) {
                //     if (isset($quantity) && $group->name != "NO4") {
                //         $quantity->increment('quantity', $request->quantity[$counter]);
                //     } else {
                //         if($group->name == "NO4"){
                //             $operSuppQnt = $request->quantity[$counter];
                //             DB::table('supplies')
                //             ->where('id', $request->item_id[$counter])
                //             ->update([
                //                 'quantity' => DB::raw('quantity + '.$operSuppQnt)
                //             ]);
                //         }else{
                //             Quantity::create([
                //                 'ownerable_id'=>$request->store_id[$counter],
                //                 'ownerable_type'=>'App\Models\Store',
                //                 'item_id'=>$request->item_id[$counter],
                //                 'quantity'=>$request->quantity[$counter]
                //             ]);
                //         }
                        
                //     }
                // }
            }
            // QuantityDailies::create([
            //     'ownerable_id'=>$request->store_id[$counter],
            //     'ownerable_type'=>'App\Models\Store',
            //     'item_id'=>$request->item_id[$counter],
            //     'quantity'=>$request->quantity[$counter],
            //     'type' => '0',
            // ]);
            $counter++;
        }
        DB::commit();
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('orders-out.index');
    }

    public function show($id)
    {
        $type='invoice';
        $resource = Order::with('orderDetails', 'ownerable')->findOrFail($id);
        return view('orders-out.show', compact('resource','type'));
    }
    
    public function print_license($id)
    {
        $type='licence';
        $resource = Order::with('orderDetails', 'ownerable')->findOrFail($id);
        return view('orders-out.show', compact('resource','type'));
    }
    
    public function print_store_license($id)
    {
        $type = 'store';
        $resource = Order::with('orderDetails', 'ownerable', 'reposite')->findOrFail($id);
        return view('orders-out.storeShow', compact('resource', 'type'));
    }
    
    public function edit($id)
    {
        $resource = Order::with('orderDetails', 'ownerable')->where('type', 'out')->where('is_return', false)->where('id', $id)->first();
        return view('orders-out.edit', compact('resource'));
    }

    // public function update(Request $request, $id)
    // {
    //     $oldData = Order::where('type', 'out')->where('id', $id)->first();

    //     $resource = Order::where('type', 'out')->where('id', $id)->first();
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
    //     $customer->decrement('balance', $resource->total - $resource->discount);
    //     $customer->increment('balance', $total - $resource->discount);
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

    //     $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'orders-out', $resource->id, $this->dateNow, $this->timeNow, $properties, null );

    //     DB::commit();
    //     flash('تم الحفظ بنجاح')->success();
    //     return back();
    // }
    
    public function update(Request $request, $id) {
        $oldData = Order::where('type', 'out')->where('id', $id)->first();
        $resource = Order::where('type', 'out')->where('id', $id)->first();

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

            $customer->decrement('balance', $resource->total - $resource->discount);
            $customer->increment('balance', $total - $resource->discount);

            $resource->update([
                'total' => $total,
                'final_total' => $total - $resource->discount,
            ]);

            // $resource->status = 'pending';
            $resource->save();
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

        $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'orders-out', $resource->id, $this->dateNow, $this->timeNow, $properties, null );
        if($pendingRefuedOrders) {
            $this->push_notification_update(['user_id' => $resource->receiver_id, 'url' => url('orders-out'), 'message' => 'تم اجراء تعديل فى فاتورة رقم ' . $resource->id]);
        }
        DB::commit();
        flash('تم الحفظ بنجاح')->success();
        return redirect()->route('orders-out.index');
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
                $account = Account::where('order_id', $id)->first();
                if ($account) {
                    if ($account->pending != 1) {
                        $customer->decrement('balance', $record->final_total);
                    }
                    $account->delete();
                }
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
        $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'orders-out', $record->id, $this->dateNow, $this->timeNow, $properties, null );

        $record->delete();
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('orders-out.index');
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
    //             $quantity->decrement('quantity', $detail->quantity);
    //             $total = $resource->final_total - ($detail->quantity * $detail->unite_price) - $detail->discount;
    //         }
    //     }
    //     $customer->decrement('balance', $resource->final_total);
    //     $customer->increment('balance', $total - $resource->discount);
    //     $resource->update([
    //         'total' => $total,
    //         'final_total' => $total - $resource->discount
    //     ]);
        
    //     $oldData = OrderDetail::where('id', $id)->first();
    //     $properties = [
    //         'order_data' => $resource,
    //         'old_order_detail_data' => $oldData,
    //     ];
    //     $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'orders-out-details', $detail->id, $this->dateNow, $this->timeNow, $properties, null );

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
        
        $oldData = OrderDetail::where('id', $id)->first();
        $properties = [
            'order_data' => $resource,
            'old_order_detail_data' => $oldData,
        ];

        $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'orders-out-details', $detail->id, $this->dateNow, $this->timeNow, $properties, null );

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
