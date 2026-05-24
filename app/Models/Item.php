<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    //
    protected $guarded = [];
    protected $fillable = [
        'code', 'price', 'name', 'notes', 'group_id','length','width','first_qnt','quantity','weight','standard_weight','weight_one','used', 'thickness'
    ];

    public function detail()
    {
        return $this->hasOne('App\Models\Detail');
    }

    public function group()
    {
        return $this->belongsTo('App\Models\Group');
    }

    public function quantities()
    {
        return $this->hasMany('App\Models\Quantity')->with('item','ownerable');
    }

}
