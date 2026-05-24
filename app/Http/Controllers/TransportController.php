<?php

namespace App\Http\Controllers;

use App\Models\Transport;
use App\Models\Employee;
use Illuminate\Http\Request;

use App\DataTables\TransportsDataTable;
use App\Models\Job;
use App\Models\Setting;
use App\Utils\Util;
use Carbon\Carbon;

class TransportController extends Controller
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
    public function index(TransportsDataTable $dataTable)
    {
        return $dataTable->render('transports.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $employees = Employee::latest()->get();
        $jobDriver = Job::where('name', 'سائق')->first();
        if($jobDriver){
            $employees = Employee::where('job_id', $jobDriver->id)->latest()->get();
        }else{
            $employees = Employee::latest()->get();
        }
        return view('transports.create',compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data=$request->all();
        $data['rate']=($request->cost * $request->percent)/100;
        $transport = Transport::create($data);
        $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'transports', $transport->id, $this->dateNow, $this->timeNow, null, null );

        flash('تمت العملية بنجاح')->success();
        return redirect()->route('transports.index');
    }

        
    public function update_setting(Request $request) {
        $this->validate($request, [
            'transport_percent' => 'required|numeric'
        ]);
        $setting = Setting::first();
        if ($setting) {
            $setting->update([
                'transport_percent' => $request->transport_percent
            ]);
        } else {
            Setting::create(['transport_percent' => $request->transport_percent]);
        }
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('transports.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transport  $transport
     * @return \Illuminate\Http\Response
     */
    public function show(Transport $transport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transport  $transport
     * @return \Illuminate\Http\Response
     */
    public function edit(Transport $transport)
    {
        $employees = Employee::latest()->get();
        return view('transports.edit',compact('employees','transport'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transport  $transport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transport $transport)
    {
        $oldData = Transport::find($transport->id);

        $data=$request->all();
        $data['rate']=($request->cost * $request->percent)/100;
        $transport->update($data);
        
        $newData = $request->all();
        $properties = [
            'old_data' => $oldData,
            'new_data' => $newData
        ];

        $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'transports', $transport->id, $this->dateNow, $this->timeNow, $properties, null );

        flash('تمت العملية بنجاح')->success();
        return redirect()->route('transports.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transport  $transport
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transport $transport)
    {
        $oldData = Transport::find($transport->id);
        $properties = [
            'old_data' => $oldData,
        ];
        $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'transports', $transport->id, $this->dateNow, $this->timeNow, $properties, null );

        $transport->delete();
        flash('تمت العملية بنجاح')->success();
        return redirect()->route('transports.index');
    }
}
