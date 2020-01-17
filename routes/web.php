<?php

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
Route::get('/test','artisanController@tester');

Route::view('/artisan/signup','artisan/signup');
Route::view('/artisan/login','artisan/login');
Route::post('/artisan/login','artisanController@login');
Route::post('artisan/signup','artisanController@signup');
Route::get('artisan/states','artisanController@getstates');
Route::get('artisan/cities/{state_id}','artisanController@getcities');
Route::get('artisan/services','artisanController@getservices');
