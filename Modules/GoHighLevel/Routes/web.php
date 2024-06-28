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

Route::prefix('gohighlevel')->middleware(['auth','checkGhlApiKey'])->group(function() {
    Route::get('/', 'GoHighLevelController@index');
    Route::get('redirect', 'AuthController@redirect')->name('gohighlevel.redirect');

    Route::get("dashboard", "Dashboardcontroller@dashboard")->name('gohighlevel.dashboard');
    Route::get("campaigns", "CampaignsController@index")->name('gohighlevel.campaigns');
    Route::get("contacts", "ContactsController@index")->name('gohighlevel.contacts');
    Route::get("contacts/appointments/{contactId}", "ContactsController@appointments")->name('gohighlevel.contact.appointments');
    Route::get("contacts/tasks/{contactId}", "ContactsController@tasks")->name('gohighlevel.contact.tasks');
    Route::get("contacts/notes/{contactId}", "ContactsController@notes")->name('gohighlevel.contact.notes');
    Route::get("calendars", "CalendarController@index")->name('gohighlevel.calendars');
    Route::get("calendars/events", "CalendarController@events")->name('gohighlevel.calendars.events');
    Route::get("calendars/slots/{calendarId}", "CalendarController@slots")->name('gohighlevel.calendar.slots');
    Route::get("invoices", "InvoicesController@index")->name('gohighlevel.invoices');
    Route::get("funnels", "FunnelsController@index")->name('gohighlevel.funnels');
    Route::get("funnel/pages/{funnelId}", "FunnelsController@pages")->name('gohighlevel.funnel.pages');
});

Route::get('gohigh-oauth/callback', 'AuthController@callback')->name('gohighlevel.callback');

