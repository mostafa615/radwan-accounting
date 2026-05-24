<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Storage;

use Carbon\Carbon;
use App\Models\DefaultAttendanceSetting;

use App\Models\AttendanceSettings;

class Employee extends Model
{
    //
    protected $guarded = [];
    protected $dates = ['date_of_birth', 'date_of_appointment'];

    protected $appends = ['attend_report'];
    
    public function order() {
        return $this->belongsToMany(Order::class, 'order_employee');
    }
    
    public function loads() {
        return $this->belongsToMany(Load::class, 'load_employee');
    }


    public function getAttendReportAttribute()
    {
        $date_from = Carbon::parse(request()->date_from);                         
        $date_to = Carbon::parse(request()->date_to);                         
        
        // $absenceInYear = $this->attendances()
        //     ->whereYear('date', $date->year)
        //     ->whereMonth('date', $date->month)
        //     ->where('absence', true)->count();


        // $lateWithPermission = $this->attendances()
        //     ->whereBetween('date', [$date_from, $date_to] )
        //     ->where('late_with_permission', true)
        //     ->count();

        // $late = $this->attendances()
        //     ->whereBetween('date', [$date_from, $date_to] )
        //     ->where('late', true)->count();
        $workTime = AttendanceSettings::first();    
        $balance = 0;
        $lateBalance = 0;
        $lateWithPermissionBalance = 0;
        $lates_with_permission_last = 0;
//         $lates = $this->attendances()
//             ->whereBetween('date', [$date_from, $date_to] )
//             ->where('late', true)->get();
       
//         foreach ($lates as $late) {
//             $realAttendanceTime = Carbon::parse($late->attendance_time);
//             $lateHours = Carbon::parse($workTime->attendance_time)
//                 ->diffInMinutes($realAttendanceTime);
// //            return $lateHours;
//             if ($lateHours > 30 ){
//                 $lateHours=$lateHours*3;
//             }else{
//                 $lateHours = 0;
//             }
// //            return $lateHours;
//             $signOutHours = Carbon::parse($workTime->attendance_time)
//                 ->addHour($workTime->work_hours)
//                 ->diffInMinutes(Carbon::parse($late->abandonment_time));
//             $lateBalance += $signOutHours + $lateHours;
//         }
        $lates_with_permission = $this->attendances()
            ->whereBetween('date', [$date_from, $date_to] )
            ->where('late_with_permission', true)
            ->get();

        $lateBalanceLast = 0;

        foreach ($lates_with_permission as $late) {
            $realAttendanceTime = Carbon::parse($late->attendance_time);
            $lateHours = Carbon::parse($workTime->attendance_time)
                ->diffInMinutes($realAttendanceTime);

            $lates_with_permission_last += Carbon::parse($workTime->attendance_time)
            ->diffInMinutes($realAttendanceTime);

            $signOutHours = Carbon::parse($workTime->attendance_time)
                ->addHour($workTime->work_hours)
                ->diffInMinutes(Carbon::parse($late->abandonment_time));
            $lateWithPermissionBalance += $signOutHours + $lateHours;
            $lateBalanceLast += $lateHours;
        }
        $balance = $lateBalance+ ($lateWithPermissionBalance);

        $blalnce = $balance/60;

        $absenceWithHoliday = $this->attendances()
            ->whereBetween('date', [$date_from, $date_to] )
            ->where('absence_with_holiday', true)->count();

        $leaveWithPermission = $this->attendances()
            ->whereBetween('date', [$date_from, $date_to] )
            ->where('leave_with_permission', true)->count();

        $absenceWithPermission = $this->attendances()
            ->whereBetween('date', [$date_from, $date_to] )
            ->where('absence_with_permission', true)->count();


            
        $absenceInMonth = $this->attendances()
            ->whereBetween('date', [$date_from, $date_to] )
            ->where('absence', true)->count();

        // if ($absenceInMonth > 0) {
        //     for ($i = 1; $i <= $absenceInMonth+1; $i++) {
        //         $totalAbsence = $totalAbsence + $i;
        //     }
        //     $totalAbsence = $totalAbsence - 1;
        // }
        $totalAbsence = 0;


        for($iMon=$date_from->month; $iMon<=$date_to->month; $iMon++){

            $absenceL = $this->attendances()
                    ->whereYear('date', $date_from->year)
                    ->whereMonth('date', $iMon)
                    ->where('absence', true)->count();
            
            if ($absenceL > 0) {
                for ($i = 1; $i <= $absenceL+1; $i++) {
                    $totalAbsence = $totalAbsence + $i;
                }
                $totalAbsence = $totalAbsence - 1;
            }
        }

        $lates_with_permission = $this->attendances()
            ->whereBetween('date', [$date_from, $date_to] )
            ->where('absence', 0)->where('absence_with_permission', 0)->where('absence_with_holiday', 0)
            ->where('summer_holidays', 0)->where('emergency_absence', 0)->where('late_with_permission', 0)
            ->where('leave_with_permission', 0)
            ->get();

        $lates_without_permission_last = 0;

        foreach ($lates_with_permission as $late) {
            $realAttendanceTime = Carbon::parse($late->attendance_time);
         
            $lates_without_permission_last += Carbon::parse($workTime->attendance_time)
            ->diffInMinutes($realAttendanceTime);

         
        }

        $leave_with_permission = $this->attendances()
        ->whereBetween('date', [$date_from, $date_to] )
            ->get();

        $leave_without_permission_last = 0;

        foreach ($leave_with_permission as $leave) {
            $realAttendanceTime = Carbon::parse($leave->attendance_time);
            $leave_without_permission_last += Carbon::parse($workTime->attendance_time)
            ->addHour($workTime->work_hours)
            ->diffInMinutes(Carbon::parse($leave->abandonment_time));
           
        }

        
           


        $attendDetails = [
            "absenceWithPermission" => $absenceWithPermission,
            // "absenceInYear" => $absenceInYear,
            "absenceInMonth" => $absenceInMonth,
            "totalAbsence" => $totalAbsence,
            "lates_with_permission_last" => $lates_with_permission_last,
            "lates_without_permission_last" => $lates_without_permission_last,
            // "lateWithPermission" => $lateWithPermission, 
            "late" => $blalnce,
            // "lateBalance" => $lateBalance,
            "absenceWithHoliday" => $absenceWithHoliday,
            "leave_without_permission_last" => $leave_without_permission_last,
        ];
        return $attendDetails;
    }

    public static function boot()
    {
        parent::boot();
        // self::created(function ($employee) {
        //     $employee->createAttendanceSetting();
        // });
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    public function job()
    {
        return $this->belongsTo('App\Models\Job');
    }

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }

    public function attendancesPerMonth($date) {
        $year = Carbon::parse($date)->year;
        $month = Carbon::parse($date)->month;
        $date1 = date("Y-m-d", strtotime($year . "-" . $month . "-" . "01"));
        $date2 = date("Y-m-d", strtotime($year . "-" . $month . "-" . "30"));
        $attendances = Attendance::where("employee_id", $this->id)->
                whereBetween("date", array($date1, $date2))->get();
         
        return count($attendances);  
    }

    public function attendances()
    {
        return $this->hasMany('App\Models\Attendance');
    }

    public function transports()
    {
        return $this->hasMany('App\Models\Transport');
    }

    public function getTransports() {
        return Transport::where("employee_id", $this->id)->sum("rate");
    }

    public function getTransportsSum($date) {
        $employeeIn = Transport::where("employee_id", $this->id)->where('type','in')
                        ->whereMonth('date', Carbon::parse($date)->month)
                        ->whereYear('date', Carbon::parse($date)->year)->sum("rate");
                        
        $employeeOut = Transport::where("employee_id", $this->id)->where('type','out')
                        ->whereMonth('date', Carbon::parse($date)->month)
                        ->whereYear('date', Carbon::parse($date)->year)->sum("rate");
                        
        return $employeeIn - $employeeOut;
    }

    public function loans()
    {
        return $this->hasMany('App\Models\Loan');
    }

    public function attendanceSettings()
    {
        return $this->hasMany('App\Models\DefaultAttendanceSetting');
    }

    public function holidays() {
        return DefaultAttendanceSetting::where("employee_id","=", $this->id)->first()->holidaies_balance;
    }

    public function summerHolidays() {
        return DefaultAttendanceSetting::where("employee_id","=", $this->id)->first()->summer_holidays;
    }
    public function holidaysWithYear($year) {
        return DefaultAttendanceSetting::where("employee_id","=", $this->id)
        ->where('year',$year)->first()->holidaies_balance;
    }
    public function summerHolidaysWithYear($year) {
        return DefaultAttendanceSetting::where("employee_id","=", $this->id)
        ->where('year',$year)->first()->summer_holidays;
    }

    public function salaries()
    {
        return $this->hasMany('App\Models\Salary');
    }


    public function absence($date)
    {
        $totalAbsence = 0;
        $date = Carbon::parse($date);
        $absenceWithPermission = $this->attendances()
            ->whereMonth('date', $date->month)
            ->whereYear('date', $date->year)
            ->where('absence_with_permission', true)->count();

        $emergencyAbsence = $this->attendances()
            ->whereMonth('date', $date->month)
            ->whereYear('date', $date->year)
            ->where('emergency_absence', true)->count();

        $absence = $this->attendances()
            ->whereMonth('date', $date->month)
            ->whereYear('date', $date->year)
            ->where('absence', true)->count();
        if ($absence > 0) {
            for ($i = 1; $i <= $absence+1; $i++) {
                $totalAbsence = $totalAbsence + $i;
            }
            $totalAbsence = $totalAbsence - 1;
        }
        $totalAbsence = $totalAbsence + $absenceWithPermission + $emergencyAbsence;
        return $totalAbsence;
    }


    public function late($date)
    {
//       return 0;
        $date = Carbon::parse($date);
        // first get the days where late
        $balance = 0;
        $lateBalance = 0;
        $lateWithPermissionBalance = 0;
        $leaveWithPermissionBalance = 0;
        $lates = $this->attendances()
            ->whereMonth('date', $date->month)
            ->whereYear('date', $date->year)
            ->where('absence', 0)->where('absence_with_permission', 0)->where('absence_with_holiday', 0)
            ->where('summer_holidays', 0)->where('emergency_absence', 0)
            ->where('late_with_permission', 0)
            ->where('leave_with_permission', 0)
            ->get();
          
        $workTime = AttendanceSettings::first();
        foreach ($lates as $late) {
            $realAttendanceTime = Carbon::parse($late->attendance_time);
            $lateHours = Carbon::parse($workTime->attendance_time)
                ->diffInMinutes($realAttendanceTime);
  
            if ($lateHours > 30 ){
                $lateHours=$lateHours*3;
            }else{
                $lateHours = 0;
            }
            
            if(Carbon::parse($late->abandonment_time)->format('H') < Carbon::parse($workTime->attendance_time)->addHour($workTime->work_hours)->format('H')  ){
            $signOutHours = Carbon::parse($workTime->attendance_time)
                ->addHour($workTime->work_hours)
                ->diffInMinutes(Carbon::parse($late->abandonment_time)) * 3;
                $lateBalance += $signOutHours + $lateHours;
            }else{
                $lateBalance += $lateHours;
            }
            
            
        }
       
                 
//        return $lateBalance;
        $lates_with_permission = $this->attendances()
            ->whereMonth('date', $date->month)
            ->whereYear('date', $date->year)
            ->where('late_with_permission', true)
            ->get();

        foreach ($lates_with_permission as $late) {
            $realAttendanceTime = Carbon::parse($late->attendance_time);
            $lateHours = Carbon::parse($workTime->attendance_time)
                ->diffInMinutes($realAttendanceTime);
            $signOutHours = Carbon::parse($workTime->attendance_time)
                ->addHour($workTime->work_hours)
                ->diffInMinutes(Carbon::parse($late->abandonment_time));
            $lateWithPermissionBalance += $signOutHours + $lateHours;
        }

        $leave_with_permission = $this->attendances()
            ->whereMonth('date', $date->month)
            ->whereYear('date', $date->year)
            ->where('leave_with_permission', true)
            ->get();

        foreach ($leave_with_permission as $late) {
            $realAttendanceTime = Carbon::parse($late->attendance_time);
            $lateHours = Carbon::parse($workTime->attendance_time)
                ->diffInMinutes($realAttendanceTime);
            $signOutHours = Carbon::parse($workTime->attendance_time)
                ->addHour($workTime->work_hours)
                ->diffInMinutes(Carbon::parse($late->abandonment_time));
            $leaveWithPermissionBalance += $signOutHours + $lateHours;
        }
        
        $balance = $lateBalance+ ($lateWithPermissionBalance) + ($leaveWithPermissionBalance);
        
       
            
//        return $balance;
        return ($balance)/60;

    }

    public function overTime($date)
    {
//        return 0;
        $date = Carbon::parse($date);
        // first get the days where late
        $balance = 0;
        $overTimeBalance = 0;
        $lateWithPermissionBalance = 0;
        $attendances = $this->attendances()
            ->whereMonth('date', $date->month)
            ->whereYear('date', $date->year)
            ->where('absence', 0)->where('absence_with_permission', 0)->where('absence_with_holiday', 0)
            ->where('summer_holidays', 0)->where('emergency_absence', 0)
            ->where('leave_with_permission', 0)
            ->get();
          
        $workTime = AttendanceSettings::first();
        
        foreach ($attendances as $attendance) {
            $realLeaveTime = Carbon::parse($workTime->attendance_time)->addHour($workTime->work_hours + 1);
            $abandonmentTime = Carbon::parse($attendance->abandonment_time);
              
                if($realLeaveTime->format('H') < $abandonmentTime->format('H')){

                

                    $overTimeHours = Carbon::parse($attendance->abandonment_time)
                        ->diffInMinutes($realLeaveTime);
                    
                    
        //            return $realovertimehours;
                    if ($overTimeHours > 30 ){
                        $overTimeHours=$overTimeHours*10;
                    }else{
                        $overTimeHours = 0;
                    }
                
                    $overTimeBalance += $overTimeHours;
                }

        }

        $balance = $overTimeBalance;
        //return $balance;
        return ($balance)/60;

    }


    public function calcSalary($date, $net)
    {

        $date = Carbon::parse($date);
        $workDays = AttendanceSettings::first()->work_days;
        $workHours = AttendanceSettings::first()->work_hours;
        $discount_hours= $this->late($date) + ($this->absence($date) * $workHours);
        $hour_cost= $net /( $workDays * $workHours);
//return $discount_hours;
//        return $discount_hours * $hour_cost;
        return $net - ($discount_hours * $hour_cost);

//        return $workDays * $workHours;
//        return $hour_cost;
//        return $discount_hours;
//        return $workHours;
        $perDay = $net / $workDays;
//        return $perDay
        $perHour = $perDay / $workHours;
        $realworkDays = $workDays - $this->absence($date);
        $realWorkHours = ($realworkDays * $workHours) - $this->late($date);
        $finalSalary = ($realWorkHours * $perHour);
       
        return $finalSalary;

    }

    public function createAttendanceSetting()
    {

        $data = DefaultAttendanceSetting::first();
        $this->attendanceSettings()->create([
            'holidaies_balance' => '24',
            'summer_holidays' => '6',
            'year' => Carbon::now()->year,
        ]);
    }


    public function getAttendanceSettingAttribute()
    {
        return $this->attendanceSettings->last();
    }


    public function setIdImage1Attribute($file)
    {
        if (isset($this->attributes['id_image_1'])) {
            Storage::delete($this->attributes['id_image_1']);
        }
        $path = request()->file('id_image_1')->store('public/employees');
        $this->attributes['id_image_1'] = $path;
    }

    public function setIdImage2Attribute($file)
    {
        if (isset($this->attributes['id_image_2'])) {
            Storage::delete($this->attributes['id_image_2']);
        }
        $path = request()->file('id_image_2')->store('public/employees');
        $this->attributes['id_image_2'] = $path;
    }


    public function setAvatarAttribute($file)
    {
        if (isset($this->attributes['avatar'])) {
            Storage::delete($this->attributes['avatar']);
        }
        $path = request()->file('avatar')->store('public/employees');
        $this->attributes['avatar'] = $path;
    }

    public function getIdImage1Attribute()
    {
        if ($this->attributes['id_image_1']) {
            $src = asset(Storage::url($this->attributes['id_image_1']));
        } else {
            $src = asset(Storage::url('default/default.jpg'));
        }
        return $src;
    }

    public function getIdImage2Attribute()
    {
        if ($this->attributes['id_image_2']) {
            $src = asset(Storage::url($this->attributes['id_image_2']));
        } else {
            $src = asset(Storage::url('default/default.jpg'));
        }
        return $src;
    }


    public function getAvatarAttribute()
    {
        if ($this->attributes['avatar']) {
            $src = asset(Storage::url($this->attributes['avatar']));
        } else {
            $src = asset(Storage::url('default/default.jpg'));
        }
        return $src;
    }


    //
    public function setCvAttribute($file)
    {
        if (isset($this->attributes['cv'])) {
            Storage::delete($this->attributes['cv']);
        }
        $path = request()->file('cv')->store('public/employees');
        $this->attributes['cv'] = $path;
    }

    public function setCriminalRecordAttribute($file)
    {
        if (isset($this->attributes['criminal_record'])) {
            Storage::delete($this->attributes['criminal_record']);
        }
        $path = request()->file('criminal_record')->store('public/employees');
        $this->attributes['criminal_record'] = $path;
    }

    // public function getCvAttribute()
    // {
    //     if(isset($this->attributes['criminal_record']))
    //     {
    //      return  Storage::download($this->attributes['criminal_record']);            
    //     }
    //     else return null;
    // }

    // public function getCriminalRecordAttribute()
    // {
    //     if(isset($this->attributes['cv']))
    //     {
    //      return  Storage::download($this->attributes['cv']);            
    //     }
    //     else return null;
    // }

}
