<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TopicController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth','role:admin'])->group(function () {

	Route::get('/', [AdminController::class, 'index'])->name('admin-index');

	Route::get('/topics', [TopicController::class, 'index'])->name('admin-topics');

	Route::get('/lessons', [LessonController::class, 'index'])->name('admin-lessons');

	Route::get('/lessons/create', [LessonController::class, 'create'])->name('create-lesson');

	Route::post('/lessons', [LessonController::class, 'store'])->name('lessons');

	Route::get('/students', [StudentController::class, 'index'])->name('admin-students');

	Route::get('/users', [AdminController::class, 'getUsers'])->name('admin-users');

});



