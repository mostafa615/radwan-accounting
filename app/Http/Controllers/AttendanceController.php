<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;

use App\DataTables\AttendanceDataTable;

use App\Models\AttendanceSettings;
use App\Utils\Util;
use Carbon\Carbon;

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AttendanceDataTable $dataTable)
    {
        $setting = AttendanceSettings::first();
        return $dataTable->render('attendance.index',compact('setting'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::all();
        return view('attendance.create',compact('employees'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attendance = Attendance::create($request->all());
        $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'attendance', $attendance->id, $this->dateNow, $this->timeNow, null, null );

        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('attendance.index');        
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
            $employees = Employee::all();
            return view('attendance.edit',[
                'attendance'=>$attendance,
                'employees'=>$employees,
        ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        $oldData = Attendance::find($attendance->id);
        $newData = $request->all();
        $properties = [
            'old_data' => $oldData,
            'new_data' => $newData
        ];

        $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'attendance', $attendance->id, $this->dateNow, $this->timeNow, $properties, null );

        $attendance->update($request->all());
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('attendance.index');      
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        $oldData = Attendance::find($attendance->id);
        $properties = [
            'old_data' => $oldData,
        ];
        $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'attendance', $attendance->id, $this->dateNow, $this->timeNow, $properties, null );

        $attendance->delete();
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('attendance.index');  
    }
}
