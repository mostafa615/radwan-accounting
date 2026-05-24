<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    //
    protected $guarded = [];

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }


    public function quantities()
    {
        return $this->morphMany('App\Models\Quantity', 'ownerable');
    }


    public function orderDetails()
    {
        return $this->hasMany('App\Models\OrderDetail');
    }


    public function loads()
    {
        return $this->hasMany('App\Models\Load','to_id');
    }

    public function scopeOfType($query , $type)
    {
        $query->where('type',$type);
    }
}
