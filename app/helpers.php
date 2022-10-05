<?php

/*
|--------------------------------------------------------------------------
| Enroll To Lesson
|--------------------------------------------------------------------------
*/
function enrol($lesson,$user)
{
            
    $lessonWords = $lesson->word_count;

    $my_words = $user->calculateCurrentWords();

    $my_words = $lessonWords + $my_words;



    if (! $user->hasActiveSubscription()) {
        if ($my_words > 60) {
            return redirect('/plan');
        }

        $user->lessons()->attach($lesson->id);

    }

    if ($user->hasActiveSubscription()) {
        $subscribe_id = $user->activeSubscription()->pivot->id;
        $user->lessons()->attach($lesson->id,['subscribe_id'=>$subscribe_id]); 
    }

    


    $user->update( [ 
        'my_words' => $user->calculateCurrentWords() ,
        'all_words' => $user->calculateWords() ,
    ]);


    return view('lesson' ,compact('lesson'));
}
/*
|--------------------------------------------------------------------------
| Enroll To Lesson By Api
|--------------------------------------------------------------------------
*/
function enrolByApi($lesson,$user)
{
            
    $lessonWords = $lesson->word_count;

    $my_words = $user->calculateCurrentWords();

    $my_words = $lessonWords + $my_words;



    if (! $user->hasActiveSubscription()) {
        if ($my_words > 60) {

            return 0; // its mean you need to subscribe

        }

        $user->lessons()->attach($lesson->id);

    }

    if ($user->hasActiveSubscription()) {
        $subscribe_id = $user->activeSubscription()->pivot->id;
        $user->lessons()->attach($lesson->id,['subscribe_id'=>$subscribe_id]); 
    }


    $user->update( [ 
        'my_words' => $user->calculateCurrentWords() ,
        'all_words' => $user->calculateWords() ,
    ]);


    return 1;
}

/*
|--------------------------------------------------------------------------
| Upload File
|--------------------------------------------------------------------------
*/
function uploadFile($file,$path)
{
    $name = date('mdYHis').uniqid().$file->getClientOriginalName();

    $file->storeAs($path, $name);

    return 'file/'.$path.'/'.$name;

}