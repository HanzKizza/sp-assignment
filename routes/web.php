<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('home');
});

Route::post('/filePreview', 'fileController@filePreview')->name('filePreview');

Route::post('/uploadStudents', 'fileController@uploadStudents')->name('uploadStudents');

Route::get('/viewMarks', 'viewController@viewMarks')->name('viewMarks');


//Ive done this cos I don wanna edit the initial route
Route::get('/uploadMarks', function(){
    return view('home');
})->name('uploadMarks');

