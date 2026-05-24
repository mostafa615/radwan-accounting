<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MachineSupplies extends Model
{
    public $table = 'machine_supplies';
    protected $fillable = [
        'machine_id',
        'supplie_id',
        'quantity',
        'used',
        'date',
        'note'
    ];
    protected $hidden = ['created_at', 'updated_at'];

    public function machine()
    {
        return $this->belongsTo(Machine::class, 'machine_id');
    }
    public function supplie()
    {
        return $this->belongsTo(Supply::class, 'supplie_id');
    }

    public function ScopeFilter($query, $request)
    {
        if (isset($request['machine'])) {
            $query->where('machine_id', '=', $request['machine']);
        }
        if (isset($request['supply_id'])) {
            $query->where('supplie_id', '=', $request['supply_id']);
        }
        if (isset($request['date_from']) && !isset($request['date_to'])) {
            $from = Carbon::parse($request['date_from']);
            $query->where('date', $from);
        }
        if (isset($request['date_from']) && isset($request['date_to'])) {
            $to = Carbon::parse($request['date_to']);
            $from = Carbon::parse($request['date_from']);
            $query->whereBetween('date', [$from, $to]);
        }
    }
}
