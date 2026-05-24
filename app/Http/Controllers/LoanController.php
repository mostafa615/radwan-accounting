<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Employee;
use App\Models\Reposite;
use App\Models\Daily;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\DataTables\LoansDataTable;
use App\Utils\Util;

class LoanController extends Controller {

    protected $util;
    protected $dateNow;
    protected $timeNow;

    public function __construct(Util $util)
    {
        $this->util = $util;
        $this->dateNow = Carbon::now()->format('Y-m-d');
        $this->timeNow = Carbon::now()->format('H:i:s');
    }
    
    public function update_setting(Request $request) {
        $this->validate($request, [
            'loan_max_amount' => 'required|numeric',
            'loan_start_date' => 'required|numeric'
        ]);
        $setting = Setting::first();
        if ($setting) {
            $setting->update([
                'loan_max_amount' => $request->loan_max_amount,
                'loan_start_date' => $request->loan_start_date
            ]);
        } else {
            Setting::create(['loan_max_amount' => $request->loan_max_amount,
                            'loan_start_date' => $request->loan_start_date]);
        }
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('loans.index');
    }

    public function index(LoansDataTable $dataTable) {

        return $dataTable->render('loans.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $employees = Employee::where('active','1')->get();
        $reposites = Reposite::all();

        if (auth()->user()->id != 1) {
            $employees = $employees->where('branch_id', auth()->user()->branch_id);
            $reposites = $reposites->where('branch_id', auth()->user()->branch_id);
        }

        
        return view('loans.create', compact('employees', 'reposites'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
//        return $request->all();
        if ($request->type == 'solfa') {
            $setting = Setting::first();
            
            /*
            $old_loan = Loan::where('employee_id', $request->employee_id)
                    ->where('type', 'solfa')
                    ->whereMonth('date', Carbon::parse($request->date)->month)
                    ->sum('cost');*/
            $old_loan = Employee::find($request->employee_id)->solaf_balance;
                    
                    
            if ($setting) {
                $date1 = Carbon::createFromFormat('Y-m-d', $this->dateNow)->format('d');
                $date2 = $setting->loan_start_date;
                // dd($date1);
            //    dd($date1->gt($date2));
                if($date2 > $date1){
                    flash('لا يمكن عمل سلفة قبل التاريخ المحدد من الادمن وهو ' . ($setting->loan_start_date))->error();
                    return back();
                }
                if ($setting->loan_max_amount >= ($request->cost + $old_loan)) {
                    Loan::createLoan($request);
                    $properties = [
                        'loan_data' => $request->all()
                    ];
                    $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'loans', $request->employee_id, $this->dateNow, $this->timeNow, $properties, null );

                    // update employee solaf_balance	 
                    Employee::find($request->employee_id)->increment("solaf_balance", $request->cost);
                    
                    flash('تمت العمليه بنجاح')->success();
                } else {
                    flash('مبلغ السلفة لا بد أن يكون أقل من أو يساوي ' . ($setting->loan_max_amount - $old_loan))->error();
                    return back();
                }
            }
        } else {
            // update employee 
            Employee::find($request->employee_id)->increment("madionia_balance", $request->cost);
            $properties = [
                'loan_data' => $request->all()
            ];
            $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'loans', $request->employee_id, $this->dateNow, $this->timeNow, $properties, null );

            Loan::createLoan($request);
            flash('تمت العمليه بنجاح')->success();
        }
        return redirect()->route('loans.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loan $loan
     * @return \Illuminate\Http\Response
     */
    public function show(Loan $loan) {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Loan $loan
     * @return \Illuminate\Http\Response
     */
    public function edit(Loan $loan) {
        $employees = Employee::where('active','1')->get();
        $reposites = Reposite::all();
        return view('loans.edit', [
            'loan' => $loan,
            'employees' => $employees,
            'reposites' => $reposites,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Loan $loan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Loan $loan) {
        if ($loan->type != "solfa") {
            // update employee 
            Employee::find($loan->employee_id)->decrement("madionia_balance", $loan->cost);
            Employee::find($request->employee_id)->increment("madionia_balance", $request->cost);
        }

        $oldData = Loan::find($loan->id);

        $newData = $request->all();
        $properties = [
            'old_data' => $oldData,
            'new_data' => $newData
        ];

        $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'loans', $loan->id, $this->dateNow, $this->timeNow, $properties, null );

        $loan->updateLoan($request);

        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('loans.index');
    }
    
    /**
     * get madionai balance of the employee
     * 
     */
    
    public function getMadionaiBalanceApi(Loan $loan){
        echo Employee::find($loan->employee_id)->madionia_balance;
    }
     
    /**
     * pay the madionia
     * 
     */
    public function payMadionia(Request $request) {
        $modioniaId = $request->madioniaId;
        $cost = $request->cost; 
        $reposite_id = $request->reposite_id; 
        
        // set loan as paid
        $loan = Loan::find($modioniaId);
        //$loan->paid = 1;
        $loan->paid_value += $request->cost;
        
        if ($loan->cost <= $loan->paid_value)
            $loan->paid = 1;
        
        $loan->update();
        
        
        
        // increment cost to reposite
        //Reposite::find($reposite_id)->increment("balance", $cost);
        
        // decrement cost from employee madionia balance
        Employee::find($loan->employee_id)->decrement("madionia_balance", $cost);
        
        // add dialy
        $request->merge([
            "notes" => $request->notes . " " . "تسديد مديونية " . Employee::find($loan->employee_id)->name,
            "tree_id" => "j1_1", 
            "type" => "in",
            "employee_id" => $loan->employee_id 
        ]);
        
        /*
        $request->notes .=" " . "تسديد مديونية " . Employee::find($loan->employee_id)->name;
        $request->tree_id = "j1_1";
        $request->type = "in";  
        $request->employee_id = $loan->employee_id; 
        */
        
        $newData = $request;
        $properties = [
            'data' => $newData
        ];

        $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'pay_loan', $loan->id, $this->dateNow, $this->timeNow, $properties, null );

        Daily::createDaily($request);
        
        // return back
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('loans.index');
    }
    
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loan $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loan $loan) {
        if ($loan->type != "solfa") {
            // update employee 
            Employee::find($loan->employee_id)->decrement("madionia_balance", $loan->cost);
        }else{
            Employee::find($loan->employee_id)->decrement("solaf_balance", $loan->cost);
        }
        $oldData = Loan::find($loan->id);
        $properties = [
            'old_data' => $oldData,
        ];
        $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'loans', $loan->id, $this->dateNow, $this->timeNow, $properties, null );

        
        $loan->deleteLoan();
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('loans.index');
    }

}
