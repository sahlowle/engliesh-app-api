<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Lesson extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['word_count','words'];
	
	/**
	* Lesson Users Releation 
	*/
	public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }

	/**
	* Subject(Topic) Releation 
	*/
	public function subject()
    {
        return $this->belongsTo('App\Models\Subject');
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
	* get number of words in the lesson 
	*/
    public function getWordCountAttribute()
    {
        $text = $this->text;

        $string = str_word_count($text, 1);

        $words = array_unique($string);

        return count($words);
    }

    /**
	* get number of words in the lesson 
	*/
    public function getWordsAttribute()
    {
        $text = $this->text;

        $string =  trim($text);
        
        return Str::of($string)->split('/[\s ]+/');
    }
}
