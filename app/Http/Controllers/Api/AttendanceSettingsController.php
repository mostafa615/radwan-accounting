<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\AttendanceSettings;

class AttendanceSettingsController extends Controller
{
    //
    public function update(Request $request)
    {
        if(AttendanceSettings::count()){
            AttendanceSettings::first()->update($request->all());
        } else {
            AttendanceSettings::create($request->all());
        }

        return response()->json([
            'done'=>true
        ]);
    }
}
