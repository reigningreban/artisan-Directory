<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::get('/dservices','apiController@getservices');

Route::group(['prefix' => 'c2568'], function(){
    // Route::get('/user', function( Request $request ){
    //   return $request->user();
    // });
  
    //all services
    Route::get('/services', 'apiController@getservices');
    //random artisans
    Route::get('/randartisan', 'apiController@random');
    //all artisans
    Route::get('/artisans', 'apiController@getartisans');
    //search
    Route::get('/search', 'apiController@search');
    //nearby artisans
    Route::post('/nearby', 'apiController@closeartisans');
    // search nearby artisans
    Route::post('/nearbysearch', 'apiController@closesearch');
    // all states
    Route::get('/states', 'apiController@getstates');
    // all cities
    Route::get('/cities', 'apiController@getcities');
    //artisan
    Route::post('/artisan','apiController@artisan');
    //artisan login
    Route::post('/artisan_login','apiController@artisanlogin');
    //artisan signup
    Route::post('/artisan_signup','apiController@artisansignup');
    //edit profile
    Route::post('/artisan_editprofile','apiController@profedit');
    //change password
    Route::post('/artisan_changepass','apiController@changepass');
    //change password
    Route::post('/artisan_picupload','apiController@artisanpicupload');




    //agent
    //Agent apply
    Route::post('/agent_apply','apiController@agentapply');

    //Agent Login
    Route::post('/agent_login','apiController@agentlogin');
    //agent
    Route::post('/agent','apiController@agent');
    //Agent add artisan
    Route::post('/agent_add_artisan','apiController@addartisan');
    //Agent add artisan
    Route::post('/agent_myartisans','apiController@myartisans');
    //Agent upload pic 
    Route::post('/agent_picupload','apiController@agentpicupload');
    

    //
    Route::post('/agent_artpicupload','apiController@myartisanpicupload');

    Route::post('/agent_myedit','apiController@editmyprofile');

    Route::post('/agent_editartisan','apiController@editartisan');

    Route::post('/agent_logout','apiController@agentlogout');

    Route::post('/artisan_logout','apiController@artisanlogout');
    Route::fallback(function(){
        return response()->json([
            'message' => 'Page Not Found. If error persists, contact info@obounce.net'], 404);
    });
  });
  