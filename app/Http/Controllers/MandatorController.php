<?php

namespace App\Http\Controllers;

use App\Models\Mandator;
use Illuminate\Http\Request;

use App\DataTables\MandatorsDataTable;
use App\Models\Branch;
use App\Models\Country;
use App\Models\Store;
use App\Utils\Util;
use Carbon\Carbon;

class MandatorController extends Controller
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
    public function index(MandatorsDataTable $dataTable)
    {
        return $dataTable->render('mandators.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branches = Branch::latest()->get();
        $stores = Store::latest()->get();
        $countries = Country::all();
        return view('mandators.create',compact('countries', 'branches', 'stores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $mandator = Mandator::create($request->all());
        $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'mandators', $mandator->id, $this->dateNow, $this->timeNow, null, null );

        flash('تمت العملية بنجاح')->success();
        return redirect()->route('mandators.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Mandator $mandator)
    {
        
        return view('mandators.show',compact('mandator'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Mandator $mandator)
    {
        $branches = Branch::latest()->get();
        $stores = Store::latest()->get();
        $countries = Country::all();
        return view('mandators.edit',compact('mandator','countries', 'branches', 'stores'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mandator $mandator)
    {
        $oldData = Mandator::find($mandator->id);

        $mandator->update($request->all());
        
        $newData = $request->all();
        $properties = [
            'old_data' => $oldData,
            'new_data' => $newData
        ];

        $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'mandators', $mandator->id, $this->dateNow, $this->timeNow, $properties, null );

        flash('تمت العملية بنجاح')->success();
        return redirect()->route('mandators.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mandator $mandator)
    {
        $oldData = Mandator::find($mandator->id);
        $properties = [
            'old_data' => $oldData,
        ];
        $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'mandators', $mandator->id, $this->dateNow, $this->timeNow, $properties, null );

        $mandator->delete();
        flash('تمت العملية بنجاح')->success();
        return redirect()->route('mandators.index');
    }
}
