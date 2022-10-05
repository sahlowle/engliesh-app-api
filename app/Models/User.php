<?php

namespace App\Models;

use App\Traits\HasPlan;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasPlan;

    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'level', // 1 =  beginner , 2 = intermediate ,3 = advanced
        'email',
        'subscribe_until',
        'password',
        'my_words',
        'all_words',
        'role', // 1 =  admin , 2 = user
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'subscribe_until' => 'date',
    ];

    /**
     * Scope a query to Get Admins
     *
     */
    public function scopeAdmins($query)
    {
        return $this->role('admin');

        return $query->where('role',1);
    }

    /**
     * Scope a query to Get Students
     *
     */
    public function scopeStudents($query)
    {
        return $this->role('student');

        return $query->where('role',2);
    }

    /**
	* Set Password 
	*/
    public function setPasswordAttribute($value){

        $this->attributes['password'] = Hash::make($value);
    }

    /**
	* Cast Levels To Names
	*/
    public function getLevelAttribute($value)
    {
        switch ($value) {
            case 1:
                return "Beginner";
                break;
            case 2:
                return "Intermediate";
                break;
            case 3:
                return "Advanced";
                break;
            
            default:
            return "Not Selected";
                break;
        }
    }
	
	/**
	* User LearningWords Relation 
	*/
	public function LearningWords()
    {
        return $this->hasOne('App\Models\LearningWord');
    }

	/**
	* User knownWords Relation 
	*/
	public function knownWords()
    {
        return $this->hasOne('App\Models\KnownWord');
    }

    /**
	* User Subjects Relation 
	*/
	public function subjects()
    {
        return $this->belongsToMany('App\Models\Subject');
    }
	
	/**
	* User Lessons Relation 
	*/
	public function lessons()
    {
        return $this->belongsToMany('App\Models\Lesson')->withPivot('id');
    }

	/**
	* Calculate Current Words 
	*/
	public function calculateCurrentWords()
    {
        $array = collect();
        
        if ($this->hasActiveSubscription()) {
            $subscribe_id = $this->activeSubscription()->pivot->id;
            $array = $this->lessons()->wherePivot('subscribe_id',$subscribe_id)->pluck('text');
        }

        if ($this->hasPreviousSubscription()) {
            $subscribe_id = $this->lastActiveSubscription()->pivot->id;

            $id = $this->lessons()->wherePivot('subscribe_id',$subscribe_id)->latest('id')->first()->pivot->id;

            $array = $this->lessons()->wherePivot('id','>',$id)->wherePivotNull('subscribe_id')->pluck('text');
        }
        else {

            if (! $this->hasSubscriptions()) {

                $array = $this->lessons()->wherePivotNull('subscribe_id')->pluck('text');

            }

        }
        // $array = $this->lessons()->pluck('text');

        $joined = $array->implode(' ');

        $string = str_word_count($joined, 1);

        $words = array_unique($string);

        return count($words);
    }
	/**
	* Calculate All User Words 
	*/
	public function calculateWords()
    {
        $array = $this->lessons()->pluck('text');

        $joined = $array->implode(' ');

        $string = str_word_count($joined, 1);

        $words = array_unique($string);

        return count($words);
    }

	/**
	* Get Learning Words 
	*/
	public function getLearningWords()
    {
        if ( ! $this->LearningWords) {
           return [];
        }

        $string = $this->LearningWords->words;

        $array = explode(',', $string);

        return $array;
    }

	/**
	* Get Known Words 
	*/
	public function getKnownWords()
    {
        if ( ! $this->knownWords) {
           return [];
        }

        $string = $this->knownWords->words;

        $array = explode(',', $string);

        return $array;
    }
}
