<?php

namespace App\Http\Controllers;

use App\Models\Daily;

use App\Models\Reposite;

use Illuminate\Http\Request;

use App\DataTables\DailyDataTable;

class DailyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DailyDataTable $dataTable)
    {
        return $dataTable->render('daily.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $reposites = Reposite::all();
        if (auth()->user()->id != 1) {
            $reposites = $reposites->where('branch_id', auth()->user()->branch_id);
        }
        return view('daily.create',compact('reposites'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Daily::createDaily($request);
        flash('تمت العملية بنجاح')->success();
        return redirect()->route('daily.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Daily  $daily
     * @return \Illuminate\Http\Response
     */
    public function show(Daily $daily)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Daily  $daily
     * @return \Illuminate\Http\Response
     */
    public function edit(Daily $daily)
    {
        $reposites = Reposite::all();
        return view('daily.edit',compact('reposites','daily'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Daily  $daily
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Daily $daily)
    {
        $daily->updateDaily($request);
        flash('تمت العملية بنجاح')->success();
        return redirect()->route('daily.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Daily  $daily
     * @return \Illuminate\Http\Response
     */
    public function destroy(Daily $daily)
    {
        //
        $daily->deleteDaily();
        flash('تمت العملية بنجاح')->success();
        return redirect()->route('daily.index');

    }
}
