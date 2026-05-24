<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    //
    protected $guarded = [];
   
    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }
}
