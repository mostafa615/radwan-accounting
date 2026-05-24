<?php

namespace App\Traits;

trait ReturnsTrait {

    public function canBeReturned()
    {
        $current = $this->orderDetails()->sum('quantity');
        $returns = $this->returns;
        $sum = 0;        
        $returns->each(function($item ,$index) use( &$sum){
            return $sum +=$item->orderDetails()->sum('quantity');
        });
        return $current >  $sum;
    }

    public function returns()
    {
        return $this->hasMany('App\Models\Order','order_id');
    }

    public function getAvailableQuantaties()
    {
        $returns = $this->returns;
        $order = $this;
        $items = $order->orderDetails()->with('item')->get();
        $new = collect();
        if($returns->count())  {
            foreach($returns as $return)  {
                foreach($return->orderDetails as $detail)   {
                    foreach($items as $item)  {
                            if(($detail->item_id ==  $item->item_id) and ($item->quantity > $detail->quantity + 1)) {
                                $item->quantity -= $detail->quantity;
                                $new->push($item);  
                            }
                    }
                }
            }
    }  else  {
        $new = $items;
    }
       return $new->unique('id');
    }

    // public function getAvailableAttribute()
    // {   
    //     return $this->getAvailableQuantaties();
    // }
}