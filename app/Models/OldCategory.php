<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OldCategory extends Model
{
    protected $connection='old_db';
    protected $table='radwan_steel_old.sub_categories';
    protected $fillable=[
        'Main_Category_ID', 'Sub_Category_ID', 'Sub_Category_Name_A',
        'Sub_Category_Name_E', 'Category_Type', 'Entry_ID', 'Entry_Name',
        'Flag', 'Flag_N', 'Notice'
    ];

    public function items(){
        return $this->hasMany('App\Models\OldItem','Sub_Category_ID');
    }
}
