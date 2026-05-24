<?php

namespace App\Models;

use App\Models\Quantity;
use Illuminate\Database\Eloquent\Model;
use DB;

class Load extends Model
{
    protected $guarded = [];
    protected $dates = ['date'];
    
    public function sendEmployees() {
        return $this->belongsToMany(Employee::class, 'load_employee')->where('type', 'send');
    }

    public function receiveEmployees() {
        return $this->belongsToMany(Employee::class, 'load_employee')->where('type', 'receive');
    }

    public static function make($request)
    {
        DB::beginTransaction();
        $load = self::create([
            'user_id' => auth()->user()->id,
            'date' => $request->date,
            'from_id' => $request->from_id,
            'to_id' => $request->to_id,
            'notes' => $request->notes,
        ]);
        if (!empty(json_decode($request->items, true))) {
            foreach (json_decode($request->items, true) as $item) {
                $load->loadDetails()->create(
                    [
                        'item_id' => $item['item_id'],
                        'quantity' => $item['quantity']
                    ]
                );
                if (Auth()->user()->id == 1) {
                    $quantity_from = Quantity::where('item_id', $item['item_id'])
                        ->where('ownerable_id', $request->from_id)
                        ->where('ownerable_type', 'App\Models\Store')->first();
                    $quantity_to = Quantity::where('item_id', $item['item_id'])
                        ->where('ownerable_id', $request->to_id)
                        ->where('ownerable_type', 'App\Models\Store')->first();
                    if ($quantity_from) {
                        $quantity_from->decrement('quantity', $item['quantity']);
                    }
                    if ($quantity_to) {
                        $quantity_to->increment('quantity', $item['quantity']);
                    } else {
                        Quantity::create([
                            'ownerable_id' => $request->to_id,
                            'ownerable_type' => 'App\Models\Store',
                            'item_id' => $item['item_id'],
                            'quantity' => $item['quantity']
                        ]);
                    }
                }
            }
        } else {
            flash()->error('اختر الاصناف من فضلك');
            return redirect()->back();
        }
        DB::commit();

    }


    public function loadDetails()
    {
        return $this->hasMany('App\Models\LoadDetail')->with('item');
    }

    public function deleteLoad()
    {
        DB::beginTransaction();
        $items = $this->loadDetails()->where('pending', false)->select('item_id', 'quantity')->get();
        $load = $this;
        foreach ($items as $item) {
            Quantity::where([
                'ownerable_id' => $load->from_id,
                'ownerable_type' => 'App\Models\Store',
                'item_id' => $item['item_id'],
            ])->increment('quantity', $item['quantity']);

            Quantity::where([
                'ownerable_id' => $load->to_id,
                'ownerable_type' => 'App\Models\Store',
                'item_id' => $item['item_id'],
            ])->decrement('quantity', $item['quantity']);
        }

        $this->loadDetails->each->delete();
        $this->delete();

        DB::commit();

    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    
    public function sender()
    {
        return $this->belongsTo('App\Models\User', 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo('App\Models\User', 'receiver_id');
    }

    public function from()
    {
        return $this->belongsTo('App\Models\Store', 'from_id');
    }


    public function to()
    {
        return $this->belongsTo('App\Models\Store', 'to_id');
    }
}
