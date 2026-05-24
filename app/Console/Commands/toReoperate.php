<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Detail;

use DB;
 
use Carbon\Carbon;

class toReoperate extends Command
{
   /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'details:reoperate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'return details that dont has responses to the create datatable';

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
        Detail::
        select('id','to_reoperate','type',DB::raw('MAX(details.id) as max'),'deadline','client_id','submitted')
        ->having('submitted',false)
        ->whereIn('details.id',function($query){
                $query
                ->select(DB::raw('max(details.id)'))
                ->from('details')
                ->where('type','reoperation')
               ->groupBy('details.client_id');
        }) 
        ->where('deadline','<',Carbon::now())
        ->having('type','reoperation')
        ->groupBy('client_id')->chunk(10,function($details){
                foreach($details as $detail)
                {
                    Detail::find($detail->id)->update(['to_reoperate'=>true]);
                }
        });


        // call_center 
        Detail::
        select('id','to_reoperate','type',DB::raw('MAX(details.id) as max'),'deadline','client_id','submitted')
        ->having('submitted',true)
        ->whereIn('details.id',function($query){
                $query
                ->select(DB::raw('max(details.id)'))
                ->from('details')
               ->groupBy('details.client_id');
        }) 
        ->where('deadline','<',Carbon::now())
        ->having('type','call_center')
        ->groupBy('client_id')->chunk(50,function($details){
                foreach($details as $detail)
                {
                    Detail::find($detail->id)->update(['to_reoperate'=>true]);
                }
        });

        // created_by_user
        Detail::
        select('id','to_reoperate','type',DB::raw('MAX(details.id) as max'),'deadline','client_id','submitted')
        ->having('submitted',true)
        ->whereIn('details.id',function($query){
                $query
                ->select(DB::raw('max(details.id)'))
                ->from('details')
               ->groupBy('details.client_id');
        }) 
         // archive
         ->where('user_id','!=','14')
        ->where('deadline','<',Carbon::now())
        ->having('type','created_by_user')
        ->groupBy('client_id')->chunk(50,function($details){
                foreach($details as $detail)
                {
                    Detail::find($detail->id)->update(['to_reoperate'=>true]);
                }
        });



    }
}
