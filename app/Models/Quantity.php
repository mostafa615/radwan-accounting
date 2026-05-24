<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quantity extends Model
{
    //
    protected $guarded = [];

    public function ownerable()
    {
        return $this->morphTo();
    }
    
    public function item()
    {
        return $this->belongsTo('App\Models\Item');
    }

    public function store()
    {
        return $this->belongsTo('App\Models\Store');
    }

}
