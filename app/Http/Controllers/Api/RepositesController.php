<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Reposite;

class RepositesController extends Controller
{
    //
    public function index()
    {
        return response()->json([
            'reposites'=>Reposite::all()
        ]);
    }
}
