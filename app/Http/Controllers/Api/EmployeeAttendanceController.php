<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Employee;

use Carbon\Carbon;

class EmployeeAttendanceController extends Controller
{
    //
    public function index(Request $request)
    {
        $date = Carbon::parse($request->date);
        $employee = Employee::find($request->id);
        $absence = $employee
        ->attendances()
        ->whereBetween('date',[
            $request->from,
            $request->to
        ])
        ->where('absence',true)
        ->count();

        $absenceWithPermission = $employee
        ->attendances()
        ->whereBetween('date',[
            $request->from,
            $request->to
        ])
        ->where('absence_with_permission',true)
        ->count();

        $presence = $employee
        ->attendances()
        ->whereBetween('date',[
            $request->from,
            $request->to
        ])
        ->whereNotNull('attendance_time')
        ->whereNotNull('abandonment_time')
        ->count();

        return response()->json(compact('absence','absenceWithPermission','presence'));
    }
}
