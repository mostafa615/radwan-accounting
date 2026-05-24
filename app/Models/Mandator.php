<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Mandator extends Model
{
    protected $guarded = [];

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }
    
    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }

    public function store()
    {
        return $this->belongsTo('App\Models\Store');
    }

    public function setIdImage1Attribute($file)
    {
        if(isset($this->attributes['id_image_1']))
        {
         Storage::delete($this->attributes['id_image_1']);            
        }
        $path = request()->file('id_image_1')->store('public/mandator');
        $this->attributes['id_image_1'] =$path;
    }

    public function setIdImage2Attribute($file)
    {
        if(isset($this->attributes['id_image_2']))
        {
         Storage::delete($this->attributes['id_image_2']);            
        }
        $path = request()->file('id_image_2')->store('public/mandator');
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
