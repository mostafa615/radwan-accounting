<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Quantity;
use App\Models\QuantityDailies;
use App\Models\Store;
use Illuminate\Http\Request;
use Pusher\Pusher;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    // public function pendingOrders() {
    //     $ordersIn = Order::with('orderDetails', 'ownerable', 'reposite', 'user')
    //         ->where('role', 'in')
    //         ->where('branch_id', auth()->user()->branch_id)
    //         ->orderBy('created_at', 'DESC')
    //         ->whereHas('orderDetails', function ($query) {
    //             $query->where('status', 'pending');
    //         })
    //         ->paginate(10);

    //     $ordersReturnIn = Order::with('orderDetails', 'ownerable', 'reposite', 'user')
    //         ->where('role', 'return-in')
    //         ->where('branch_id', auth()->user()->branch_id)
    //         ->orderBy('created_at', 'DESC')
    //         ->whereHas('orderDetails', function ($query) {
    //             $query->where('status', 'pending');
    //         })
    //         ->paginate(10);

    //     $ordersOut = Order::with('orderDetails', 'ownerable', 'reposite', 'user')
    //         ->where('role', 'out')
    //         ->where('branch_id', auth()->user()->branch_id)
    //         ->orderBy('created_at', 'DESC')
    //         ->whereHas('orderDetails', function ($query) {
    //             $query->where('status', 'pending');
    //         })
    //         ->paginate(10);

    //     $ordersReturnOut = Order::with('orderDetails', 'ownerable', 'reposite', 'user')
    //         ->where('role', 'return-out')
    //         ->where('branch_id', auth()->user()->branch_id)
    //         ->orderBy('created_at', 'DESC')
    //         ->whereHas('orderDetails', function ($query) {
    //             $query->where('status', 'pending');
    //         })
    //         ->paginate(10);

    //     return view('pending-orders.index', compact('ordersIn', 'ordersOut', 'ordersReturnIn', 'ordersReturnOut'));
    // }
    
    public function pendingOrders() {
        $ordersInIds = DB::table('orders')
            ->where('orders.role', 'in')
            ->where('orders.branch_id', auth()->user()->branch_id)
            ->leftJoin('order_details', 'orders.id', '=', 'order_details.order_id')
            ->where('order_details.status', 'pending')
            ->orderBy('orders.created_at', 'DESC')
            ->pluck('orders.id');

        $ordersOutIds = DB::table('orders')
            ->where('role', 'out')
            ->where('branch_id', auth()->user()->branch_id)
            ->leftJoin('order_details', 'orders.id', '=', 'order_details.order_id')
            ->where('order_details.status', 'pending')
            ->orderBy('orders.created_at', 'DESC')
            ->pluck('orders.id');

        $ordersReturnInIds = DB::table('orders')
            ->where('orders.role', 'return-in')
            ->where('orders.branch_id', auth()->user()->branch_id)
            ->leftJoin('order_details', 'orders.id', '=', 'order_details.order_id')
            ->where('order_details.status', 'pending')
            ->orderBy('orders.created_at', 'DESC')
            ->pluck('orders.id');

        $ordersReturnOutIds = DB::table('orders')
            ->where('orders.role', 'return-out')
            ->where('orders.branch_id', auth()->user()->branch_id)
            ->leftJoin('order_details', 'orders.id', '=', 'order_details.order_id')
            ->where('order_details.status', 'pending')
            ->orderBy('orders.created_at', 'DESC')
            ->pluck('orders.id');

        return view('pending-orders.index', compact('ordersInIds', 'ordersOutIds', 'ordersReturnInIds', 'ordersReturnOutIds'));
    }
    
    // public function acceptOrder(Request $request) {
    //     $detail = OrderDetail::where('id', $request->id)->first();
    //     $order = Order::findorFail($detail->order_id);

    //     if(!$detail) {
    //         flash()->error('لقد تم حذف الصنف من الراسل');
    //         return back();
    //     }

    //     if($detail->status == 'pending') {
    //         if($order->role == 'in') {
    //             // $store = Store::where('id', $detail->store_id)->first();
    //             // if($store && Auth()->user()->id != 1) {
    //             //     $this->push_notification(['user_id' => $store->user_id, 'url' => url('pending-loads')]);
    //             // }

    //             $quantity = Quantity::where('item_id', $detail->item_id)
    //                 ->where('ownerable_id', $detail->store_id)
    //                 ->where('ownerable_type', 'App\Models\Store')->first();

    //             $quantity->decrement('quantity', $detail->quantity);
    //             $detail->update(['load_pending' => 0, 'status' => 'accepted', 'notes' => $request->notes]);
            
    //             QuantityDailies::create([
    //                 'ownerable_id' => $detail->store_id,
    //                 'ownerable_type' => 'App\Models\Store',
    //                 'item_id' => $detail->item_id,
    //                 'quantity' => $detail->quantity,
    //                 'type' => '1',
    //                 'type_order' => '1',
    //                 'order_id' => $detail->id,
    //             ]);
    //         }
    //         else if($order->role == 'return-in') {
    //             // $store = Store::where('id', $detail->store_id)->first();
    //             // if($store && Auth()->user()->id != 1) {
    //             //     $this->push_notification(['user_id' => $store->user_id, 'url' => url('pending-loads')]);
    //             // }

    //             $quantity = Quantity::where('item_id', $detail->item_id)
    //                 ->where('ownerable_id', $detail->store_id)
    //                 ->where('ownerable_type', 'App\Models\Store')->first();

    //             $quantity->increment('quantity', $detail->quantity);
    //             $detail->update(['load_pending' => 0, 'status' => 'accepted', 'notes' => $request->notes]);
            
    //             QuantityDailies::create([
    //                 'ownerable_id' => $detail->store_id,
    //                 'ownerable_type' => 'App\Models\Store',
    //                 'item_id' => $detail->item_id,
    //                 'quantity' => $detail->quantity,
    //                 'type' => '2',
    //             ]);
    //         }
    //         else if($order->role == 'out') {
    //             // $store = Store::where('id', $detail->store_id)->first();
    //             // if($store && Auth()->user()->id != 1) {
    //             //     $this->push_notification(['user_id' => $store->user_id, 'url' => url('pending-loads')]);
    //             // }

    //             $quantity = Quantity::where('item_id', $detail->item_id)
    //                 ->where('ownerable_id', $detail->store_id)
    //                 ->where('ownerable_type', 'App\Models\Store')->first();

    //             if(isset($quantity) && $detail->is_oper_supplies != 1) {
    //                 $quantity->increment('quantity', $detail->quantity);
    //             } else {
    //                 if($detail->is_oper_supplies == 1) {
    //                     $operSuppQnt = $detail->quantity;
    //                     DB::table('supplies')
    //                         ->where('id', $detail->item_id)
    //                         ->update([
    //                             'quantity' => DB::raw('quantity + '.$operSuppQnt)
    //                         ]);
    //                 } else {
    //                     Quantity::create([
    //                         'ownerable_id' => $detail->store_id,
    //                         'ownerable_type' => 'App\Models\Store',
    //                         'item_id' => $detail->item_id,
    //                         'quantity' => $detail->quantity
    //                     ]);
    //                 }
    //             }
    //             $detail->update(['load_pending' => 0, 'status' => 'accepted', 'notes' => $request->notes]);

    //             QuantityDailies::create([
    //                 'ownerable_id' => $detail->store_id,
    //                 'ownerable_type' => 'App\Models\Store',
    //                 'item_id' => $detail->item_id,
    //                 'quantity' => $detail->quantity,
    //                 'type' => '0',
    //             ]);
    //         }
    //         else if($order->role == 'return-out') {
    //             // $store = Store::where('id', $detail->store_id)->first();
    //             // if($store && Auth()->user()->id != 1) {
    //             //     $this->push_notification(['user_id' => $store->user_id, 'url' => url('pending-loads')]);
    //             // }

    //             $quantity = Quantity::where('item_id', $detail->item_id)
    //                 ->where('ownerable_id', $detail->store_id)
    //                 ->where('ownerable_type', 'App\Models\Store')->first();
                    
    //             if(isset($quantity) && $detail->is_oper_supplies != 1) {
    //                 $quantity->decrement('quantity', $detail->quantity);
    //             } else {
    //                 if($detail->is_oper_supplies == 1) {
    //                     $operSuppQnt = $detail->quantity;
    //                     DB::table('supplies')
    //                         ->where('id', $detail->item_id)
    //                         ->update([
    //                             'quantity' => DB::raw('quantity - '.$operSuppQnt)
    //                         ]);
    //                 }
    //             }
    //             $detail->update(['load_pending' => 0, 'status' => 'accepted', 'notes' => $request->notes]);
                
    //             QuantityDailies::create([
    //                 'ownerable_id' => $detail->store_id,
    //                 'ownerable_type' => 'App\Models\Store',
    //                 'item_id' => $detail->item_id,
    //                 'quantity' => $detail->quantity,
    //                 'type' => '2',
    //             ]);
    //         }
    //     }
        
    //     $order->update(['status' => 'checked', 'receiver_id' => auth()->user()->id]);

    //     flash('تم قبول الصنف بنجاح')->success();
    //     return back();
    // }

    // public function refuseOrder(Request $request) {        
    //     $detail = OrderDetail::where('id', $request->id)->first();
    //     $order = Order::findorFail($detail->order_id);

    //     if(!$detail) {
    //         flash()->error('لقد تم حذف الصنف من الراسل');
    //         return back();
    //     }

    //     if($detail->status == 'pending') {
    //         $detail->update(['status' => 'refused']);

    //         if(!empty($request->notes))
    //             $detail->update(['notes' => $request->notes]);
    //     }

    //     $order->update(['status' => 'checked', 'receiver_id' => auth()->user()->id]);

    //     flash('تم رفض الصنف بنجاح')->success();
    //     return back();
    // }
    
    
    public function acceptOrder(Request $request)
    {
        // Check if we have multiple IDs (comma-separated)
        if (strpos($request->id, ',') !== false) {
            $ids = explode(',', $request->id);

            foreach ($ids as $id) {
                $detail = OrderDetail::where('id', $id)->first();

                if (!$detail) {
                    continue; // Skip if detail not found
                }

                $order = Order::findorFail($detail->order_id);
                if (!empty($request->employee_id)) {
                    $order->employee()->syncWithoutDetaching($request->employee_id);
                }
                if ($detail->status == 'pending') {
                    if ($order->role == 'in') {
                        $quantity = Quantity::where('item_id', $detail->item_id)
                            ->where('ownerable_id', $detail->store_id)
                            ->where('ownerable_type', 'App\Models\Store')->first();

                        $quantity->decrement('quantity', $detail->quantity);
                        $detail->update(['load_pending' => 0, 'status' => 'accepted', 'notes' => $request->notes]);

                        QuantityDailies::create([
                            'ownerable_id' => $detail->store_id,
                            'ownerable_type' => 'App\Models\Store',
                            'item_id' => $detail->item_id,
                            'quantity' => $detail->quantity,
                            'type' => '1',
                            'type_order' => '1',
                            'order_id' => $detail->id,
                        ]);
                    } else if ($order->role == 'return-in') {
                        $quantity = Quantity::where('item_id', $detail->item_id)
                            ->where('ownerable_id', $detail->store_id)
                            ->where('ownerable_type', 'App\Models\Store')->first();

                        $quantity->increment('quantity', $detail->quantity);
                        $detail->update(['load_pending' => 0, 'status' => 'accepted', 'notes' => $request->notes]);

                        QuantityDailies::create([
                            'ownerable_id' => $detail->store_id,
                            'ownerable_type' => 'App\Models\Store',
                            'item_id' => $detail->item_id,
                            'quantity' => $detail->quantity,
                            'type' => '2',
                        ]);
                    } else if ($order->role == 'out') {
                        $quantity = Quantity::where('item_id', $detail->item_id)
                            ->where('ownerable_id', $detail->store_id)
                            ->where('ownerable_type', 'App\Models\Store')->first();

                        if (isset($quantity) && $detail->is_oper_supplies != 1) {
                            $quantity->increment('quantity', $detail->quantity);
                        } else {
                            if ($detail->is_oper_supplies == 1) {
                                $operSuppQnt = $detail->quantity;
                                DB::table('supplies')
                                    ->where('id', $detail->item_id)
                                    ->update([
                                        'quantity' => DB::raw('quantity + ' . $operSuppQnt)
                                    ]);
                            } else {
                                Quantity::create([
                                    'ownerable_id' => $detail->store_id,
                                    'ownerable_type' => 'App\Models\Store',
                                    'item_id' => $detail->item_id,
                                    'quantity' => $detail->quantity
                                ]);
                            }
                        }
                        $detail->update(['load_pending' => 0, 'status' => 'accepted', 'notes' => $request->notes]);

                        QuantityDailies::create([
                            'ownerable_id' => $detail->store_id,
                            'ownerable_type' => 'App\Models\Store',
                            'item_id' => $detail->item_id,
                            'quantity' => $detail->quantity,
                            'type' => '0',
                        ]);
                    } else if ($order->role == 'return-out') {
                        $quantity = Quantity::where('item_id', $detail->item_id)
                            ->where('ownerable_id', $detail->store_id)
                            ->where('ownerable_type', 'App\Models\Store')->first();

                        if (isset($quantity) && $detail->is_oper_supplies != 1) {
                            $quantity->decrement('quantity', $detail->quantity);
                        } else {
                            if ($detail->is_oper_supplies == 1) {
                                $operSuppQnt = $detail->quantity;
                                DB::table('supplies')
                                    ->where('id', $detail->item_id)
                                    ->update([
                                        'quantity' => DB::raw('quantity - ' . $operSuppQnt)
                                    ]);
                            }
                        }
                        $detail->update(['load_pending' => 0, 'status' => 'accepted', 'notes' => $request->notes]);

                        QuantityDailies::create([
                            'ownerable_id' => $detail->store_id,
                            'ownerable_type' => 'App\Models\Store',
                            'item_id' => $detail->item_id,
                            'quantity' => $detail->quantity,
                            'type' => '2',
                        ]);
                    }
                }

                $order->update(['status' => 'checked', 'receiver_id' => auth()->user()->id]);
            }

            flash('تم قبول الأصناف بنجاح')->success();
            return back();
        }

        // Handle single item acceptance (existing code)
        $detail = OrderDetail::where('id', $request->id)->first();
        $order = Order::findorFail($detail->order_id);

        if (!empty($request->employee_id)) {
            $order->employee()->syncWithoutDetaching($request->employee_id);
        }
        if (!$detail) {
            flash()->error('لقد تم حذف الصنف من الراسل');
            return back();
        }

        if ($detail->status == 'pending') {
            if ($order->role == 'in') {
                $quantity = Quantity::where('item_id', $detail->item_id)
                    ->where('ownerable_id', $detail->store_id)
                    ->where('ownerable_type', 'App\Models\Store')->first();

                $quantity->decrement('quantity', $detail->quantity);
                $detail->update(['load_pending' => 0, 'status' => 'accepted', 'notes' => $request->notes]);

                QuantityDailies::create([
                    'ownerable_id' => $detail->store_id,
                    'ownerable_type' => 'App\Models\Store',
                    'item_id' => $detail->item_id,
                    'quantity' => $detail->quantity,
                    'type' => '1',
                    'type_order' => '1',
                    'order_id' => $detail->id,
                ]);
            } else if ($order->role == 'return-in') {
                $quantity = Quantity::where('item_id', $detail->item_id)
                    ->where('ownerable_id', $detail->store_id)
                    ->where('ownerable_type', 'App\Models\Store')->first();

                $quantity->increment('quantity', $detail->quantity);
                $detail->update(['load_pending' => 0, 'status' => 'accepted', 'notes' => $request->notes]);

                QuantityDailies::create([
                    'ownerable_id' => $detail->store_id,
                    'ownerable_type' => 'App\Models\Store',
                    'item_id' => $detail->item_id,
                    'quantity' => $detail->quantity,
                    'type' => '2',
                ]);
            } else if ($order->role == 'out') {
                $quantity = Quantity::where('item_id', $detail->item_id)
                    ->where('ownerable_id', $detail->store_id)
                    ->where('ownerable_type', 'App\Models\Store')->first();

                if (isset($quantity) && $detail->is_oper_supplies != 1) {
                    $quantity->increment('quantity', $detail->quantity);
                } else {
                    if ($detail->is_oper_supplies == 1) {
                        $operSuppQnt = $detail->quantity;
                        DB::table('supplies')
                            ->where('id', $detail->item_id)
                            ->update([
                                'quantity' => DB::raw('quantity + ' . $operSuppQnt)
                            ]);
                    } else {
                        Quantity::create([
                            'ownerable_id' => $detail->store_id,
                            'ownerable_type' => 'App\Models\Store',
                            'item_id' => $detail->item_id,
                            'quantity' => $detail->quantity
                        ]);
                    }
                }
                $detail->update(['load_pending' => 0, 'status' => 'accepted', 'notes' => $request->notes]);

                QuantityDailies::create([
                    'ownerable_id' => $detail->store_id,
                    'ownerable_type' => 'App\Models\Store',
                    'item_id' => $detail->item_id,
                    'quantity' => $detail->quantity,
                    'type' => '0',
                ]);
            } else if ($order->role == 'return-out') {
                $quantity = Quantity::where('item_id', $detail->item_id)
                    ->where('ownerable_id', $detail->store_id)
                    ->where('ownerable_type', 'App\Models\Store')->first();

                if (isset($quantity) && $detail->is_oper_supplies != 1) {
                    $quantity->decrement('quantity', $detail->quantity);
                } else {
                    if ($detail->is_oper_supplies == 1) {
                        $operSuppQnt = $detail->quantity;
                        DB::table('supplies')
                            ->where('id', $detail->item_id)
                            ->update([
                                'quantity' => DB::raw('quantity - ' . $operSuppQnt)
                            ]);
                    }
                }
                $detail->update(['load_pending' => 0, 'status' => 'accepted', 'notes' => $request->notes]);

                QuantityDailies::create([
                    'ownerable_id' => $detail->store_id,
                    'ownerable_type' => 'App\Models\Store',
                    'item_id' => $detail->item_id,
                    'quantity' => $detail->quantity,
                    'type' => '2',
                ]);
            }
        }

        $order->update(['status' => 'checked', 'receiver_id' => auth()->user()->id]);

        flash('تم قبول الصنف بنجاح')->success();
        return back();
    }

    public function refuseOrder(Request $request)
    {
        // Check if we have multiple IDs (comma-separated)
        if (strpos($request->id, ',') !== false) {
            $ids = explode(',', $request->id);

            foreach ($ids as $id) {
                $detail = OrderDetail::where('id', $id)->first();

                if (!$detail) {
                    continue; // Skip if detail not found
                }

                $order = Order::findorFail($detail->order_id);
                if (!empty($request->employee_id)) {
                    $order->employee()->syncWithoutDetaching($request->employee_id);
                }
                if ($detail->status == 'pending') {
                    $detail->update(['status' => 'refused']);

                    if (!empty($request->notes)) {
                        $detail->update(['notes' => $request->notes]);
                    }
                }

                $order->update(['status' => 'checked', 'receiver_id' => auth()->user()->id]);
            }

            flash('تم رفض الأصناف بنجاح')->success();
            return back();
        }

        // Handle single item refusal (existing code)
        $detail = OrderDetail::where('id', $request->id)->first();
        $order = Order::findorFail($detail->order_id);

        if (!empty($request->employee_id)) {
            $order->employee()->syncWithoutDetaching($request->employee_id);
        }
        if (!$detail) {
            flash()->error('لقد تم حذف الصنف من الراسل');
            return back();
        }

        if ($detail->status == 'pending') {
            $detail->update(['status' => 'refused']);

            if (!empty($request->notes))
                $detail->update(['notes' => $request->notes]);
        }

        $order->update(['status' => 'checked', 'receiver_id' => auth()->user()->id]);

        flash('تم رفض الصنف بنجاح')->success();
        return back();
    }

    public function setEmployees(Request $request) {
        $order = Order::where('id', $request->id)->first();

        if(!$order) {
            flash()->error('لقد تم حذف الفاتورة من الراسل');
            return back();
        }

        if(!empty($request->employee_id))
            $order->employee()->attach($request->employee_id);

        if(!empty($request->notes))
            $order->update(['notes2' => $request->notes]);

        $order->update(['receiver_id' => auth()->user()->id]);

        flash()->success('تم الحفظ بنجاح');
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
}