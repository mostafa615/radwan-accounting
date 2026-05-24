<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Employee;

use Carbon\Carbon;

class EmployeeLoanController extends Controller
{
    //
    public function index(Request $request)
    {
        $date = Carbon::parse($request->date);
        $employee = Employee::find($request->id);
        $loans = $employee
        ->loans()
        ->whereBetween('date',[
            $request->from,
            $request->to
        ])
        ->sum('cost');
        return response()->json(compact('loans'));
    }

}
