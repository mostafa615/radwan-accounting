<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewQuantities extends Model
{
    protected $table = 'new_quantities';
    protected $guarded = [];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
