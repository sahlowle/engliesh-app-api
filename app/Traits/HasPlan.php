<?php

namespace App\Traits;

use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Http\Request;

trait HasPlan {

    /*
    |--------------------------------------------------------------------------
    | Check User Has Subscription
    |--------------------------------------------------------------------------
    */
    public function hasSubscription() {

        if ($this->subscribe_until) {
            if ( $this->subscribe_until->isFuture() ) {
                return true;
            }
        }
        
        return false;

    }
    /*
    |--------------------------------------------------------------------------
    | Check User Does Not Has Subcription
    |--------------------------------------------------------------------------
    */
    public function doesNotHasSubscription() {

        if ($this->subscribe_until) {
            if ( $this->subscribe_until->isFuture() ) {
                return false;
            }
        }
        
        return true;

    }
    /*
    |--------------------------------------------------------------------------
    | Check User Has Previous Subcription
    |--------------------------------------------------------------------------
    */
    public function hasPreviousSubscription() {

        if (! $this->hasActiveSubscription()) {
            if ( $this->hasSubscriptions() ) {
                return true;
            }
        }
        
        return false;

    }
    /*
    |--------------------------------------------------------------------------
    | Subscribe User To Plan
    |--------------------------------------------------------------------------
    */
    public function subscribeToPlan($plan) {

        $months = $plan->months;

        $expireDate = Carbon::today()->addMonths($months);

        $this->update([ 
            'subscribe_until' => $expireDate, 
            'my_words' => 0, 
        ]);

        $subData = [ 
            'starts_on' => Carbon::today() ,
            'expires_on' => $expireDate ,
        ];

        $this->subscriptions()->attach($plan->id,$subData );

    }
    // public function oldSubscribeToPlan($plan) {

    //     $months = $plan->months;

    //     if ($this->subscribe_until) {
    //         $newDate = $this->subscribe_until->addMonths($months);
    //     } else {
    //         $newDate = Carbon::today()->addMonths($months);
    //     }

    //     $this->update([ 
    //         'subscribe_until' => $newDate, 
    //         'my_words' => 0, 
    //     ]);

    // }
    /*
    |--------------------------------------------------------------------------
    | get User Subscription
    |--------------------------------------------------------------------------
    */
    public function subscriptions()
    {
        return $this->belongsToMany(Plan::class)->withPivot('id');
    }
    /*
    |--------------------------------------------------------------------------
    | get Has Subscription
    |--------------------------------------------------------------------------
    */
    public function hasSubscriptions()
    {
        return (bool) ($this->subscriptions()->count() > 0);
    }
    /*
    |--------------------------------------------------------------------------
    | get Current Subscription
    |--------------------------------------------------------------------------
    */
    public function currentSubscription()
    {
        return $this->subscriptions()
                    ->wherePivot('starts_on', '<', Carbon::now())
                    ->wherePivot('expires_on', '>', Carbon::now());
    }
    /*
    |--------------------------------------------------------------------------
    | get Active Subscription
    |--------------------------------------------------------------------------
    */
    public function activeSubscription()
    {
        return $this->currentSubscription()->first();
    }
    /*
    |--------------------------------------------------------------------------
    | Check Has Active Subscription
    |--------------------------------------------------------------------------
    */
    public function hasActiveSubscription()
    {
        return (bool) $this->activeSubscription();
    }
    /*
    |--------------------------------------------------------------------------
    | get Last Active Subscription
    |--------------------------------------------------------------------------
    */
    public function lastActiveSubscription()
    {
        if (! $this->hasSubscriptions()) {
            return;
        }

        if ($this->hasActiveSubscription()) {
            return $this->activeSubscription();
        }

        return $this->subscriptions()->orderByPivot('expires_on', 'desc')->first();
    }


}