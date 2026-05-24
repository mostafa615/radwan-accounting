<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DataTables\ClientsDataTable;

use App\Models\Client;

use App\Models\Country;
use App\Utils\Util;
use Carbon\Carbon;

class ClientsController extends Controller
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
    public function index(ClientsDataTable $dataTable)
    {
        return $dataTable->render('clients.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        return view('clients.create',compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        
        if (Client::where('name', $request->name)->exists()) {
            flash('عفوا هذا العميل موجود بالفعل')->error();
            return back()->withInput();
        }

        $client = Client::create($request->except('old_client'));
        $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'clients', $client->id, $this->dateNow, $this->timeNow, null, null );

        flash('تمت العملية بنجاح')->success();
        return redirect()->route('clients.index');
    }
//     public function store(Request $request)
//     {
        
//         $client = Client::create($request->except('old_client'));
//         $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'clients', $client->id, $this->dateNow, $this->timeNow, null, null );

//         flash('تمت العملية بنجاح')->success();
//         return redirect()->route('clients.index');
//     }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        
        return view('clients.show',compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        $countries = Country::all();
        return view('clients.edit',compact('client','countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $oldData = Client::find($client->id);

        $client->update($request->all());
        $newData = $request->all();
        $properties = [
            'old_data' => $oldData,
            'new_data' => $newData
        ];

        $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'clients', $client->id, $this->dateNow, $this->timeNow, $properties, null );

        flash('تمت العملية بنجاح')->success();
        return redirect()->route('clients.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $oldData = Client::find($client->id);
        $properties = [
            'old_data' => $oldData,
        ];
        $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'clients', $client->id, $this->dateNow, $this->timeNow, $properties, null );

        $client->delete();
        flash('تمت العملية بنجاح')->success();
        return redirect()->route('clients.index');
    }

    public function searchByName(Request $request)
    {
        $search = $request->get('term');
        $result = Client::where('name', 'LIKE', '%'. $search. '%')->get();
        return response()->json($result);
    }
}
