<?php
namespace App\Http\Controllers;


use App\Models\Job;

use App\DataTables\JobsDataTable;
use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JobController extends Controller
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
    public function index(JobsDataTable $dataTable)
    {
        return $dataTable->render('jobs.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
            return view('jobs.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $job = Job::create($request->all());
        $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'jobs', $job->id, $this->dateNow, $this->timeNow, null, null );

        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('jobs.index');        
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
    {

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job)
    {
            return view('jobs.edit',[
                'job'=>$job,
        ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job $job)
    {
        $oldData = Job::find($job->id);
        $job->update($request->all());
        
        $newData = $request->all();
        $properties = [
            'old_data' => $oldData,
            'new_data' => $newData
        ];

        $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'jobs', $job->id, $this->dateNow, $this->timeNow, $properties, null );

        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('jobs.index');      
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        $oldData = Job::find($job->id);
        $properties = [
            'old_data' => $oldData,
        ];
        $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'jobs', $job->id, $this->dateNow, $this->timeNow, $properties, null );

        $job->delete();
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('jobs.index');  
    }
}
