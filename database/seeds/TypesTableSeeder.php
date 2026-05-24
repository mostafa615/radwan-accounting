<?php

use Illuminate\Database\Seeder;

use App\Models\Type;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $types = [
            ['name'=>'manager','display_name'=>'مدير'],
            ['name'=>'branch_manger','display_name'=>'مدير فرع'],
            ['name'=>'employee','display_name'=>'موظف'],
        ];

        foreach($types as $type){
            Type::create($type);
        }
    }
}
