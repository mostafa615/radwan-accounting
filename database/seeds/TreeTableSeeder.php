<?php

use Illuminate\Database\Seeder;

use App\Models\Tree;

class TreeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $trees = 
        [
            ['j1_1', '#', 'fa  fa-folder-o', 'default', 'ايرادات',],
            ['j1_2', '#', 'fa  fa-folder-o', 'default', 'مصروفات',],
        ];

        foreach($trees as $tree)
        {
            Tree::create([
                'id'=>$tree[0],
                'parent'=>$tree[1],
                'icon'=>$tree[2],
                'type'=>$tree[3],
                'text'=>$tree[4],
            ]);
        }
    }
}
