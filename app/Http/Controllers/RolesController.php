<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Role;
use App\Models\Permission;

use DB;
use App\DataTables\RolesDataTable;
use App\Utils\Util;
use Carbon\Carbon;

class RolesController extends Controller
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
    public function index(RolesDataTable $dataTable)
    {
        return $dataTable->render('roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create',compact('permissions'));
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
        DB::beginTransaction();
        $role = Role::create(['name'=>$request->name,'display_name' =>$request->display_name]);
        $role->attachPermissions($request->permissions);
        $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'roles', $role->id, $this->dateNow, $this->timeNow, null, null );

        DB::commit();
        flash('تمت العملية بنجاح')->success();
        return redirect()->route('roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();        
        return view('roles.edit',compact('role','permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        //
        
        DB::beginTransaction();
        $role->update(['name'=>$request->name]);
        $role->perms()->sync([]);
        $role->attachPermissions($request->permissions);
        $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'users', $role->id, $this->dateNow, $this->timeNow, null, null );

        DB::commit();

        flash('تمت العملية بنجاح')->success();
        return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        DB::beginTransaction();
         $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'roles', $role->id, $this->dateNow, $this->timeNow, $properties, null );
        $blog = DB::table('roles')->where('id',$role->id)->delete();
        
        // // Force Delete
        // $role->users()->sync([]); // Delete relationship data
        // $role->perms()->sync([]); // Delete relationship data

        // $role->forceDelete(); // Now force delete will work regardless of whether the pivot table has cascading delete
        // $oldData = Role::find($role->id);
        // $properties = [
        //     'old_data' => $oldData,
        // ];
       

        // $role->delete(); // This will work no matter what

        DB::commit();
        flash('تمت العملية بنجاح')->success();
        return redirect()->route('roles.index');
    }
}
