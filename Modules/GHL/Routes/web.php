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


Route::get('dashboard/ghl',"DashboardController@index")->name('ghl.dashboard')->middleware(['auth']);
Route::prefix('ghl')->group(function() {
    Route::group(['middleware' => 'auth'], function(){
        Route::get("dashboard", "GHLController@dashboard");
        Route::get("contacts", "ContactsController@index")->name('ghl.contacts');
        Route::get("contacts/appointments/{contactId}", "ContactsController@appointments")->name('ghl.contact.appointments');
        Route::get("contacts/tasks/{contactId}", "ContactsController@tasks")->name('ghl.contact.tasks');
        Route::get("contacts/notes/{contactId}", "ContactsController@notes")->name('ghl.contact.notes');
        Route::get("calendars", "CalendarController@index")->name('ghl.calendars');
        Route::get("calendars/slots/{calendarId}", "CalendarController@slots")->name('ghl.calendar.slots');
        Route::get("invoices", "InvoicesController@index")->name('ghl.invoices');
        Route::get("funnels", "FunnelsController@index")->name('ghl.funnels');
        Route::get("funnel/pages/{funnelId}", "FunnelsController@pages")->name('ghl.funnel.pages');
        Route::post('/settings/store', "\SuperAdmin\SettingsController@store")->name('ghl.setting.store');

    });
    Route::get('/', 'GHLController@index')->name('ghl.index');
    Route::get('redirect', 'GHLController@redirect')->name('ghl.redirect');
});
Route::get('oauth/callback/gohigh', 'GHLController@callback')->name('ghl.callback');

