<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Daily extends Model
{
    //
    protected $guarded = [];
    protected $dates = ['date'];

    public function tree()
    {
        return $this->belongsTo('App\Models\Tree', 'tree_id');
    }

    public function reposite()
    {
        return $this->belongsTo('App\Models\Reposite');
    }

    public static function createDaily($request)
    {
        // first create the instance
        $daily = self::create([
            'branch_id' => auth()->user()->branch_id,
            'user_id' => auth()->user()->id,
            'type' => $request->type,
            'tree_id' => $request->tree_id,
            'date' => $request->date,
            'cost' => $request->cost,
            'notes' => $request->notes,
            'reposite_id' => $request->reposite_id,
            'employee_id' => $request->employee_id,
        ]);


    }


    public function deleteDaily()
    {
        if (!$this->pending) {
            if ($this->type == 'in') {
                $this->reposite()->increment('balance', -$this->cost);
            } else { // out
                $this->reposite()->increment('balance', -$this->cost);
            }
        }
        $this->delete();
    }


    public function updateDaily($request)
    {
        // if($this->type == 'in'){
        //     $this->reposite()->increment('balance',-$this->cost);
        // }
        // else{ // out
        //     $this->reposite()->increment('balance',-$this->cost);
        // }

        $this->update([
            'branch_id' => auth()->user()->branch_id,
            'user_id' => auth()->user()->id,
            'type' => $request->type,
            'tree_id' => $request->tree_id,
            'date' => $request->date,
            'cost' => $request->cost,
            'notes' => $request->notes,
            'reposite_id' => $request->reposite_id,
            'pending' => true,
        ]);

        // if($this->type == 'in'){
        //     $this->reposite()->increment('balance',$this->cost);
        // }
        // else{ // out
        //     $this->reposite()->increment('balance',-$this->cost);
        // }


    }


    public function performPending()
    {
        \Debugbar::info(optional($this->reposite)->balance);
        \Debugbar::info($this->cost);
        \Debugbar::info($this->cost >= optional($this->reposite)->balance);
        if ($this->pending == 1) {
            if ($this->type == 'in') {
                $this->reposite()->increment('balance', $this->cost);
            } else { // out
                if ($this->cost >= optional($this->reposite)->balance) {
                    return false;
                }

                $this->reposite()->increment('balance', -$this->cost);
            }
            $this->update(['pending' => false]);
            return true;
        }
        return false;
    }
}
