<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

use App\DataTables\BranchesDataTable;
use App\Utils\Util;
use Carbon\Carbon;

class BranchController extends Controller
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
    public function index(BranchesDataTable $dataTable)
    {
        return $dataTable->render('branches.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
            return view('branches.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $branch = Branch::create($request->all());
        $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'branches', $branch->id, $this->dateNow, $this->timeNow, null, null );
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('branch.index');        
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function show(Branch $branch)
    {

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit(Branch $branch)
    {
            return view('branches.edit',[
                'branch'=>$branch,
        ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Branch $branch)
    {
        $oldData = Branch::find($branch->id);
        $branch->update($request->all());

        $newData = $request->all();
        $properties = [
            'old_data' => $oldData,
            'new_data' => $newData
        ];

        $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'branches', $branch->id, $this->dateNow, $this->timeNow, $properties, null );

        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('branch.index');      
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Branch $branch)
    {
        flash('عزيزي الادمن لا يمكن حذف الفرع بالرجاء التوجه الي الدعم')->error();
        return redirect()->route('branch.index');

        // $oldData = Branch::find($branch->id);
        // $properties = [
        //     'old_data' => $oldData,
        // ];
        // $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'branches', $branch->id, $this->dateNow, $this->timeNow, $properties, null );

        // $branch->delete();
        // flash('تمت العمليه بنجاح')->success();
        // return redirect()->route('branch.index');  
    }
}
