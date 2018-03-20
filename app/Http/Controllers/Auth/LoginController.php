<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    public function isUserValidated(Request $request){

        $credentials = [
            'name' => $request->get('username'),
            'password' => $request->get('password'),
        ];

        if (Auth::attempt($credentials)){
            $status = true;  
            return response()->json($status);      
        }
        else {
            $status = false;
            return response()->json($status);        
        }
    }
}