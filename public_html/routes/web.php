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
Route::get('artisan',function ()
{
    return redirect('artisan/dashboard');
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
Route::get('/getservices','onesearchController@getservices');
Route::get('/services','onesearchController@services');
Route::get('/getartisans','onesearchController@artisans');
Route::get('/getnearartisans/{lat}/{lon}','onesearchController@nearartisans');
Route::get('/artisans/search/{stuff}','onesearchController@search');
Route::post('/search','onesearchController@searchtoartisans');
Route::get('/search/{stuff}','onesearchController@clicktoartisans');
Route::get('/artisans/closesearch/{stuff}/{lat}/{lon}','onesearchController@closesearch');
Route::get('/artisans/{slug}','onesearchController@artprofile');
Route::get('/artisan/logout','artisanController@logout');
Route::get('/artisan/editprofile','artisanController@getprofile');

Route::get('/qzwf/login',function ()
{
    if (session()->exists('admin')) {
        return redirect('/qzwf/dashboard');
    }else {
        return view('qzwf/login');
    }
});
Route::post('/qzwf/login','adminController@login');
Route::get('/qzwf/logout','adminController@logout');
Route::get('/qzwf/dashboard','adminController@dashboard');
Route::get('qzwf',function ()
{
    return redirect('qzwf/dashboard');
});
Route::get('/qzwf/disable/{id}','adminController@disable');
Route::get('/qzwf/enable/{id}','adminController@enable');

Route::get('/qzwf/agentdisable/{id}','adminController@agentdisable');
Route::get('/qzwf/agentenable/{id}','adminController@agentenable');
Route::get('/qzwf/approve/{id}','admincontroller@approve');
Route::get('/qzwf/disapprove/{id}','admincontroller@disapprove');
Route::get('/qzwf/agentapprove/{id}','admincontroller@agentapprove');
Route::get('/qzwf/agentdisapprove/{id}','admincontroller@agentdisapprove');
Route::get('/qzwf/profile/{id}','admincontroller@artisan');
Route::get('/qzwf/agentprofile/{id}','admincontroller@agent');


Route::get('/agent/login',function ()
{
    if (session()->exists('agent')) {
        return redirect('/agent/dashboard');
    }else {
        return view('agent/login');
    }
});
Route::get('agent',function ()
{
    return redirect('agent/dashboard');
});
Route::view('/agent/apply','/agent/apply');
Route::get('/agent/logout','AgentController@logout');
Route::post('/agent/apply','AgentController@apply');
Route::post('/agent/login','AgentController@login');
Route::get('/agent/dashboard','AgentController@dashboard');
Route::view('/agent/addartisan','/agent/addartisans');
Route::post('/agent/addartisan','AgentController@addartisan');
Route::get('/agent/myartisans','AgentController@myartisans');
Route::get('/agent/myartisans/{slog}','AgentController@artisanprofile');
Route::get('/agent/editartisan/{slog}','AgentController@showartisan');
Route::post('/agent/editartisan/{slog}','AgentController@editartisan');
Route::get('/agent/editartisan/{slog}/statesedit','AgentController@statesedit');
Route::get('/agent/editartisan/{slog}/citiesedit','AgentController@citiesedit');
Route::get('/agent/editartisan/{slog}/statesedit','AgentController@statesedit');
Route::get('/agent/citiesedit','AgentController@mycitiesedit');
Route::get('/agent/statesedit','AgentController@mystatesedit');
Route::post('/agent/picupload','AgentController@picupload');
Route::post('/agent/artisanpicupload/{slog}','AgentController@artisanpicupload');
Route::get('/agent/editprofile','AgentController@showmyprofile');
Route::post('/agent/editprofile','AgentController@editmyprofile');

Route::view('/mytest','emails/Agentapproved');
// Route::get('/mytest','adminController@test');



