<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Validator;

class ValidationController extends Controller
{
    //
    /**
     * get the validation rule and the data
     * @param method to be called
     * @param Request
     * @return Response
     */
    public function validation(Request $request)
    {
        
        $field = $request->field;
        $value = $request->value;
        $method = $request->method;
        $data[$field] = $value; 
        $validator = Validator::make($data,[
            $field=>$method,
        ]);
        $response= ($validator->fails())?'false':'true';
        return response($response);
    }
}
