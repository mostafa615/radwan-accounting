<?php

namespace App\Http\Controllers\Api;

use App\Models\Loan;
use App\Models\Reposite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AttendanceSettings;

use DB;
use App\Models\Employee;

use Carbon\Carbon;

use App\Models\Salary;
use App\Utils\Util;

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
    
    public function performSave(Request $request)
    {

        
        $date = Carbon::parse($request->date);
        $employee = Employee::find($request->employee_id);
        $employee->update([
            'salary' => $request->basic
        ]);
        $solaf = Loan::where('type', 'solfa')
            ->where('paid', 0)
            ->where('employee_id', $request->employee_id)
            ->whereMonth('date', Carbon::parse($request->date)->month)
            ->get();
        $madionia = Loan::where('type', 'madionia')
            ->where('paid', 0)
            ->where('employee_id', $request->employee_id)
            ->whereMonth('date', Carbon::parse($request->date)->month)
            ->get();

        $employee->decrement('madionia_balance', $request->madionia);

        $mad = Loan::query()
            ->where('employee_id', $employee->id)
            ->where('paid', 0)
            ->whereRaw('paid_value < cost')
            ->where('type', 'madionia')
            ->first();

        if ($employee->madionia_balance == 0) {
            if (sizeOf($madionia) > 0) {
                $m = $madionia[0];
                $m->paid = 1;
                $m->update();
            }
        }

        if ($mad) {
            $mad->increment('paid_value', $request->madionia);
        }

        $employee->decrement('solaf_balance',  $request->loans);
        if ($employee->solaf_balance == 0) {
            foreach ($solaf as $item) {
                $item->paid = 1;
                $item->update();
            }
        }

        DB::statement("update transports set rate='0' where employee_id='$employee->id' ");

        $workDays = AttendanceSettings::first()->work_days;
        $workHours = AttendanceSettings::first()->work_hours;
        $hour_cost = $employee->salary / ($workDays * $workHours);
        $overTimeCost = round($hour_cost * $employee->overTime($request->date),2);
       

        $net = $employee->calcSalary($request->date, $request->basic)
            - ($request->loans + $request->madionia + $request->financial_penalties + $request->insurance)
            + $request->bonus + $request->transports + $overTimeCost;

        $net = floor($net);


        $salary = $employee->salaries()
            ->whereMonth('date', $date->month)
            ->whereYear('date', $date->year)
            ->first();
        if (!$salary) {
            $salary = Salary::create([
                'employee_id' => $request->employee_id,
                'notes' => $request->notes,
                'date' => $request->date,
            ]);
        } else {
            $reposite = Reposite::where('id', $salary->reposite_id)->first();
            if ($reposite) {
                $reposite->increment('balance', $salary->net);
            }
        }

        $reposite = Reposite::where('id', $request->reposite_id)->first();
        if ($reposite) {
            $reposite->decrement('balance', $net);
        }
        $salary->update([
            'basic' => $request->basic,
            'loans' => $request->loans,
            'madionia' => $request->madionia,
            'insurance' => $request->insurance,
            'bonus' => $request->bonus,
            'notes' => $request->notes,
            'financial_penalties' => $request->financial_penalties,
            'net' => $net,
            'transports' => $request->transports,
            'reposite_id' => $request->reposite_id
        ]);

        $requestData = $request->all();
        $properties = [
            'reposite' => $reposite,
            'salary' => $salary,
            'request_data' => $requestData
        ];

        $this->util->activityLog(auth()->user()->id ?? '', 'update', 'accounts', 'salary-perform', optional($salary)->id, $this->dateNow, $this->timeNow, $properties, null );

        $salary->notes = $request->notes;

        DB::commit();
        //        return $net;
        return response([
            'done' => true,
        ]);
        // first look for the record 
        // then if it was found get the loans value and increase it to the employee
        // then save / update
        // then get the loans and decrease it from the employee
        try {
            if ($request->salary_id) {
                $salary = Salary::find($request->salary_id);
                $salary->update([
                    // 'salary' => $request->salary,
                    'insurance' => $request->insurance,
                    'bonus' => $request->bonus,
                    'financial_penalties' => $request->financial_penalties
                ]);
            } else {
                $salary = Salary::create([
                    'employee_id' => $request->employee_id,
                    'date' => $request->date,
                ]);
            }
        } catch (\Exception $e) {
        }
        return 1;
    }

     /**
     * reset the salary of employee 
     */

    public function resetSalary(Request $request)
    {
        dd('hamooo');
        try {
            if ($request->salary_id) {
                $salary = Salary::find($request->salary_id);
                $salary->update([
                    // 'salary' => $request->salary,
                    'insurance' => $request->insurance,
                    'bonus' => $request->bonus,
                    'financial_penalties' => $request->financial_penalties
                ]);
            } 
        } catch (\Exception $e) {
        }
        return 1;
    }

    /**
     * save the bonus of employee 
     */
    public function saveBonus(Request $request)
    {
        try {
            if ($request->salary_id) {
                $salary = Salary::find($request->salary_id);
                $salary->update([
                    // 'salary' => $request->salary,
                    'insurance' => $request->insurance,
                    'bonus' => $request->bonus,
                    'financial_penalties' => $request->financial_penalties
                ]);
            } else {
                $salary = Salary::create([
                    'employee_id' => $request->employee_id,
                    'date' => $request->date,
                ]);
            }
        } catch (\Exception $e) {
        }
        return 1;
    }
   
}
