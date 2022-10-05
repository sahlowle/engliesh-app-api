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

require __DIR__.'/auth.php';


