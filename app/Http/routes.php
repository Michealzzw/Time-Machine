<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/home', 'HomeController@index');
Route::get('/selectEventType', 'HomeController@selectEventType');
Route::get('/showEventType', 'HomeController@showEventType');
Route::get('/showEventType/{id}', 'HomeController@showFromParentEventType');
Route::get('/addEventType/{id}', 'HomeController@addEventType');
Route::get('/completeEvent', 'HomeController@completeEvent');
Route::post('/addEvent', 'HomeController@addEvent');
Route::get('/addEvent/{id}', 'HomeController@addInvitedEvent');
Route::get('/endEvent', 'HomeController@endEvent');
Route::get('/events', 'HomeController@showEvents');
Route::get('/friends', 'HomeController@showFriends');
Route::get('/findFriend', 'HomeController@findFriend');
Route::get('/addFriend/{id}', 'HomeController@addFriend');
Route::get('/deleteFriend/{id}', 'HomeController@deleteFriend');
Route::get('/deleteRequest/{id}', 'HomeController@deleteRequest');
Route::get('/addRequest/{id}', 'HomeController@addRequest');
