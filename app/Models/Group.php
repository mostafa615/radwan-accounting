<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    //
    protected $guarded = [];
    protected $fillable = [
        'id', 'name', 'notes', 'price',
        'edit_permission_s', 'edit_permission_q', 'edit_permission_o'
    ];

    public function items()
    {
        return $this->hasMany('App\Models\Item');
    }
    
}
