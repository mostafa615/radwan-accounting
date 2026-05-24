<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Branch;

class BranchController extends Controller
{
    //
    public function users(Request $request)
    {
        $users = Branch::find($request->branch_id)->users;
        return response()->json(['users'=>$users]);
    }

}
