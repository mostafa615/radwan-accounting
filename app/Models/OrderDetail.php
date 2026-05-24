<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DB;

class OrderDetail extends Model
{
    protected $guarded = [];

    public function item()
    {
        return $this->belongsTo('App\Models\Item');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order','order_id');
    }

    public function store()
    {
        return $this->belongsTo('App\Models\Store');
    }

    public function getStore() {
        return Store::find($this->store_id);
    }
    
    public function getItem() {
        return Item::find($this->item_id);
    }

    public function performPendingPrice($request)
    {
        
        $this->update(['price_pending'=>false , 'status'=>$request->status]);
    }

    public function performPendingLoad()
    {
            $order = $this->order;
            $exist = true;
            if($order->type == 'in'){
                $hasQuantities =  Quantity::where([
                    'ownerable_type'=>'App\Models\Store',
                    'ownerable_id'=>$this->store_id,
                    'item_id'=>$this->item_id
                ])->first();
    
                if($hasQuantities->quantity >= $this->quantity){
                    Quantity::where([
                        'ownerable_type'=>'App\Models\Store',
                        'ownerable_id'=>$this->store_id,
                        'item_id'=>$this->item_id
                    ])->decrement('quantity' ,$this->quantity);
            
                    Quantity::firstOrCreate([
                        'ownerable_type'=>$this->order->ownerable_type,
                        'ownerable_id'=>$this->order->ownerable_id,
                        'item_id'=>$this->item_id
                    ])->increment('quantity', $this->quantity);
                } else  {
                    return false;
                }
              
            } else {
                Quantity::firstOrCreate([
                    'ownerable_type'=>'App\Models\Store',
                    'ownerable_id'=>$this->store_id,
                    'item_id'=>$this->item_id
                ])->increment('quantity', $this->quantity);
        
                Quantity::firstOrCreate([
                    'ownerable_type'=>$this->order->ownerable_type,
                    'ownerable_id'=>$this->order->ownerable_id,
                    'item_id'=>$this->item_id
                ])->increment('quantity', $this->quantity);
            }
            
    
    /*
              $count = self::where('order_id',$this->order_id)
                ->where('load_pending',false)
                ->count();
    
            if($count == 0) {
                if($order->type == 'in'){
                    $order->ownerable->setBalance($order->final_total);
                } else {
                    $order->ownerable->setBalance(-$order->final_total);
                }
                
               if($order->cost != 0){
                   
                    $order->account()->create([
                        'reposite_id'=>$order->reposite_id,
                        'accountable_id'=>$order->ownerable_id,
                        'accountable_type'=>$order->ownerable_type,
                        'type'=>$order->type,
                        'order_id'=>$this->order_id,
                        'cost'=>$order->cost,
                        'date'=>$order->date,
                    ]);
               }
    
            }*/
            $this->update(['load_pending'=>false]);
            return true;
        
       
    }

}
