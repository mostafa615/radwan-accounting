<?php

namespace App\Http\Controllers;

use App\Models\Load;
use Illuminate\Http\Request;

class LoadsController extends Controller
{
    public function pendingLoads() {
        $resources = Load::with('user','loadDetails', 'from', 'to')->where('to_id', auth()->user()->store_id)->where('status', 'pending')->paginate(10);

        return view('pending-loads.index', compact('resources'));
    }
 
    public function acceptLoad(Request $request) {
        $load = Load::findorFail($request->id);
       
        $load->update(['status' => 'accepted']);
        
        if(!empty($request->notes))
            $load->update(['notes' => $request->notes]);

        $load->employee()->attach($request->employee_id);

        flash('تم قبول تحويل الخامات بنجاح')->success();
        return back();
    }

    public function refuseLoad(Request $request) {
        $load = Load::findorFail($request->id);
       
        $load->update(['status' => 'refused']);
        
        if(!empty($request->notes))
            $load->update(['notes' => $request->notes]);

        $load->employee()->attach($request->employee_id);

        flash('تم رفض تحويل الخامات بنجاح')->success();
        return back();
    }
}