<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\SubjectController;
use App\Http\Controllers\Frontend\LessonController;
use App\Http\Controllers\Frontend\PlanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/file/{path}', function ($path) {
// return Hash::make("12345678");
	$pathToFile = storage_path('app/'.$path);

	// return response()->download($pathToFile, null, [], null);


	if (file_exists($pathToFile)) {
		return response()->file($pathToFile);
	}

	return abort(404);

    
})->where('path', '.*');

Route::get('/', [HomeController::class, 'index'])->middleware('auth')->name('index');

Route::middleware(['auth','role:student','has-topics'])->group(function () {

	Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
	Route::get('/course', [HomeController::class, 'course'])->name('course');
	Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
	Route::get('/plan', [PlanController::class, 'index'])->name('plan');
	Route::get('/payment/{plan_id}', [PlanController::class, 'payForm'])->name('pay');
	Route::get('/subscribe/{id}', [PlanController::class, 'subscribe'])->name('subscribe');
	Route::get('/lesson/{id}', [LessonController::class, 'show'])->name('show-lesson');

});

Route::get('/topics', [SubjectController::class, 'index'])->middleware(['auth','role:student'])->name('index');


require __DIR__.'/auth.php';


