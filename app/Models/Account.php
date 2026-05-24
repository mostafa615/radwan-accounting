<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    //
    protected $guarded = [];
    protected $dates = ['date'];

    // public static function boot()
    // {
    //     parent::boot();
    //     static::created(function($account){
    //         // $account->update([
    //         //     'user_id'=>auth()->user()->id,
    //         //     'branch_id'=>auth()->user()->branch_id
    //         // ]);
    //     });
    // }
    public static function createAccount($request)
    {

        $owners = [
            'supplier' => 'App\Models\Supplier',
            'client' => 'App\Models\Client',
        ];
        if (Auth()->user()->id == 1) {
            $account = self::create([
                'user_id'=>Auth()->user()->id,
                'pending' =>0,
                'type' => $request->type,
                'order_id' => $request->order_id,
                'cost' => $request->cost,
                'date' => $request->date,
                'reposite_id' => $request->reposite_id,
                'accountable_id' => $request->accountable_id,
                'accountable_type' => $owners[$request->accountable_type],
                'notes'=>$request->notes
            ]);
            $reposite = Reposite::where('id',$request->reposite_id)->first();
            if ($reposite){
                if ($request->type == 'in'){
                    $reposite->increment('balance',$request->cost);
                }else{
                    $reposite->decrement('balance',$request->cost);
                }
            }
            if ($request->accountable_type == 'client'){
                $customer=Client::where('id',$request->accountable_id)->first();
                if ($customer){
                    if ($request->type == 'in'){
                        $customer->decrement('balance',$request->cost);
                    }else{
                        $customer->increment('balance',$request->cost);
                    }
                }
            }else{
                $customer=Supplier::where('id',$request->accountable_id)->first();
                if ($customer){
                    if ($request->type == 'out'){
                        $customer->decrement('balance',$request->cost);
                    }else{
                        $customer->increment('balance',$request->cost);
                    }
                }
            }
        } else {
            // check the type of the account
            $account = self::create([
                'user_id'=>Auth()->user()->id,
                'type' => $request->type,
                'order_id' => $request->order_id,
                'cost' => $request->cost,
                'date' => $request->date,
                'reposite_id' => $request->reposite_id,
                'accountable_id' => $request->accountable_id,
                'accountable_type' => $owners[$request->accountable_type],
                'notes'=>$request->notes
            ]);
        }


        // if($request->type == 'in')
        // {
        //     //will add the money to the reposite
        //     $account->reposite()->increment('balance',$account->cost);
        //     // will decrease the balance of the payer
        //     $account->accountable->setBalance(-$account->cost);
        // }
        // else //type is out
        // {
        //     // decrease the money from the reposite
        //     $account->reposite()->increment('balance',-$account->cost);
        //     // increase the balance of the payer
        //     $account->accountable->setBalance($account->cost);
        // }

    }


    public function deleteAccount()
    {
        //
        if ($this->type == 'in') {
            //will add the money to the reposite
            $this->reposite()->increment('balance', -$this->cost);
            // will decrease the balance of the payer
            $this->accountable->increment('balance',$this->cost);
        } else //type is out
        {
            // decrease the money from the reposite
            $this->reposite()->increment('balance', $this->cost);
            // increase the balance of the payer
            $this->accountable->decrement('balance',$this->cost);
        }

        $this->delete();
    }


    public function updateAccount($request)
    {
        //
        // if($this->type == 'in')
        // {
        //     //will add the money to the reposite
        //     $this->reposite()->increment('balance',-$this->cost);
        //     // will decrease the balance of the payer
        //     $this->accountable->setBalance($this->cost);
        // }
        // else //type is out
        // {
        //     // decrease the money from the reposite
        //     $this->reposite()->increment('balance',$this->cost);
        //     // increase the balance of the payer
        //     $this->accountable->setBalance(-$this->cost);
        // }


        $this->update([
            'type' => $request->type,
            'order_id' => $request->order_id,
            'cost' => $request->cost,
            'date' => $request->date,
            'reposite_id' => $request->reposite_id,
            'pending' => true,
        ]);


        // if($request->type == 'in')
        // {
        //     //will add the money to the reposite
        //     // $this->reposite()->increment('balance',$this->cost);
        //     // will decrease the balance of the payer
        // }
        // else //type is out
        // {
        //     // decrease the money from the reposite
        //     // $this->reposite()->increment('balance',-$this->cost);
        //     // increase the balance of the payer
        // }


    }


    public function performPending()
    {

        try {
            if ($this->type == 'in') {

                $this->reposite()->increment('balance', $this->cost);
                if ($this->accountable_type == "App\Models\Client" ){
                    if ($this->accountable)
                        $this->accountable->decrement('balance',$this->cost);
                }else{
                    if ($this->accountable)
                        $this->accountable->increment('balance',$this->cost);
                }
    
            } else {
    
                if ($this->reposite->balance < $this->cost) {
                    return false;
                }
                if ($this->reposite())
                    $this->reposite()->decrement('balance', $this->cost);
    
    //            $this->accountable->setBalance($this->cost);
                if ($this->accountable_type == "App\Models\Client" ){
                    if ($this->accountable)
                        $this->accountable->increment('balance',$this->cost);
    
                }else{
                    if ($this->accountable)
                        $this->accountable->decrement('balance',$this->cost);
                }
    
            }
        }catch(\Exception $e){
            
        }
        $this->update(['pending' => false]);
        return true;
    }


    public function accountable()
    {
        return $this->morphTo();
    }

    public function reposite()
    {
        return $this->belongsTo('App\Models\Reposite');
    }


    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }
}
