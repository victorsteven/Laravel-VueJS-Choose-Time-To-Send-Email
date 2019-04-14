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
    return view('welcome');
});

Route::get('/get_users', 'MessageController@getUsers');
Route::get('/get_messages', 'MessageController@getMessages');
Route::post('notifications', 'MessageController@sendMail');



