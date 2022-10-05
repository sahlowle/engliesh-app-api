<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PlanRequest;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Get All Plans
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $data = Plan::select(['id','name','price','months','is_feature'])->get();

        return $this->sendResponse(true,$data,"Plans get Successfully",200);
    }
    /*
    |--------------------------------------------------------------------------
    | Subscribe To Plan
    |--------------------------------------------------------------------------
    */
    public function subscribe(PlanRequest $request)
    {
        $user = $request->user();

        $plan_id = $request->plan_id;

        $plan = Plan::findOrFail($plan_id);

        if ($user->hasActiveSubscription()) {
            $this->sendResponse(false,[],"You Already Have Active Subscription",403);
        }

        $user->subscribeToPlan($plan);

        return $this->sendResponse(true,[],"Subscription Added Successfully",200);
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
