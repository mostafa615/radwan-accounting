<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    // public $table = ' supplies';
    protected $fillable = [
        'name',
        'type',
        'height',
        'width',
        'init_quantity',
        'quantity',
        'used',
        'store_id',
        'description'
    ];
    protected $hidden = ['created_at', 'updated_at'];
}
