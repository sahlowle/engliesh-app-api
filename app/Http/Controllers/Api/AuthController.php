<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Register New User
    |--------------------------------------------------------------------------
    */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create($data);

        $user->assignRole('student');

        return $this->sendResponse(true,$data,"User Created Successfully",200);
        
    }
    // End Function

    /*
    |--------------------------------------------------------------------------
    | Login User
    |--------------------------------------------------------------------------
    */
    public function Login(LoginRequest $request)
    {

        if(!Auth::attempt($request->only(['email', 'password']))){
            
            return $this->sendResponse(false,[],"Email & Password does not match with our record.",401);

        }

        $user = User::where('email', $request->email)->first();

       if ($user->hasRole('admin')) {
           return $this->sendResponse(false,[],"Admin Not Allowed",403);
       }

        $data["token"] = $user->createToken("API TOKEN")->plainTextToken;

        return $this->sendResponse(true,$data,"User Login Successfully",200);
        
    }
    // End Function
}
