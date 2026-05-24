<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

use App\DataTables\BranchesDataTable;

class BranchController extends Controller
{
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
            Branch::create($request->all());
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
        $branch->update($request->all());
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
        $branch->delete();
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('branch.index');  
    }
}
