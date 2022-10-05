<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ChangePasswordRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
    /*
    |--------------------------------------------------------------------------
    | Show User
    |--------------------------------------------------------------------------
    */
    public function show(Request $request)
    {
        $user = $request->user();

        $user["known_words"] = $user->getKnownWords();
        $user["learning_words"] = $user->getLearningWords();

        $user->makeHidden(['knownWords','LearningWords']);
        $user->makeHidden(['created_at','updated_at','email_verified_at']);

        return $this->sendResponse(true,$user,"User get Successfully",200);
    }
    /*
    |--------------------------------------------------------------------------
    | Update User
    |--------------------------------------------------------------------------
    */
    public function update(Request $request)
    {
        $data = $request->only('name','level','email');

        $user = $request->user();

        $user->update($data);

        return $this->sendResponse(true,$data,"User Updated Successfully",200);
    }
    /*
    |--------------------------------------------------------------------------
    | Change User Password
    |--------------------------------------------------------------------------
    */
    public function changePassword(ChangePasswordRequest $request)
    {
        $data = $request->only('password');

        $user = $request->user();

        $user->update($data);

        return $this->sendResponse(true,$data,"Password Updated Successfully",200);
    }
}
