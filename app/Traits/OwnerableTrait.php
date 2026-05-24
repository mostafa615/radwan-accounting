<?php

namespace App\Traits;

trait OwnerableTrait {

    public function orders()
    {
        return $this->morphMany('App\Models\Order', 'ownerable');
    }

    public function setBalance($value)
    {
        $this->increment('balance',$value);
    }

    public function quantities()
    {
        return $this->morphMany('App\Models\Quantity', 'ownerable');
    }


}