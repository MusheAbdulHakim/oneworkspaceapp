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

Route::prefix('gohighlevel')->group(function() {
    Route::get('/', 'GoHighLevelController@index');
    Route::get('redirect', 'AuthController@redirect')->name('gohighlevel.redirect');
});

Route::get('gohigh-oauth/callback', 'AuthController@callback')->name('gohighlevel.callback');

