<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LessonTopicRequest;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class LessonController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | get user lesson
    |--------------------------------------------------------------------------
    */
    public function myLessons(Request $request)
    {
        
        $user = $request->user();

        $data = $user->lessons;

        return $this->sendResponse(true,$data,"Lesson Retrieved Successfully",200);

    }

    /*
    |--------------------------------------------------------------------------
    | Translate Any Word
    |--------------------------------------------------------------------------
    */
    public function translate(Request $request)
    {
        $word = $request->word;

        $translated_word = GoogleTranslate::trans($word, 'ar'); 

        $data = [ "word_ar" => $translated_word ];

        return $this->sendResponse(true,$data,"Word Translated Successfully",200);
    }
    

    /*
    |--------------------------------------------------------------------------
    | get lesson
    |--------------------------------------------------------------------------
    */
    public function show(Request $request,$lesson_id)
    {
        
        $user = $request->user();

        $lesson = Lesson::findOrFail($lesson_id);

        if ($user->lessons()->find($lesson_id)) {

            return $this->sendResponse(true,$lesson,"Lesson Retrieved Successfully",200);

        }

        $status = enrolByApi( $lesson, $user ); // Enroll To Lesson

        if ($status == 0) {
            return $this->sendResponse(false,[],"You Need To Subscribe",403 );
        }

        
        
        return $this->sendResponse(true,$lesson,"Lesson Retrieved Successfully",200);

    }


    /*
    |--------------------------------------------------------------------------
    | Add Known Word To User
    |--------------------------------------------------------------------------
    */
    public function addKnownWord(Request $request)
    {
        $word = $request->word;

        $user = $request->user();
        
        $kWords = $user->knownWords;
        
        if (empty($kWords)) {
            $user->knownWords()->create(['words' => $word]);
        }
        else
        {
            $arr = explode(",",$kWords->words);

            if (in_array($word,$arr))
            return $this->sendResponse(false,[],"Word Already Exists",420);

            $updatedWords = $kWords->words.','.$word;
            $user->knownWords->update(['words' => $updatedWords]);
        }
        
        return $this->sendResponse(true,[],"Known Word Updated Successfully",200);
    }

    /*
    |--------------------------------------------------------------------------
    | Add Learning Word To User
    |--------------------------------------------------------------------------
    */
    public function addLearningWord(Request $request)
    {
        $word = $request->word;

        $user = $request->user();
        
        $LWords = $user->LearningWords;
        
        if (empty($LWords)) {
            $user->LearningWords()->create(['words' => $word]);
        }
        else
        {
            $arr = explode(",",$LWords->words);

            if (in_array($word,$arr))
            return $this->sendResponse(false,[],"Word Already Exists",420);

            $updatedWords = $LWords->words.','.$word;
            $user->LearningWords->update(['words' => $updatedWords]);
        }
        
        return $this->sendResponse(true,[],"Learnig Word Updated Successfully",200);
    }

    /*
    |--------------------------------------------------------------------------
    | Get Lessons By Topic
    |--------------------------------------------------------------------------
    */
    public function lessonByTopic(LessonTopicRequest $request)
    {
        $subject_id = $request->subject_id;

        $data = Lesson::select(['id','name','level','image','text'])->where('subject_id',$subject_id)->get()->makeHidden(['text','words']);

        return $this->sendResponse(true,$data,"Lessons get Successfully",200);
    }
}
