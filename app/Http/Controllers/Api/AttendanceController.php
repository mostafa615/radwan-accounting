<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Attendance;
use App\Models\AttendanceSettings;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\DefaultAttendanceSetting;
use App\Utils\Util;
use App\Models\MachineSupplies;
use Illuminate\Support\Facades\Auth;
use DB;
class AttendanceController extends Controller
{
    protected $util;
    protected $dateNow;
    protected $timeNow;

    public function __construct(Util $util)
    {
        $this->util = $util;
        $this->dateNow = Carbon::now()->format('Y-m-d');
        $this->timeNow = Carbon::now()->format('H:i:s');
    }
    
    /**
     * Mohamed Maher
     */
    public function supply_data(Request $request)
    {
        try{
    
            if (!$request->machine_id) {
                $html = '<option value="">' . trans('site.items') . '</option>';
            } else {
                $html = '';
    
                $machineSupplies = MachineSupplies::select('id', 'supplie_id', DB::raw("SUM(used) as used"))
                    ->where("machine_id", $request->machine_id)
                    ->where("used", ">", 1)
                    ->groupBy("supplie_id")
                    ->get();
                // dd($machineSupplies);
                foreach ($machineSupplies as $machineSupplie) {
                    // $html .= '<option value="'.$machineSupplie->supplie_id.'"> '.$machineSupplie->name .' - remain use= ' . $machineSupplie->used.' </option>';
                    $html .= '<option value="' . $machineSupplie->id . '"> ' . $machineSupplie->Supplie['name'] . '  : المتبقي  ' . $machineSupplie->used . ' </option>';
                }
            }
    
            return response()->json(['html' => $html],200);
            
        }catch(\Exception $ex){
            return response()->json(['error'=>$ex->getMessage(),'message'=>'There are error occur'],500);
        }
    }
    
    /**
     * Mohamed Maher
     */
    //
    public function store(Request $request)
    {
        $toClearArr = [
            'attendance_time' => ['absence', 'absence_with_permission'],
            'abandonment_time' => ['absence', 'absence_with_permission'],
            'late' => ['absence', 'absence_with_permission', 'late_with_permission', 'absence_with_holiday'],
            'late_with_permission' => ['absence', 'absence_with_permission', 'late', 'absence_with_holiday'],
            //'leave_with_permission' => ['leave_with_permission', 'leave_with_permission', 'leave_with_permission', 'leave_with_permission'],
            'absence' => ['attendance_time', 'abandonment_time', 'absence_with_permission', 'absence_with_holiday', 'late', 'late_with_permission'],
            'absence_with_permission' => ['attendance_time', 'abandonment_time', 'absence', 'absence_with_holiday', 'late', 'late_with_permission'],
            'absence_with_holiday' => ['attendance_time', 'abandonment_time', 'absence', 'absence_with_permission', 'late', 'late_with_permission'],
        ];

        $employee = Employee::findOrFail($request->employee_id);
         
        // first i get on the request 
        // 1- date
        // 2- employee_id
        // 3- payload {field_name} which can be one of the fields in the up of this method

        // then first or create the attendance with the employee_id andv date
        // then update with the field name and its value
        //


        $canGetPerm = DB::table('permission_role')->where('role_id',DB::table('role_user')->where('user_id',$request->auth_user_id)->first()->role_id)
                ->where('permission_id',113)->first();

        if($request->abandonment_time){
            if(Carbon::parse($request->abandonment_time)->format('H') > Carbon::parse(AttendanceSettings::first()->attendance_time->addHour(AttendanceSettings::first()->work_hours))->format('H'))
            {
                if($canGetPerm == null){
                    return response(['notPermission' => true]);
                }
            }
        }

        $attendance = Attendance::where([
            'date' => $request->date,
            'employee_id' => $request->employee_id
        ])->first();

        if (!$attendance){
            $attendance=Attendance::create([
                'date' => $request->date,
                'employee_id' => $request->employee_id
            ]);
            // $this->util->activityLog($request->auth_user_id, 'create', 'accounts', 'attendance', $attendance->id, $this->dateNow, $this->timeNow, null, null );
        }
        
        if ($request->key == 'leave_with_permission') {
            $attendance->update(['leave_with_permission' => $request->value]); 
            return response([
                'done' => true
            ]);
        }

        $attendance->update([
            'abandonment_time' => Carbon::parse($request->abandonment_time)->format('H:i')
        ]);
        $year = Employee::find($request->employee_id)
            ->attendanceSettings()
            ->where('year', Carbon::parse($request->date)->year)
            ->first();
        

        // if ($attendance->absence_with_holiday == 1) {
        //     $year->increment('holidaies_balance');
        // }

        if ($request->key == 'absence_with_holiday') {

            $attendances_with_absence_with_holiday_in_month=Attendance::where([
                'employee_id' => $request->employee_id,
                'absence_with_holiday'=>1
            ])->whereMonth('date',Carbon::parse($request->date)->month)->whereYear('date',Carbon::parse($request->date)->year)->get();
            
            $attendances_with_absence_with_holiday_in_year=Attendance::where([
                'employee_id' => $request->employee_id,
                'absence_with_holiday'=>1
            ])->whereYear('date',Carbon::parse($request->date)->year)->get();
            
            $EmpAttendYear = Employee::find($request->employee_id)
            ->attendanceSettings()
            ->where('year', Carbon::now()->year)
            ->first();

            $allowed_absence_with_holiday_absence = $EmpAttendYear->holidaies_balance;

           
             if (count($attendances_with_absence_with_holiday_in_month) >= 1 && $request->auth_user_id != 1){
                return response([
                    'done' => false
                ]);
            }
            if (count($attendances_with_absence_with_holiday_in_year) >= $allowed_absence_with_holiday_absence){
                return response([
                    'done' => false
                ]);
            } else {
                $attendance->update([$request->key => $request->value]);
                return response([
                    'done' => true
                ]);
            }


        }

        if ($request->key == 'summer_holidays') {
            
          
            $attendances_with_summer_holidays_in_year=Attendance::where([
                'employee_id' => $request->employee_id,
                'summer_holidays'=>1
            ])->whereYear('date',Carbon::parse($request->date)->year)->get();
            
            $EmpAttendYear = Employee::find($request->employee_id)
            ->attendanceSettings()
            ->where('year', Carbon::now()->year)
            ->first();

            $allowed_summer_holidays_absence = $EmpAttendYear->summer_holidays;

            if(!$employee->summer_holiday_permission){
                return response([
                    'done' => false
                ]);
            }
           
            if (count($attendances_with_summer_holidays_in_year) >= $allowed_summer_holidays_absence){
                return response([
                    'done' => false
                ]);
            } else {
                $attendance->update([$request->key => $request->value]);
                return response([
                    'done' => true
                ]);
            }



        }

        if ($request->key == 'emergency_absence') {
            $attendances_emergency_absence=Attendance::where([
                'employee_id' => $request->employee_id,
                'emergency_absence'=>1
            ])->whereYear('date',Carbon::parse($request->date)->year)->get();
            $attendances_emergency_absence_in_month=Attendance::where([
                'employee_id' => $request->employee_id,
                'emergency_absence'=>1
            ])->whereMonth('date',Carbon::parse($request->date)->month)->whereYear('date',Carbon::parse($request->date)->year)->get();

            $allowed_emergency_absence = AttendanceSettings::first()->allowed_emergency_absence;
           
             if (count($attendances_emergency_absence_in_month) >= 1 && $request->auth_user_id != 1){
                return response([
                    'done' => false
                ]);
            }
            if (count($attendances_emergency_absence) >= $allowed_emergency_absence){
                return response([
                    'done' => false
                ]);
            } else {
                $attendance->update([$request->key => $request->value]);
                return response([
                    'done' => true
                ]);
            }
        }

        if ($request->key == 'absence_with_permission') {
          
            $attendances_absence_with_permission=Attendance::where([
                'employee_id' => $request->employee_id,
                'absence_with_permission'=>1
            ])->whereYear('date',Carbon::parse($request->date)->year)->get();
            $allowed_absence = AttendanceSettings::first()->allowed_absence;
           
            if (count($attendances_absence_with_permission) >= $allowed_absence){
               
                return response([
                    'done' => false
                ]);
            } else {
                $attendance->update([$request->key => $request->value]);
                return response([
                    'done' => true
                ]);
            }
        }
        
        


        $attendance->update([$request->key => $request->value]);

        if ($request->key == 'attendance_time') {
            $attendance->update(['real_attendance_time' => Carbon::now()->toTimeString()]);
        }



        if ($request->key == 'abandonment_time') {
            $attendance->update(['real_abandonment_time' => Carbon::now()->toTimeString()]);
        }
        if (isset($toClearArr[$request->key]) && $request->key != 'leave_with_permission') {
            foreach ($toClearArr[$request->key] as $attribute) {
                $attendance->update([$attribute => null]);
            }
        }

        $properties = [
            'request_data' => $request->all()
        ];

        $this->util->activityLog($request->auth_user_id, 'update', 'accounts', 'attendance', $attendance->id, $this->dateNow, $this->timeNow, $properties, null );


        return response([
            'done' => true
        ]);
    }


    public function destroy(Request $request)
    {
        
        $oldData = Attendance::find($request->id);
        $properties = [
            'old_data' => $oldData,
        ];
        $this->util->activityLog($request->auth_user_id, 'delete', 'accounts', 'attendance', $oldData->id, $this->dateNow, $this->timeNow, $properties, null );

        $resource=Attendance::find($request->id);
        if ($resource){
            $year = Employee::find($resource->employee_id)
                ->attendanceSettings()
                ->where('year', Carbon::parse($resource->date)->year)
                ->first();

           
            $resource->delete();
        }

        return response([
            'done' => true,
        ]);
    }
}
