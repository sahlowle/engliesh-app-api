<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\TopicController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | User Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/get-profile', [UserController::class, 'show']);
    Route::post('/update-profile', [UserController::class, 'update']);
    Route::post('/change-password', [UserController::class, 'changePassword']);

    Route::get('/topics', [TopicController::class, 'index']);
    Route::get('/topics/user', [TopicController::class, 'userTopics']);
    Route::post('/add-topics/user', [TopicController::class, 'AddUserTopics']);

    Route::get('/lessons/user', [LessonController::class, 'myLessons']);
    Route::get('/lessons/{id}', [LessonController::class, 'show']);
    Route::get('/lessons/topic', [LessonController::class, 'lessonByTopic']);
    Route::get('/translate', [LessonController::class, 'translate']);

    Route::post('/add-known-word', [LessonController::class, 'addKnownWord']);
    Route::post('/add-learning-word', [LessonController::class, 'addLearningWord']);

    Route::get('/plans', [PlanController::class, 'index']);
    Route::post('/plans/subscribe', [PlanController::class, 'subscribe']);
    
});
