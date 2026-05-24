<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DataTables\ActorsDataTable;

use App\Models\Actor;
use App\Models\Country;
use App\Utils\Util;
use Carbon\Carbon;

class ActorController extends Controller
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
    public function index(ActorsDataTable $dataTable)
    {
        return $dataTable->render('actors.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        return view('actors.create',compact('countries'));
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
        $actor = Actor::create($request->all());
        $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'actors', $actor->id, $this->dateNow, $this->timeNow, null, null );

        flash('تمت العملية بنجاح')->success();
        return redirect()->route('actors.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Actor $actor)
    {
        
        return view('actors.show',compact('actor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Actor $actor)
    {
        $countries = Country::all();
        return view('actors.edit',compact('actor','countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Actor $actor)
    {
        $oldData = Actor::find($actor->id);

        $actor->update($request->all());
        $newData = $request->all();
        $properties = [
            'old_data' => $oldData,
            'new_data' => $newData
        ];

        $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'actors', $actor->id, $this->dateNow, $this->timeNow, $properties, null );

        flash('تمت العملية بنجاح')->success();
        return redirect()->route('actors.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Actor $actor)
    {
        $oldData = Actor::find($actor->id);
        $properties = [
            'old_data' => $oldData,
        ];
        $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'actors', $actor->id, $this->dateNow, $this->timeNow, $properties, null );

        $actor->delete();
        flash('تمت العملية بنجاح')->success();
        return redirect()->route('actors.index');
    }
}
