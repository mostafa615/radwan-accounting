<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionHistory extends Model
{
    protected $table = "action_histories";

    protected $fillable = [
        'user_id','action' , 'model' , 'date' , 'time', 'notes'
    ];
}
