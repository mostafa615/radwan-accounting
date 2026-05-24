<?php

namespace App\DataTables;

use App\Models\Attendance;
use App\Models\AttendanceSettings;
use App\Models\Employee;
use Carbon\Carbon;
use Yajra\DataTables\Services\DataTable;

class AttendanceDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function getExist($employee){
        $exist = 0;
        $attendanceObject = $employee->attendances->first();
        $absence = optional($attendanceObject)->absence;
        $absence_with_permission = optional($attendanceObject)->absence_with_permission;
        $leave_with_permission = optional($attendanceObject)->leave_with_permission;
        $late = optional($attendanceObject)->late;
        $late_with_permission = optional($attendanceObject)->late_with_permission;
        $absence_with_holiday = optional($attendanceObject)->absence_with_holiday;
        $summer_holidays = optional($attendanceObject)->summer_holidays;
        $emergency_absence = optional($attendanceObject)->emergency_absence;
        if($absence == 1 || $absence_with_permission == 1 || $leave_with_permission == 1 || $late == 1 || $late_with_permission == 1 || $absence_with_holiday == 1 || $summer_holidays == 1 || $emergency_absence == 1)
            $exist = 1;
        
        return $exist;

    }

    public function dataTable($query)
    {
        $globalDisable = '';
        if (Auth()->user()->id != 1) {
            $reqDate = request()->date ?: Carbon::now()->format('Y-m-d');
            $time = Carbon::now()->format('H:i:s');
            if (AttendanceSettings::count() > 0) {
                if ($time > AttendanceSettings::first()->end_permited_attend_time || $reqDate != Carbon::now()->format('Y-m-d')) {
                    $globalDisable = 'disabled';
                }
            }
        }

        return datatables($query)
        ->addColumn('attendance',function(Employee $employee) use ($globalDisable){
            $attendance_object=$employee->attendances->first();
            $attendance = optional($attendance_object)->attendance_time;
            $id = $employee->id;
            $disable=$globalDisable;
            return view('attendance.datatbles.attendance',compact('id','attendance','disable'))->render();
        })
        ->addColumn('abandonment',function(Employee $employee) use ($globalDisable){
            $abandonment =Carbon::parse('05:00 pm')->format('H:i');
            $abandonment_object=$employee->attendances->last();
            $id = $employee->id;
            $disable=$globalDisable;

            if ($abandonment_object){
                $times = explode(":", $abandonment_object->abandonment_time);
                if (sizeOf($times) >= 2) {
                    $t1 = isset($times[1])? $times[1] : '';
                    $abandonment= $times[0] . ":" . $t1;
                } else {
                    $abandonment = '';
                }
                return view('attendance.datatbles.abandonment',compact('times','id','disable','abandonment'))->render();
            }else{
                return view('attendance.datatbles.abandonment',compact('id','disable','abandonment'))->render();
            }
        })
        ->addColumn('absence',function(Employee $employee) use ($globalDisable){
            $absence_object=$employee->attendances->first();
            $absence = optional($absence_object)->absence;
            $id = $employee->id;
            $disable=$globalDisable;
            if($this->getExist($employee) == 1)
                $disable='disabled';
            return view('attendance.datatbles.absence',compact('id','disable','absence'))->render();
        })
        ->addColumn('absence_with_permission',function(Employee $employee) use ($globalDisable){
            $absenceWithPermission_object=$employee->attendances->first();
            $absenceWithPermission = optional($absenceWithPermission_object)->absence_with_permission;
            $id = $employee->id;
            $disable=$globalDisable;
            if($this->getExist($employee) == 1)
                $disable='disabled';
            return view('attendance.datatbles.absence-with-permission',compact('id','disable','absenceWithPermission'))->render();
        })
        ->addColumn('leave_with_permission',function(Employee $employee) use ($globalDisable){
            $leave_with_permission_object=$employee->attendances->first();
            $leave_with_permission = optional($leave_with_permission_object)->leave_with_permission;
            $id = $employee->id;
            $disable=$globalDisable;
            if($this->getExist($employee) == 1)
                $disable='disabled';
            return view('attendance.datatbles.leave_with_permission',compact('id','leave_with_permission','disable'))->render();
        })
        ->addColumn('late',function(Employee $employee) use ($globalDisable){
            $late_object=$employee->attendances->first();
            $late = optional($late_object)->late;
            $id = $employee->id;
            $disable=$globalDisable;
            if($this->getExist($employee) == 1)
                $disable='disabled';
            return view('attendance.datatbles.late',compact('id','late','disable'))->render();
        })
        ->addColumn('late_with_permission',function(Employee $employee) use ($globalDisable){
            $lateWithPermission_object=$employee->attendances->first();
            $lateWithPermission = optional($lateWithPermission_object)->late_with_permission;
            $id = $employee->id;
            $disable=$globalDisable;
            if($this->getExist($employee) == 1)
                $disable='disabled';
            return view('attendance.datatbles.late-with-permission',compact('id','disable','lateWithPermission'))->render();
        })
        ->addColumn('absence_with_holiday',function(Employee $employee) use ($globalDisable){
            $absenceWithHoliday_object=$employee->attendances->first();
            $absenceWithHoliday = optional($absenceWithHoliday_object)->absence_with_holiday;
            $id = $employee->id;

            $EmpAttendYear = Employee::find($employee->id)
            ->attendanceSettings()
            ->where('year', Carbon::now()->year)
            ->first();

            $disable=$globalDisable;
            if($this->getExist($employee) == 1)
                $disable='disabled';

            $attendAbsenceWithHolidayCount=Attendance::where([
                'employee_id' => $employee->id,
                'absence_with_holiday'=>1
            ])->whereYear('date',Carbon::now()->year)->count();

            $attendYearCount = 0;
            if($EmpAttendYear){
                $attendYearCount= $EmpAttendYear->holidaies_balance - $attendAbsenceWithHolidayCount;
            }
            return view('attendance.datatbles.absence-with-holiday',compact('absenceWithHoliday', 'attendYearCount','disable','id'))->render();
        })
        ->addColumn('summer_holidays',function(Employee $employee) use ($globalDisable){
            $summerHoliday_object=$employee->attendances->first();
            $summerHoliday = optional($summerHoliday_object)->summer_holidays;
            $id = $employee->id;
            
            $disable=$globalDisable;
            if (Auth()->user()->id != 1 && $employee->summer_holiday_permission == 0) {
                $disable = 'disabled';
            }
          
            if($this->getExist($employee) == 1)
                $disable='disabled';
    
            $EmpAttendYear = Employee::find($employee->id)
            ->attendanceSettings()
            ->where('year', Carbon::now()->year)
            ->first();

            $attendSummerHolidaysCount=Attendance::where([
                'employee_id' => $employee->id,
                'summer_holidays'=>1
            ])->whereYear('date',Carbon::now()->year)->count();
           
            $attendYearCount = 0;
            if($EmpAttendYear){
                $attendYearCount= $EmpAttendYear->summer_holidays - $attendSummerHolidaysCount;
            }

            return view('attendance.datatbles.summer-holiday',compact('summerHoliday', 'attendYearCount','disable','id'))->render();
        })
        ->addColumn('emergency_absence',function(Employee $employee) use ($globalDisable){
            $absenceEmergency_object=$employee->attendances->first();
            $absenceEmergency = optional($absenceEmergency_object)->emergency_absence;
            $id = $employee->id;

            $disable=$globalDisable;
            if($this->getExist($employee) == 1)
                $disable='disabled';

            $attendEmergencyCount=Attendance::where([
                'employee_id' => $employee->id,
                'emergency_absence'=>1
            ])->whereYear('date',Carbon::now()->year)->count();

            $availableEmergencyCount = AttendanceSettings::first()->allowed_emergency_absence - $attendEmergencyCount;
          
            return view('attendance.datatbles.absence-emergency',compact('absenceEmergency', 'availableEmergencyCount','disable','id'))->render();
        })
        ->addColumn('action',function(Employee $employee) use ($globalDisable){
            $attendance_object=$employee->attendances->first();
            $attendance = optional($attendance_object)->id;
            $disable=$globalDisable;
            return view('attendance.datatbles.actions',compact('attendance','disable'))->render();
        })
        ->rawColumns(['attendance','abandonment','absence','absence_with_permission','late_with_permission','late','absence_with_holiday', 'summer_holidays', 'emergency_absence','action', 'leave_with_permission']);
    }


    public function query(Employee $model)
    {
        \Debugbar::info(request()->all());
        \Debugbar::info(request()->date);
        
        $reqDate = request()->date;
        if($reqDate) {
            $setting = AttendanceSettings::first();
            if ($setting) {
                $timeNow = Carbon::now()->format('H:i:s');
                $isToday = $reqDate == Carbon::now()->format('Y-m-d');
                $isPastDay = Carbon::parse($reqDate)->startOfDay() < Carbon::now()->startOfDay();
            }
        }

        $query = $model
        ->with([
            'attendances'=>function($query){
                $query->whereDate('date',request()->date);
            }
        ])
        ->select(
        'employees.id',
        'employees.name as employee',
        'jobs.name as job',
        'employees.summer_holiday_permission',
        'employees.created_at'
        )
        ->leftJoin('jobs','employees.job_id','=','jobs.id')
        ->where('active','1')
        ->latest();


        if(auth()->user()->id != 1){
            $query = $query->where('branch_id',auth()->user()->branch_id); 
        } 

        if (count($query->get()) <= 0)
            return [];
        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->columns($this->getColumns())
                    ->ajax([
                        'data'=>'function(data){
                            data.date = $date.val();
                        }'
                    ])
                    ->parameters($this->getBuilderParameters());
    }

     
    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            [
                'name'=>'employees.name',
                'data'=>'employee',
                'title'=>'الموظف',                
            ],
            [
                'name'=>'jobs.name',
                'data'=>'job',
                'title'=>'الوظيفة',                
            ], 
            [
                'name'=>'attendance',
                'data'=>'attendance',
                'title'=>'الحضور',  
                'orderable'=>false,
                'searchable'=>false,              
            ], 
            [
                'name'=>'abandonment',
                'data'=>'abandonment',
                'title'=>'الانصراف', 
                'orderable'=>false,
                'searchable'=>false,               
            ], 
            [
                'name'=>'leave_with_permission',
                'data'=>'leave_with_permission',
                'title'=>'انصراف  باذن',                
                'orderable'=>false,
                'searchable'=>false,
            ], 
            [
                'name'=>'late_with_permission',
                'data'=>'late_with_permission',
                'title'=>'تاخير  باذن',                
                'orderable'=>false,
                'searchable'=>false,
            ], 
            // [
            //     'name'=>'late',
            //     'data'=>'late',
            //     'title'=>'تاخير بدون إذن ',
            //     'orderable'=>false,
            //     'searchable'=>false,              
            // ], 
            [
                'name'=>'absence_with_permission',
                'data'=>'absence_with_permission',
                'title'=>'غياب  باذن',                
                'orderable'=>false,
                'searchable'=>false,
            ], 
            [
                'name'=>'absence',
                'data'=>'absence',
                'title'=>'غ بدون اذن',  
                'orderable'=>false,
                'searchable'=>false,              
            ],
            [
                'name'=>'absence_with_holiday',
                'data'=>'absence_with_holiday',
                'title'=>'غ رصيد',  
                'orderable'=>false,
                'searchable'=>false,              
            ], 
            [
                'name'=>'summer_holidays',
                'data'=>'summer_holidays',
                'title'=>'مصيف',  
                'orderable'=>false,
                'searchable'=>false,              
            ], 
            [
                'name'=>'emergency_absence',
                'data'=>'emergency_absence',
                'title'=>'عارضة',  
                'orderable'=>false,
                'searchable'=>false,              
            ], 
            [
                'name'=>'action',
                'data'=>'action',
                'title'=>'عمليات',   
                'exportable' => false,
                'printable' => false,
                'searchable' => false,
                'orderable' => false,
            ], 
        ];
    }


    /**
    *Get the builder parameters
    *@return array
    */
    public function getBuilderParameters()
    {
        return [
            'dom' => 'Bfrtip',
            'buttons' => ['excel', 'print', 'reset', 'reload'],
            'lengthMenu' => [100, 200],
            'language' => [
                      'url' => url('/vendor/datatables/arabic.json')
            ],
            // 'filter' => true,
            // 'order' => [ [0,'desc'] ],
            // 'lengthMenu' => [10,25,50]
            
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Attendance_' . date('YmdHis');
    }
}
