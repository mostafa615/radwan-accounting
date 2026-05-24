<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //

    protected $guarded = [];
    protected $dates = ['date'];
    public static function performCreate($request)
    {
        self::create([
            'cost'=>$request->cost,
            'date'=>$request->date,
            'user_id'=>auth()->user()->id,
            'from_id'=>auth()->user()->reposite->id,
            'to_id'=>$request->to_id,
            'notes'=>$request->notes,
        ]);
    }

    public function from()
    {
        return $this->belongsTo('App\Models\Reposite','from_id');
    }

    public function to()
    {
        return $this->belongsTo('App\Models\Reposite','to_id');
    }


    public function performPending()
    {
        if($this->from->balance >= $this->cost){
            $this->to()->increment('balance',$this->cost);
            $this->from()->decrement('balance',$this->cost);
            $this->update(['pending'=>false]);
            return true;
        }
        return false;
        
    }
}
