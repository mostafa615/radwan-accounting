<?php

namespace App\Models;

use App\Notifications\UserResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use EntrustUserTrait;
    /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [];

    public function setPasswordAttribute($val)
    {
        $this->attributes['password'] = bcrypt($val);
    }

    public function store()
    {
        return $this->hasOne('App\Models\Store');
    }

    public function reposite()
    {
        return $this->hasOne('App\Models\Reposite');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\Type');
    }


    public function isOfType($type)
    {
        return (bool) $this->type()->where('name',$type)->count();
    }


}
