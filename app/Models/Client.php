<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Storage;

use App\Traits\OwnerableTrait;
use App\Traits\AccountsRelationTrait;
 

class Client extends Model
{


    use OwnerableTrait , AccountsRelationTrait;

    /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [];


    
    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }
    
    public function setIdImage1Attribute($file)
    {
        if(isset($this->attributes['id_image_1']))
        {
         Storage::delete($this->attributes['id_image_1']);            
        }
        $path = request()->file('id_image_1')->store('public/clients');
        $this->attributes['id_image_1'] =$path;
    }

    public function setIdImage2Attribute($file)
    {
        if(isset($this->attributes['id_image_2']))
        {
         Storage::delete($this->attributes['id_image_2']);            
        }
        $path = request()->file('id_image_2')->store('public/clients');
        $this->attributes['id_image_2'] =$path;
    }

    public function getIdImage1Attribute()
    {
        if($this->attributes['id_image_1'])
        {
            $src = asset(Storage::url($this->attributes['id_image_1']));
        }
        else
        {
            $src = asset(Storage::url('default/default.jpg'));
        }
        return $src;
    }

    public function getIdImage2Attribute()
    {
        if($this->attributes['id_image_2'])
        {
            $src = asset(Storage::url($this->attributes['id_image_2']));
        }
        else
        {
            $src = asset(Storage::url('default/default.jpg'));
        }
        return $src;
    }

}
