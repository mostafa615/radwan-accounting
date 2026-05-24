<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use App\DataTables\UsersDataTable;

use App\Models\User;
use App\Models\Role;
use App\Models\Type;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\Usernames;
use App\Models\Store;
use App\Utils\Util;
use Carbon\Carbon;

class UsersController extends Controller
 {
       protected $util;

    protected $dateNow;

    protected $timeNow;

    public function __construct(Util $util) {
        $this->util = $util;
        $this->dateNow = Carbon::now()->format('Y-m-d');
        $this->timeNow = Carbon::now()->format('H:i:s');
    }

    public function index(UsersDataTable $dataTable) {
        return $dataTable->render('users.index');
    }

    public function create() {
        $roles  = Role::all();
        $justRoles  = Type::all();
        $branches = Branch::latest()->get();
        $stores = Store::latest()->get();
        $Employees = Employee::all();
        $usernames = Usernames::all();

        return view('users.newCreate', compact('roles','justRoles','branches', 'stores', 'Employees', 'usernames'));
    }

    public function store(Request $request) {
        // return $request->all();
        $employee = Employee::find($request->emp_id);

        $user = User::create($request->except('job_id'));
        
        $user->update(['type_id' => $request->job_id, 'name' => $employee->name]);
        $user->attachRole(Role::find($request->role_id));
                
        $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'users', $user->id, $this->dateNow, $this->timeNow, null, null);

        flash('تم اضافة بيانات المستخدم بنجاح')->success();
        return redirect()->route('users.index');
    }

    public function edit(User $user) {
        $roles  = Role::all();
        $justRoles  = Type::all();
        $branches = Branch::latest()->get();
        $stores = Store::latest()->get();
        $usernames = Usernames::all();
        $Employees = Employee::all();

        return view('users.newedit', compact('user','roles','justRoles','branches', 'stores', 'usernames', 'Employees'));
    }

    public function update(Request $request, User $user) {
        $user = User::findOrFail($user->id);
        $user->update($request->except('job_id', 'role_id', 'emp_id'));
        $user->update(['type_id' => $request->job_id]);
        if(!$user->hasRole('admin')) {
            return $user->roles()->sync([]);
            $user->attachRole(Role::find($request->role_id));
        }

        if($request->emp_id) {
            $employee = Employee::find($request->emp_id);
            $user->update(['name' => $employee->name, 'emp_id' => $request->emp_id]);
        }

        $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'users', $user->id, $this->dateNow, $this->timeNow, ['old_data' => $user], null);

        flash('تم تعديل بيانات المستخدم بنجاح')->success();
        return redirect()->route('users.index');
    }

    public function destroy(Request $request, User $user) {
        flash('عزيزي الادمن لا يمكن حذف المستخدم بالرجاء التوجه الي الدعم')->error();
        return redirect()->route('users.index');

        // $user->roles()->sync([]);
        // $user = User::find($user->id);

        // $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'users', $user->id, $this->dateNow, $this->timeNow, ['old_data' => $user], null);

        // $user->delete();

        // flash('تم حذف بيانات المستخدم بنجاح')->success();
        // return redirect()->route('users.index');
    }

    public function return_users(Request $request) {
        $user  = User::find($request->userId);
        $state = $request->status == 1 ? 1 : 0;
        $user->update(['has_returns' => $state]);
        return response()->json('done');
    }
    
    public function edit_orders(Request $request) {
        $user  = User::find($request->userId);
        $state = $request->status == 1 ? 1 : 0;
        $user->update(['has_edit_orders' => $state]);
        return response()->json('done');
    }
    
    // public function edit_operation_order(Request $request)
    // {
    //     $user  = User::find($request->userId);
    //     $state = $request->status == 1 ? 1 : 0;
    //     $user->update(['has_edit_operation_order' => $state]);
    //     return response()->json('done');
    // }
    
    public function edit_operation_order(Request $request)
    {
        $user  = User::find($request->userId);
        $state = $request->status == 1 ? 1 : 0;
        $role = DB::table('role_user')->where('user_id', $user->id)->get();
        DB::beginTransaction();
        if ($state) {
            DB::table('permission_role')->insert([
                'role_id' => $role[0]->role_id,
                'permission_id' => 84
            ]);
        } else {
            DB::table('permission_role')->where('role_id', $role[0]->role_id)->where('permission_id', 84)->delete();
        }
        $user->update(['has_edit_operation_order' => $state]);
        DB::commit();
        return response()->json('done');
    }
    
    public function edit_operation_order_out(Request $request)
    {
        $user = User::find($request->userId);
        $state = $request->status == 1 ? 1 : 0;
        $role = DB::table('role_user')->where('user_id', $user->id)->get();
        DB::beginTransaction();
        if ($state) {
            DB::table('permission_role')->insert([
                'role_id' => $role[0]->role_id,
                'permission_id' => 84
            ]);
        } else {
            DB::table('permission_role')->where('role_id', $role[0]->role_id)->where('permission_id', 84)->delete();
        }
        $user->update(['has_edit_operation_order_out' => $state]);
        DB::commit();
        return response()->json('done');
    }

    public function storeUsername(Request $request) {
        $request->validate([
            'name' => 'required|unique:usernames',
        ], [
            'name.required' => 'بالرجاء ادخال اسم المستخدم',
            'name.unique' => 'اسم المستخدم مأخوذ من قبل',
        ]);

        DB::table('usernames')->insert(['name' => $request->name]);
                
        flash('تم اضافة اسم المستخدم بنجاح')->success();
        return redirect()->route('users.create');
    }

    public function destroyUsername(int $id) {
        $username = Usernames::findorFail($id);
        $username->delete();

        flash('تم حذف اسم المستخدم بنجاح')->success();
        return redirect()->route('users.create');
    }
}