<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class AttendanceSettings extends Model
{
    protected $guarded = [];
    // protected $dates = ['attendance_time'];
    // protected $dates = ['end_permited_attend_time'];

    public function getAttendanceTimeAttribute()
    {
        return Carbon::parse($this->attributes['attendance_time']);
    }
}
