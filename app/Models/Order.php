<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DB;

use App\Models\Quantity;
use App\Models\orderDetail;

use App\Traits\ReturnsTrait;

class Order extends Model
{
    //
    // use ReturnsTrait;


    protected $guarded = [];
    protected $dates = ['date'];

    // protected $appends = ['max','payed'];
    
    public function setDateAttribute($value) {
        $this->attributes['date2'] = date('Y-m-d', strtotime($value));
        $this->attributes['date'] = $value;
    }
    
    public function employee() {
        return $this->belongsToMany(Employee::class, 'order_employee');
    }
    
    public function receiver() {
        return $this->belongsTo('App\Models\User', 'receiver_id');
    }
    
    public function branch() {
        return $this->belongsTo('App\Models\Branch', 'branch_id');
    }
    
    function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    function driver()
    {
        return $this->belongsTo('App\Models\Employee', 'driver_id');
    }

    public function postCreate()
    {

        $details = $this
            ->orderDetails()
            ->where('store_id', optional(auth()->user()->store)->id)
            ->get();
        foreach ($details as $detail) {
            $detail->performPendingLoad();
        }
    }

    //relations
    public function orderDetails()
    {
        return $this->hasMany('App\Models\OrderDetail')->with('item', 'store');
    }
    
    public function getOrderDetails() {
        return OrderDetail::where("order_id", $this->id)->get();
    }

    public function account()
    {
        return $this->hasOne('App\Models\Account');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function reposite()
    {
        return $this->belongsTo('App\Models\Reposite');
    }

    public function mandator()
    {
        return $this->belongsTo('App\Models\Mandator');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Order', 'order_id');
    }


    public function ownerable()
    {
        return $this->morphTo();
    }


    public function performDelete()
    {
        //
        DB::beginTransaction();
        $type = $this->type;
        $sign = 1;
        $details = $this->orderDetails()->where('load_pending', false)->get();

        if ($type == 'out') $sign = -1;
        if ($details->count()) {

            foreach ($details as $detail) {
                Quantity::where([
                    'ownerable_type' => $this->ownerable_type,
                    'ownerable_id' => $this->ownerable_id,
                    'item_id' => $detail->item_id,
                ])->increment('quantity', ($sign * -$detail->quantity));

                Quantity::where([
                    'ownerable_type' => 'App\Models\Store',
                    'ownerable_id' => $detail->store_id,
                    'item_id' => $detail->item_id,
                ])->increment('quantity', ($sign * $detail->quantity));
            }
        } else {
            optional($this->ownerable)->setBalance(($sign * -$this->final_total));
        }

        // get the order account
        $account = $this->account;

        if ($account) {
            if ($account->pending) {
                $account->reposite()->increment('balance', ($sign * -$account->cost));
            }
        }

        $this->delete();
        DB::commit();
    }

    public static function out($request)
    {
        DB::beginTransaction();
        //first create the order
        $order = self::create([
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
        ]);
        $order->details(json_decode($request->items, true));
        DB::commit();
    }


    public static function returnOut($request)
    {
        DB::beginTransaction();
        $order = self::create([
            'is_return' => true,
            'type' => 'in',
            'date' => $request->date,
            'ownerable_id' => $request->ownerable_id,
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
        ]);
        $order->details(json_decode($request->items, true));

        DB::commit();
    }


    public static function returnIn($request)
    {
        DB::beginTransaction();

        $order = self::create([
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
        ]);
        $order->details(json_decode($request->items, true));
        DB::commit();
    }


    public function details($items)
    {
        foreach ($items as $item) {
            $this->orderDetails()->create([
                'item_id' => $item['item_id'],
                'discount' => $item['discount'],
                'quantity' => $item['quantity'],
                'unite_price' => $item['unite_price'],
                'store_id' => $item['store_id'],
                'load_pending' => true,
                'price_pending' => $item['price_changed'],
            ]);

        }
        $this->postCreate();

    }

    public static function in($request)
    {
        DB::beginTransaction();
        //first create the order
        $order = self::create([
            'mandator_id' => $request->mandator_id,
            'type' => 'in',
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
        ]);
        $order->details(json_decode($request->items, true));
        DB::commit();
    }
}