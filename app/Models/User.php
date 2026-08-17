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

    /**
     * The store this account is *coded to* (users.store_id).
     *
     * Distinct from store() above, which is a hasOne resolving the reverse way
     * (stores.user_id) and therefore only ever matches the one user recorded as
     * the store's owner.
     */
    public function codedStore()
    {
        return $this->belongsTo('App\Models\Store', 'store_id');
    }

    /**
     * Stores this user is allowed to transact against on the invoice screens.
     *
     * Admins get every store. Everyone else is pinned to the store their
     * account is coded to, falling back to their branch's store.
     *
     * Resolving by users.store_id rather than stores.user_id matters: a store
     * carries a single user_id, so the old rule matched only 4 of 24 accounts
     * and left everyone else with an empty dropdown.
     */
    public function allowedStores()
    {
        if ($this->hasRole('admin')) {
            return Store::select('id', 'name')->orderBy('name')->get();
        }

        if ($this->store_id) {
            return Store::select('id', 'name')->where('id', $this->store_id)->get();
        }

        // stores has no branch_id column; reposites carries the branch -> store link.
        if ($this->branch_id) {
            $ids = \DB::table('reposites')
                ->where('branch_id', $this->branch_id)
                ->whereNotNull('store_id')
                ->distinct()->pluck('store_id')->toArray();

            if (count($ids)) {
                return Store::select('id', 'name')->whereIn('id', $ids)->orderBy('name')->get();
            }
        }

        return collect();
    }

    /**
     * The single store to lock the picker to, or null when the user may choose.
     */
    public function pinnedStoreId()
    {
        if ($this->hasRole('admin')) {
            return null;
        }
        $allowed = $this->allowedStores();
        return $allowed->count() === 1 ? (int) $allowed->first()->id : null;
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
