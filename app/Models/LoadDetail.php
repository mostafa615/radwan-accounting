<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoadDetail extends Model
{
    //
    protected $guarded = [];


    public function performPendingLoad()
    {
        // first check if these quantities still exist
        $exist = Quantity::where([
            'ownerable_type' => 'App\Models\Store',
            'ownerable_id' => $this->parent->from_id,
            'item_id' => $this->item_id
        ])->first();
//        dd($exist);
        if ($exist){
//            return true;
            if ($exist->quantity >= $this->quantity) {
                Quantity::where([
                    'ownerable_type' => 'App\Models\Store',
                    'ownerable_id' => $this->parent->from_id,
                    'item_id' => $this->item_id
                ])->decrement('quantity', $this->quantity);

                Quantity::firstOrCreate([
                    'ownerable_type' => 'App\Models\Store',
                    'ownerable_id' => $this->parent->to_id,
                    'item_id' => $this->item_id
                ])->increment('quantity', $this->quantity);

                $this->update(['pending' => false]);

                return true;
            }
        }


        return false;
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Load', 'load_id')->with('from','to');
    }
}
