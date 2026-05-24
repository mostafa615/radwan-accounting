<?php

namespace App\Http\Controllers;


use App\Models\Salary;
use App\Models\Employee;
use App\Models\Reposite;
use Illuminate\Http\Request;

use App\DataTables\SalaryDataTable;
use App\Utils\Util;
use Carbon\Carbon;

class SalaryController extends Controller
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
    public function index(SalaryDataTable $dataTable)
    {
        return $dataTable->render('salary.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::all();
        $reposites = Reposite::all();
        return view('salary.create',compact('employees','reposites'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Salary::createSalary($request);
        $properties = [
            'data' => $request->all()
        ];
        $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'salary', $request->employee_id, $this->dateNow, $this->timeNow, $properties, null );

        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('salary.index');        
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function show(Salary $salary)
    {
        return view('salary.show',compact('salary'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function edit(Salary $salary)
    {
        $employees = Employee::all();
        $reposites = Reposite::all();
        return view('salary.edit',[
                'salary'=>$salary,
                'employees'=>$employees,
                'reposites'=>$reposites,
        ]);

    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Salary $salary)
    {
        $salary->updateSalary($request);
        $salary->notes = $request->notes;
        
        $oldData = Salary::find($salary->id);

        $newData = $request->all();
        $properties = [
            'old_data' => $oldData,
            'new_data' => $newData
        ];

        $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'salary', $salary->id, $this->dateNow, $this->timeNow, $properties, null );

        $salary->update();
        
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('salary.index');      
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function destroy(Salary $salary)
    {        
        $oldData = Salary::find($salary->id);
        $properties = [
            'old_data' => $oldData,
        ];
        $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'salary', $salary->id, $this->dateNow, $this->timeNow, $properties, null );

        $salary->deleteSalary();
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('salary.index');  
    }
}
