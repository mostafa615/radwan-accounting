<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    public $table = 'machines';
    protected $fillable = [
        'name',
        'type',
        'store_id',
        'description'
    ];
    protected $hidden = ['created_at', 'updated_at'];
}
