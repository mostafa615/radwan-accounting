<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Schema;

class Meta extends Model
{
    protected $guarded = [];

    public static function createColumn($name)
    {

        // first get the last row in this model 
        // naming will be details_* 

        $last  = optional(self::latest()->limit(1)->first())->col_name;
        if(is_null($last))
        {
            // first column being inserted
            $last = 'details_1';
        }
        else
        {
            $num = (int) explode('_',$last)[1];
            $last = 'details_'.++$num;
        }
        // create the column
        Schema::table('details', function($table)  use($last){
            $table->string($last)->nullable();
          });

        // store its name
        self::create(['name'=>$name , 'col_name'=>$last]);
    }

    public function deleteMeta()
    {
        Schema::table('details', function($table){
            $table->dropColumn($this->col_name);
        });
        $this->delete();
    }
}
