<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserTopicsRequest;
use App\Models\Subject;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Get All Topics
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $data = Subject::select(['id','name'])->get();

        return $this->sendResponse(true,$data,"Topics get Successfully",200);
    }
    /*
    |--------------------------------------------------------------------------
    | Get Topics To User
    |--------------------------------------------------------------------------
    */
    public function AddUserTopics(UserTopicsRequest $request)
    {
        $topics_ids = $request->topics_ids;

        $user = $request->user();

        $data = $user->subjects()->syncWithoutDetaching($topics_ids);

        return $this->sendResponse(true,[],"User Topics Added Successfully",200);
    }
    /*
    |--------------------------------------------------------------------------
    | Get User Topics With Lessons
    |--------------------------------------------------------------------------
    */
    public function userTopics(Request $request)
    {
        $user = $request->user();

        $data = $user->subjects()->select('subjects.id','subjects.name')->get();

        return $this->sendResponse(true,$data,"Topics get Successfully",200);
    }
}
