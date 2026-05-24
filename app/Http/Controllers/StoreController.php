<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use App\Models\Country;
use Illuminate\Http\Request;
use App\DataTables\StoresDataTable;
use App\DataTables\QuantitiesInStoreDataTable;
use App\Utils\Util;
use Carbon\Carbon;

class StoreController extends Controller
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
    public function index(StoresDataTable $dataTable)
    {
        return $dataTable->render('stores.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        $users = User::doesnthave('store')->get();
        return view('stores.create',compact('countries','users'));
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
        $store = Store::create($request->all());
        $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'stores', $store->id, $this->dateNow, $this->timeNow, null, null );

        flash('تمت العملية بنجاح')->success();
        return redirect()->route('stores.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store , QuantitiesInStoreDataTable $dataTable)
    {
        return $dataTable->render('stores.show',compact('store'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        $countries = Country::all();
        $users = User::doesnthave('store')
        ->orWhere('id',$store->user_id)
        ->get();
        return view('stores.edit',compact('store','countries','users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {
        $oldData = Store::find($store->id);
        $store->update($request->all());
        $newData = $request->all();
        $properties = [
            'old_data' => $oldData,
            'new_data' => $newData
        ];

        $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'stores', $store->id, $this->dateNow, $this->timeNow, $properties, null );

        flash('تمت العملية بنجاح')->success();
        return redirect()->route('stores.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        flash('عزيزي الادمن لا يمكن حذف المخزن بالرجاء التوجه الي الدعم')->error();
        return redirect()->route('stores.index');

        // $oldData = Store::find($store->id);
        // $properties = [
        //     'old_data' => $oldData,
        // ];
        // $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'stores', $store->id, $this->dateNow, $this->timeNow, $properties, null );

        // $store->delete();
        // flash('تمت العملية بنجاح')->success();
        // return redirect()->route('stores.index');
    }
}
