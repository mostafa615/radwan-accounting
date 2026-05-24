<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Employee;
use App\Models\Branch;
use App\Models\Attendance;
use Yajra\Datatables\Datatables;
use App\Models\AttendanceSettings;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    //
    public function index()
    {
        $employees = Employee::where('employees.active', '1')->get();
        $branches = Branch::where('display',1)->get();
        return view('reports.attendance.index',compact('employees','branches'));
    }

    public function detailed(Request $request)
    {
        $query = Attendance::select(
            'date',
            'attendance_time',
            'abandonment_time',
            'absence',
            'absence_with_permission',
            'late',
            'late_with_permission',
            'leave_with_permission',
            'absence_with_holiday',
            'summer_holidays',
            'emergency_absence',
            'employees.id',
            'employees.name as employee',
            'jobs.name as job',
            'employees.created_at',
            'employees.branch_id as branch'
            )
            ->leftJoin('employees','employees.id','=','attendances.employee_id')
            ->where('employees.active', '1')
            ->leftJoin('jobs','employees.job_id','=','jobs.id')
            ->whereBetween('date',[
                $request->from,
                $request->to
            ]);
        
            if($request->branch_id){
                $query->where('branch_id', $request->branch_id);
            }
            if($request->employee_id){
                $query->where('employees.id', $request->employee_id);
            }

            return Datatables::of($query)
            ->editColumn('date',function(Attendance $attendance){
                return optional($attendance->date)->toDateString();
            })
            ->editColumn('branch_id',function(Attendance $attendance){
                return optional($attendance->branch_id)->toDateString();
            })
            ->editColumn('absence',function(Attendance $attendance){
                return view('reports.attendance.datatable',[
                    'status'=>$attendance->absence,
                ]);
            })
            ->editColumn('absence_with_permission',function(Attendance $attendance){
                return view('reports.attendance.datatable',[
                    'status'=>$attendance->absence_with_permission,
                ]);
            })
            ->editColumn('leave_with_permission',function(Attendance $attendance){
                return view('reports.attendance.datatable',[
                    'status'=>$attendance->leave_with_permission,
                ]);
            })
            ->editColumn('late_with_permission',function(Attendance $attendance){
                return view('reports.attendance.datatable',[
                    'status'=>$attendance->late_with_permission,
                ]);
            })
            ->editColumn('absence_with_holiday',function(Attendance $attendance){
                return view('reports.attendance.datatable',[
                    'status'=>$attendance->absence_with_holiday,
                ]);
            })
            ->editColumn('summer_holidays',function(Attendance $attendance){
                return view('reports.attendance.datatable',[
                    'status'=>$attendance->summer_holidays,
                ]);
            })
            ->editColumn('emergency_absence',function(Attendance $attendance){
                return view('reports.attendance.datatable',[
                    'status'=>$attendance->emergency_absence,
                ]);
            })
            ->addColumn('over_time',function(Attendance $attendance){
                $workTime = AttendanceSettings::first();
                $abandonmentTime = Carbon::parse($attendance->abandonment_time);
                $realLeaveTime = Carbon::parse($workTime->attendance_time)->addHour($workTime->work_hours + 1);
                $abandonmentTime = Carbon::parse($attendance->abandonment_time);
                $status = 0;
                if($realLeaveTime->format('H') < $abandonmentTime->format('H')){
                    $overTimeHours = Carbon::parse($attendance->abandonment_time)
                        ->diffInMinutes($realLeaveTime);
                    if ($overTimeHours > 30 )
                        $status = 1;
                  
                }
                return view('reports.attendance.datatable',[
                    'status'=>$status,
                ]);
            })
            ->addColumn('late',function(Attendance $attendance){
            
            $workTime = AttendanceSettings::first();
         
            $realAttendanceTime = Carbon::parse($attendance->attendance_time);
            $lateHours = Carbon::parse($workTime->attendance_time)
                ->diffInMinutes($realAttendanceTime);


            if(!$attendance->absence && !$attendance->absence_with_permission && !$attendance->absence_with_holiday && !$attendance->summer_holidays && !$attendance->emergency_absence && !$attendance->late_with_permission && !$attendance->leave_with_permission) {
                if ($lateHours > 30) {
                    return view('reports.attendance.datatable', [
                        'status'=>1,
                    ]);
                } else {
                    return view('reports.attendance.datatable', [
                        'status'=>0,
                    ]);
                }
            }else{
                return view('reports.attendance.datatable', [
                    'status'=>0,
                ]);
            }
            

            })

            
            ->rawColumns(['absence','absence_with_permission', 'leave_with_permission', 'late_with_permission','absence_with_holiday','summer_holidays','emergency_absence','over_time','late'])
            ->make(true);


    }


    public function abstracted(Request $request)
    {
        $query = Employee::
        select('employees.id','employees.name as employee','jobs.name as job','employees.created_at','employees.branch_id as branch')
        ->leftJoin('jobs','jobs.id','=','employees.job_id')
        ->where('employees.active', '1')
        ->latest();


        if($request->employee_id){
            $query->where('employees.id', $request->employee_id);
        }

        if($request->branch_id){
            $query->where('branch_id', $request->branch_id);
        }

        if(!$request->from){
            $query->where('employees.id', 0);
        }

        if($request->branch_id){
                $query->where('branch_id', $request->branch_id);
            }

        return Datatables::of($query)
        ->addColumn('attendance',function(Employee $employee) use($request) {
           return  $employee->attendances()->whereBetween('date',[
                $request->from,
                $request->to,
            ])
            ->whereNotNull('attendance_time')
            ->whereNotNull('abandonment_time')
            ->count();

        })
        ->addColumn('absence',function(Employee $employee) use($request) {
           return $employee->attendances()->whereBetween('date',[
                $request->from,
                $request->to,
            ])
            ->where('absence',true)
            ->count();
        })
        ->addColumn('absence_with_permission',function(Employee $employee) use($request) {
           return $employee->attendances()->whereBetween('date',[
                $request->from,
                $request->to,
            ])
            ->where('absence_with_permission',true)
            ->count();
        })
        ->addColumn('late',function(Employee $employee) use($request) {
           return $employee->attendances()->whereBetween('date',[
                $request->from,
                $request->to,
            ])
            ->where('late',true)
            ->count();
        })
        ->addColumn('late_with_permission',function(Employee $employee) use($request) {
           return $employee->attendances()->whereBetween('date',[
                $request->from,
                $request->to,
            ])
            ->where('late_with_permission',true)
            ->count();
        })
        ->addColumn('leave_with_permission',function(Employee $employee) use($request) {
           return $employee->attendances()->whereBetween('date',[
                $request->from,
                $request->to,
            ])
            ->where('leave_with_permission',true)
            ->count();
        })
        ->addColumn('absence_with_holiday',function(Employee $employee) use($request) {
            return $employee->attendances()->whereBetween('date',[
                 $request->from,
                 $request->to,
             ])
             ->where('absence_with_holiday',true)
             ->count();
         })
         ->addColumn('summer_holidays',function(Employee $employee) use($request) {
            return $employee->attendances()->whereBetween('date',[
                 $request->from,
                 $request->to,
             ])
             ->where('summer_holidays',true)
             ->count();
         })
         ->addColumn('emergency_absence',function(Employee $employee) use($request) {
            return $employee->attendances()->whereBetween('date',[
                 $request->from,
                 $request->to,
             ])
             ->where('emergency_absence',true)
             ->count();
         })
         ->addColumn('over_time',function(Employee $employee) use($request) {
            $attendances = $employee->attendances()->whereBetween('date',[
                 $request->from,
                 $request->to,
             ])
             ->where('absence', 0)->where('absence_with_permission', 0)->where('absence_with_holiday', 0)
            ->where('summer_holidays', 0)->where('emergency_absence', 0)
            ->where('leave_with_permission', 0)
            ->get();
            $workTime = AttendanceSettings::first();
            $over_time_count = 0;
            foreach ($attendances as $attendance) {
                $abandonmentTime = Carbon::parse($attendance->abandonment_time);
                $realLeaveTime = Carbon::parse($workTime->attendance_time)->addHour($workTime->work_hours + 1);
                $abandonmentTime = Carbon::parse($attendance->abandonment_time);
                if($realLeaveTime->format('H') < $abandonmentTime->format('H')){
                    $overTimeHours = Carbon::parse($attendance->abandonment_time)
                        ->diffInMinutes($realLeaveTime);
                    if ($overTimeHours > 30 )
                        $over_time_count += 1;
                  
                }
            }
            return $over_time_count;
         })
         
        ->make(true);
    }
}
