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

Route::any('/', function () {
    return view('admin');
});

Route::get('auth/github', 'Api\AuthController@redirectToProvider');
Route::get('auth/github/callback', 'Api\AuthController@handleProviderCallback');
