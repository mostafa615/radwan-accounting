<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Employee;
use Yajra\Datatables\Datatables;

class EmployeeLoansInSalaryController extends Controller
{
    public function index()
    {
        
        if(auth()->user()->branch_id){
            $employees = Employee::where('branch_id',auth()->user()->branch_id)
            ->where("active", '1')
            ->get();
        } else {
            $employees = Employee::where("active", '1')->get();
        }
        return view('reports.employee-loans-in-salary',compact('employees'));
    }

    public function perform(Request $request)
    {
         $query = Salary::
         select('date','loans','employees.name')
         ->leftJoin('employees','employees.id','=','salaries.employee_id')
         ('employee_id',$request->employee_id)
            ->whereBetween('date',[
                $request->from,
                $request->to,
            ]);

            return Datatables::of($query)
            ->editColumn('date',function(Salary $employee){
                return optional($employee->date)->toDateString();
            })
            ->make(true);
    }
}
