<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->middleware('auth')->name('home');

Route::resource('/user', 'App\Http\Controllers\UserController');


Route::prefix('/register-mahasiswa')->group(function () {
    Route::get('/', 'App\Http\Controllers\UserController@registerMahasiswaCreate')->name('register.mahasiswa');
    Route::post('/', 'App\Http\Controllers\UserController@registerMahasiswaStore')->name('register.mahasiswa.store');
});

Route::prefix('loop')->middleware('auth')->group(function () {
    Route::resource('/category', 'App\Http\Controllers\CategoryController');

    Route::resource('/question', 'App\Http\Controllers\QuestionController');
    Route::get('/question/{id}/delete-answer', 'App\Http\Controllers\QuestionController@questionDelete')->name('question.answer.delete');
    Route::get('/question/{id}/check-answer', 'App\Http\Controllers\QuestionController@questionCheck')->name('question.answer.check');
    Route::get('/exercise/reset', 'App\Http\Controllers\ExerciseController@exerciseReset')->name('exercise.reset.all');
    Route::get('/exercise/recovery', 'App\Http\Controllers\ExerciseController@exerciseRecovery')->name('exercise.recovery');
    Route::resource('/exercise', 'App\Http\Controllers\ExerciseController');
    Route::get('/preview', 'App\Http\Controllers\ExerciseController@preview')->name('preview');
    Route::post('/preview', 'App\Http\Controllers\ExerciseController@exercisePreviewCheck')->name('previewcheck');
    Route::post('/exercise/check', 'App\Http\Controllers\ExerciseController@exerciseCheck')->name('exercise.check');
    Route::post('/exercise/result', 'App\Http\Controllers\ExerciseController@calculateScore')->name('exercise.result');
    Route::post('/exercise/result', 'App\Http\Controllers\ExerciseController@show')->name('exercise.hasil');
    Route::resource('/log', 'App\Http\Controllers\LogController');
    Route::resource('/confidence', 'App\Http\Controllers\ConfidenceController');
    Route::get('/confidence/{user}', 'App\Http\Controllers\ConfidenceController@show')->name('confidence.category.result');
    Route::get('/confidence/{user}/{categoryId}', 'App\Http\Controllers\ConfidenceController@PerQuestion')->name('confidence.category.question.result');

    Route::get('/log-category-result/{user}', 'App\Http\Controllers\CategoryResultController@index')->name('log.category.result');


    Route::get('/log-activity-last-result/{user}/{categoryId}', 'App\Http\Controllers\LastLogResultController@index')->name('log.last.result');
    Route::get('/log-activity-last-result/{user}/{categoryId}/{questionId}', 'App\Http\Controllers\LastLogResultController@index')->name('log.last.resulti');




    // Route::delete('/confidence/{id}', 'App\Http\Controllers\ConfidenceController@destroy')->name('confidence.destroy');



    Route::delete('/log-activity-last-result/{user}/{category}', 'App\Http\Controllers\LastLogResultController@destroy')->name('lastlogresult.destroy');
});
