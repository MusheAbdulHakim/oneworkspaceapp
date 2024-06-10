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


Route::prefix('ghl')->group(function() {
    Route::group(['middleware' => 'auth'], function(){
        Route::get("dashboard", "GHLController@dashboard")->name('ghl.dashboard');
        Route::get("contacts", "ContactsController@index")->name('ghl.contacts');
        Route::get("calendars", "CalendarController@index")->name('ghl.calendars');
        Route::get("invoices", "InvoicesController@index")->name('ghl.invoices');
        Route::post('/settings/store', "\SuperAdmin\SettingsController@store")->name('ghl.setting.store');
        
    });
    Route::get('/', 'GHLController@index')->name('ghl.index');
    Route::get('redirect', 'GHLController@redirect')->name('ghl.redirect');
});
Route::get('oauth/callback/gohigh', 'GHLController@callback')->name('ghl.callback');

