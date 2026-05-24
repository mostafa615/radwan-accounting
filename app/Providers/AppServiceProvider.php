<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Schema;
use View;
use App\Models\OrderDetail;
use App\Models\LoadDetail;
use App\Models\Reposite;
use App\Models\Load;
use App\Models\Transaction;
use App\Models\Daily;
use App\Models\Account;
use App\Models\Loan;
use App\Models\Store;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        View::composer(['partials.side-bar' , 'layout.app'], function ($view) {
            $view->with('perms',auth()->user()->roles()->first()->perms()->pluck('name'));       //
        });

        View::composer('layout.app', function ($view) {
            $pendingLoads = 0;
            
            if(auth()->user()->can('store_responsible')){

            
                $pendingOrderLoads = OrderDetail::where('price_pending',false)
                ->where('load_pending',true)
                ->where('store_id',optional(auth()->user()->store)->id)
                ->count();

                $pendingStoreLoads = LoadDetail::where('pending',true)
                ->leftJoin('loads','loads.id','=','load_details.load_id')
                ->where('loads.to_id',optional(auth()->user()->store)->id)
                ->count();
              
                $pendingLoads = $pendingStoreLoads + $pendingOrderLoads;
            }
            $view->with('pendingLoads',$pendingLoads);       //

        });

        View::composer('layout.app', function ($view) {
            $pendingAccounts = 0;
            
            if(auth()->user()->can('reposite_responsible')){
                $pendingClientAccounts = Account::where('pending',true)
                ->where('reposite_id',optional(auth()->user()->reposite)->id)
                ->count();

                $pendingTransactions = Transaction::where('pending',true)
                    ->whereHas('to',function ($q) {
                        $q->where('user_id',auth()->user()->id);
                    })
//                ->where('to_id',optional(auth()->user()->reposite)->id)
                ->count();

                $pendingLoans =  Loan::where('pending',true)
                ->where('reposite_id',optional(auth()->user()->reposite)->id)
                ->count();

$pendingDailies =  Daily::where('pending',true)
                    ->whereHas('reposite',function ($q){
                        $q->where('user_id',Auth()->user()->id);
                    })
//                ->where('reposite_id',optional(auth()->user()->reposite)->id)
                ->count();
                $pendingDailies =  Daily::where('pending',true)
                ->where('reposite_id',optional(auth()->user()->reposite)->id)
                ->count();
                
                $pendingAccounts = $pendingClientAccounts + $pendingTransactions + $pendingLoans + $pendingDailies;
            }
            $view->with('pendingAccounts',$pendingAccounts);       //

        });


        View::composer('layout.app', function ($view) {
            $pendingPrices = 0;
            
            if(auth()->user()->can('can_accept_price_requests')){
                $pendingPrices = OrderDetail::where('price_pending',true)->count();

            }
            $view->with('pendingPrices',$pendingPrices);       //

        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
