<?php

namespace App\Http\Controllers;

use App\Models\DefaultAttendanceSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\DataTables\EmployeesDataTable;

use App\Models\Employee;

use App\Models\Country;

use App\Models\Job;
use App\Models\Branch;
use App\Utils\Util;
use Storage;

class EmployeeController extends Controller
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
    public function index(EmployeesDataTable $dataTable)
    {
        return $dataTable->render('employees.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        $jobs = Job::all();
        $branches = Branch::all();
        return view('employees.create',compact('countries','jobs','branches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $employee=Employee::create($request->except('holidaies_balance', 'summer_holidays'));
        $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'employees', $employee->id, $this->dateNow, $this->timeNow, null, null );

        if ($request->has('holidaies_balance') && $request->holidaies_balance != '' && $request->holidaies_balance > 0 && $request->holidaies_balance != null){
            if ($employee){
                DefaultAttendanceSetting::create([
                    'employee_id'=>$employee->id,
                    'holidaies_balance'=>$request->holidaies_balance,
                    'summer_holidays'=>$request->summer_holidays,
                    'year'=>Carbon::now()->year
                ]);
            }
        }
        flash('تمت العملية بنجاح')->success();
        return redirect()->route('employees.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        
        return view('employees.show',compact('employee'));
    }

    /**
     *  deactive an employee  from all system
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deactive(Employee $employee)
    {
        try {
            $employee->update(["active" => 0]);
            $response = [
                "status" => 1,
                "msg" => "تم الغاء تفعيل الموظف",
            ];
        }catch(\Exception $e) {
            $response = [
                "status" => 0,
                "msg" => "هناك خطا ما",
            ];
        }
        
        return $response;
    }
    
    
    /**
     *  active an employee in all system
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function active(Employee $employee)
    {
        try {
            $employee->update(["active" => 1]);
            $response = [
                "status" => 1,
                "msg" => "تم تفعيل الموظف",
            ];
        }catch(\Exception $e) {
            $response = [
                "status" => 0,
                "msg" => "هناك خطا ما",
            ];
        }
        
        return $response;
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        $holidaies_balance=0;
        $summer_holidays=0;
        $resource=DefaultAttendanceSetting::where([
            'employee_id'=>$employee->id,
            'year'=>Carbon::now()->year
        ])->first();


        if ($resource){
            $holidaies_balance=$resource->holidaies_balance;
            $summer_holidays=$resource->summer_holidays;
        }
        
        $countries = Country::all();
        $jobs = Job::all();
        $branches = Branch::all();
        return view('employees.edit',compact('employee','holidaies_balance','summer_holidays','countries','jobs','branches'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        $oldData = Employee::find($employee->id);
        $request_data = $request->except('holidaies_balance', 'summer_holidays');

        // if ($request_data['summer_holiday_permission'] == 'on' || $request_data['summer_holiday_permission'] == 1)
        //     $request_data['summer_holiday_permission'] = 1;
        // else
        //     $request_data['summer_holiday_permission'] = 0;

        $employee->update($request_data);
        
        $newData = $request->all();
        $properties = [
            'old_data' => $oldData,
            'new_data' => $newData
        ];

        $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'employees', $employee->id, $this->dateNow, $this->timeNow, $properties, null );

        if ($request->has('holidaies_balance') && $request->holidaies_balance != '' && $request->holidaies_balance > 0 && $request->holidaies_balance != null){
            if ($employee){
                $resource=DefaultAttendanceSetting::where([
                    'employee_id'=>$employee->id,
                    'year'=>Carbon::now()->year
                ])->first();
                if ($resource){
                    $resource->update([
                        'holidaies_balance'=>$request->holidaies_balance,
                        'summer_holidays'=>$request->summer_holidays,
                    ]);
                }else{
                    $resource=DefaultAttendanceSetting::create([
                        'employee_id'=>$employee->id,
                        'holidaies_balance'=>$request->holidaies_balance,
                        'summer_holidays'=>$request->summer_holidays,
                        'year'=>Carbon::now()->year
                    ]);
                }
                
                
                // decrement holiday value of employee
                try{
                    $defaultAttandance = DefaultAttendanceSetting::where("employee_id", $employee->id)
                    ->first();
                    $defaultAttandance->update([
                        "holidaies_balance" => $request->holidaies_balance,
                        "summer_holidays" => $request->summer_holidays,
                    ]);
                     
                }catch(\Exception $e){}

            }
        }
        flash('تمت العملية بنجاح')->success();
        return redirect()->route('employees.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $oldData = Employee::find($employee->id);
        $properties = [
            'old_data' => $oldData,
        ];
        $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'employees', $employee->id, $this->dateNow, $this->timeNow, $properties, null );

        $employee->delete();
        flash('تمت العملية بنجاح')->success();
        return redirect()->route('employees.index');
    }


    public function download(Employee $employee , $resourse)
    {
        return Storage::download($employee[$resourse]);
    }

    
}
