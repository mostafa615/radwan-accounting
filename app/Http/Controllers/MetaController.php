<?php

namespace App\Http\Controllers;

use App\Models\Meta;
use Illuminate\Http\Request;
use App\DataTables\MetaDataTable;
class MetaController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MetaDataTable $dataTable)
    {
        return $dataTable->render('meta.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
            return view('meta.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Meta::createColumn($request->name);
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('meta.index');       
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Meta  $metum
     * @return \Illuminate\Http\Response
     */
    public function show(Meta $metum)
    {

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Meta  $metum
     * @return \Illuminate\Http\Response
     */
    public function edit(Meta $metum)
    {
        return view('meta.edit',[
                'metum'=>$metum,
        ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Meta  $metum
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Meta $metum)
    {
        $metum->update($request->all()); 
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('meta.index');      
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Meta  $metum
     * @return \Illuminate\Http\Response
     */
    public function destroy(Meta $metum)
    {
        $metum->deleteMeta();
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('meta.index');  
    }
}
