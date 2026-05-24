<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    /**
     * login the users
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        //
        
        if($request->isMethod('get'))
        {
            return view('auth.login');
        }
        else{
            if(auth()->attempt([
                'user_name'=>$request->username,
                'password'=>$request->password
            ] , $request->has('remember'))){

                $dateNow = Carbon::now()->format('Y-m-d');
                $timeNow = Carbon::now()->format('H:i:s');
                $util = new Util();
                $user = User::where('user_name', $request->username)->first();

                $util->activityLog($user->id, 'login', 'accounts', null, null, $dateNow, $timeNow, null, null );

                return redirect()->route('home');
            
            }

            else{
                flash('هذه البيانات غير صحيحة')->error();                
                return back();
            }


        }
        
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('login');
    }
}
