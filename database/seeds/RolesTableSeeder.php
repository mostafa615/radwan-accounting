<?php

use Illuminate\Database\Seeder;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name'=>'admin']);
        $permissions = Permission::all();
        $role->attachPermissions($permissions);
        $user = User::first();
        $user->attachRole($role);

       
    }
}
