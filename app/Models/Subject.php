<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
    protected $hidden = ['pivot'];

	
	 /**
     * Get Lessons for the Subject , Relationship.
     */
    public function lessons()
    {
        return $this->hasMany('App\Models\Lesson');
    }
	
	/**
	* Subject Users Relationship 
	*/
	public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }
}
