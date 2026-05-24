<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table='settings';
    protected $fillable=[
        'loan_max_amount',
        'loan_start_date',
        'transport_percent'
    ];
}
