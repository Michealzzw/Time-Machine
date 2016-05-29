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
Route::get('/selectEventType', 'EventController@selectEventType');
Route::get('/showEventType', 'EventController@showEventType');
Route::get('/showEventType/{id}', 'EventController@showFromParentEventType');
Route::get('/addEventType/{id}', 'EventController@addEventType');
Route::get('/completeEvent', 'EventController@completeEvent');
Route::post('/addEvent', 'EventController@addEvent');
Route::get('/addEvent/{id}', 'InvitationController@addInvitedEvent');
Route::get('/endEvent', 'EventController@endEvent');
Route::get('/events', 'EventController@showEvents');
Route::get('/showEvent/{id}', 'EventController@showEvent');
Route::get('/friends', 'FriendController@showFriends');
Route::get('/findFriend', 'FriendController@findFriend');
Route::get('/addFriend/{id}', 'FriendController@addFriend');
Route::get('/deleteFriend/{id}', 'FriendController@deleteFriend');
Route::get('/deleteRequest/{id}', 'FriendController@deleteRequest');
Route::get('/addRequest/{id}', 'FriendController@addRequest');
Route::get('/statistic', 'StatisticController@showStatistic');
