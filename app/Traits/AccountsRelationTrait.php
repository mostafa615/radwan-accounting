<?php

namespace App\Traits;

trait AccountsRelationTrait {


    public function accounts()
    {
        return $this->morphMany('App\Models\Account', 'accountable');
    }
}