<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;

use DB;
use App\Models\AttendanceSettings;
use App\Models\Item;
use App\Models\Group;

class toOperate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this for clients who was assigned and the user didnt response with status to them';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::beginTransaction();
        // $groups =DB::connection('open_system')->table('main_categories')->select('Main_Category_Name_A','Sub_Category_ID')->get();
        // foreach($groups as $group)
        // {
        //     Group::create(['id'=>$group->Sub_Category_ID,'name'=>$group->Main_Category_Name_A]);
        // }

        
        $items = DB::connection('open_system')->table('items')->select('Sub_Category_ID','Item_Name_A','Item_Code')->get();
        foreach($items as $item)
        {
            $this->line($item->Sub_Category_ID);
            // Item::create([
            //     'group_id'=>$item->Sub_Category_ID,
            //     'name'=>$item->Item_Name_A ,
            //     'code'=>$item->Item_Code,
            //  ]);
        }
        DB::commit();
    }
}
