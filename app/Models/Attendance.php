<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $guarded = [];
    protected $dates = ['date'];
    protected $table='attendances';
    protected $fillable=[
        'employee_id', 'date', 'attendance_time', 'real_attendance_time',
        'abandonment_time', 'real_abandonment_time', 'absence', 'absence_with_permission', 'emergency_absence',
        'absence_with_holiday', 'summer_holidays', 'late', 'late_with_permission', 'leave_with_permission'
    ];

}
