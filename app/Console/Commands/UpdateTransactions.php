<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

use App\Models\Transaction;
use App\Models\Detail;

class UpdateTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update transactions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    // first update transactions type
    // reoperation
    // 
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 1- update transactions types
            // Transaction::whereNull('type')->update(['type'=>'operation']);
            // Transaction::where('type','reoperate')->update(['type'=>'reoperation']);
        // 2- update type for details and deadline
            // ['operation','reoperation']
            //  $ts = Transaction::all();
            //  foreach($ts as $t)
            //  {
            //    $t->details()->update(['type'=>$t->type,'deadline'=>$t->deadline]);
            //  }

              // ['created_by_user']
                // $ts = Transaction::where('created_by_user',1)->get();
                // foreach($ts as $t)
                // {
                //     $t->details()->update(['type'=>'created_by_user']);
                // }

                // Detail::where('type','created_by_user')->chunk(100,function($ds){
                //     foreach($ds as $d)
                //     {
                //         $deadline = $d->created_at->addDays(2);
                //         $this->info($deadline);
                //         $d->update(['type'=>'created_by_user','deadline'=>$deadline]);
                //     }
                //  });
            //  ['call_center']
                // Detail::where('is_call_center',true)->chunk(100,function($ds){
                //     foreach($ds as $d)
                //     {
                //         $deadline = $d->created_at->addDays(2)->copy();
                //         $d->update(['type'=>'call_center','deadline'=>$deadline]);
                //     }
                // });
    }
}
