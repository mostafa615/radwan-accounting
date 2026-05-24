<?php

namespace App\Http\Controllers;

use App\Models\Reposite;
use Illuminate\Http\Request;
use App\Models\Branch;
use DB;
use App\DataTables\RepositesDataTable;
use App\Models\Store;
use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class RepositeController extends Controller
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
    public function index(RepositesDataTable $dataTable)
    {
        return $dataTable->render('reposites.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $stores = Store::latest()->get();
        $branches = Branch::has('users')->get();
        return view('reposites.create',compact('branches', 'stores'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reposite = Reposite::create($request->all());
        $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'reposites', $reposite->id, $this->dateNow, $this->timeNow, null, null );

        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('reposites.index');        
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reposite  $reposite
     * @return \Illuminate\Http\Response
     */
    public function show(Reposite $reposite)
    {

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reposite  $reposite
     * @return \Illuminate\Http\Response
     */
    public function edit(Reposite $reposite)
    {
        $branches = Branch::has('users')->get();
        $stores = Store::latest()->get();
        return view('reposites.edit',[
            'reposite'=>$reposite,
            'branches'=>$branches,
            'stores'=>$stores,
        ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reposite  $reposite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reposite $reposite)
    {
        $oldData = Reposite::find($reposite->id);

        $valueBefore = $reposite->balance;
        $reposite->update($request->all());
        //
        DB::table('reposite_edits')->insert([
            "reposite_id" => $reposite->id,
            "value_before" => $valueBefore,
            "value_income" => $request->balance,
            "value_after" => $reposite->balance,
            "user_id" => Auth::user()->id,
            "user_name" => Auth::user()->name,
            "reposite_name" => $reposite->name,
        ]);
        
        $newData = $request->all();
        $properties = [
            'old_data' => $oldData,
            'new_data' => $newData
        ];

        $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'reposites', $reposite->id, $this->dateNow, $this->timeNow, $properties, null );

        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('reposites.index');      
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reposite  $reposite
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reposite $reposite)
    {
        flash('عزيزي الادمن لا يمكن حذف الخزنة بالرجاء التوجه الي الدعم')->error();
        return redirect()->route('reposites.index');
        
        // $oldData = Reposite::find($reposite->id);
        // $properties = [
        //     'old_data' => $oldData,
        // ];
        // $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'reposites', $reposite->id, $this->dateNow, $this->timeNow, $properties, null );

        // $reposite->delete();
        
        // flash('تمت العمليه بنجاح')->success();
        // return redirect()->route('reposites.index');  
    }
}
