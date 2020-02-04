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
Route::get('/artisan/login',function ()
{
    if (session()->exists('artisan')) {
        return redirect('/artisan/dashboard');
    }else {
        return view('artisan/login');
    }
});
Route::post('/artisan/login','artisanController@login');
Route::post('artisan/signup','artisanController@signup');
Route::post('/artisan/editprofile','artisanController@editprofile');
Route::post('/artisan/editdes','artisanController@editdescription');
Route::post('/artisan/changepass','artisanController@changepass');
Route::post('/artisan/picupload','artisanController@picupload');
Route::get('artisan/states','artisanController@getstates');
Route::get('artisan/statesedit','artisanController@getstatesedit');
Route::get('artisan/cities/{state_id}','artisanController@getcities');
Route::get('artisan/citiesedit','artisanController@getcitiesedit');
Route::get('/test','artisanController@tester');
Route::get('artisan/services','artisanController@getservices');
Route::get('artisan/servicesedit','artisanController@getservicesedit');
Route::view('/artisans','artisans');
Route::get('/artisan/dashboard','artisanController@dashboard');
Route::get('/randartisan','onesearchController@randomartisan');
Route::get('/services','onesearchController@getservices');
Route::get('/getartisans','onesearchController@artisans');
Route::get('/getnearartisans/{lat}/{lon}','onesearchController@nearartisans');
Route::get('/artisans/search/{stuff}','onesearchController@search');
Route::post('/search','onesearchController@searchtoartisans');
Route::get('/search/{stuff}','onesearchController@clicktoartisans');
Route::get('/artisans/closesearch/{stuff}/{lat}/{lon}','onesearchController@closesearch');
Route::get('/artisan/logout','artisanController@logout');
Route::get('/artisan/editprofile','artisanController@getprofile');

Route::get('/admin/login',function ()
{
    if (session()->exists('admin')) {
        return redirect('/admin/dashboard');
    }else {
        return view('admin/login');
    }
});
Route::post('admin/login','adminController@login');
Route::get('admin/dashboard','adminController@dashboard');
Route::get('admin',function ()
{
    return redirect('admin/dashboard');
});
Route::get('/admin/disable/{id}','adminController@disable');
Route::get('/admin/enable/{id}','adminController@enable');
Route::get('/admin/approve/{id}','admincontroller@approve');
Route::get('/admin/disapprove/{id}','admincontroller@disapprove');
Route::get('/admin/profile/{id}','admincontroller@artisan');
