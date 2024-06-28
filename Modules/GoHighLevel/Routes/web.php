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

use Modules\GoHighLevel\Http\Controllers\CampaignsController;
use Modules\GoHighLevel\Http\Controllers\ContactsController;
use Modules\GoHighLevel\Http\Controllers\AuthController;
use Modules\GoHighLevel\Http\Controllers\CalendarController;
use Modules\GoHighLevel\Http\Controllers\DashboardController;
use Modules\GoHighLevel\Http\Controllers\FunnelsController;
use Modules\GoHighLevel\Http\Controllers\GoHighLevelController;
use Modules\GoHighLevel\Http\Controllers\InvoicesController;

Route::prefix('gohighlevel')->middleware(['auth','checkGhlApiKey'])->group(function() {
    Route::get('/', [GoHighLevelController::class, 'index']);
    Route::get('redirect', [AuthController::class,'redirect'])->name('gohighlevel.redirect');

    Route::get("dashboard", [DashboardController::class, 'dashboard'])->name('gohighlevel.dashboard');
    Route::get("campaigns", [CampaignsController::class, 'index'])->name('gohighlevel.campaigns');
    Route::get("contacts", [ContactsController::class, 'index'])->name('gohighlevel.contacts');
    Route::get("contacts/appointments/{contactId}", [ContactsController::class, 'appointments'])->name('gohighlevel.contact.appointments');
    Route::get("contacts/tasks/{contactId}", [ContactsController::class, 'tasks'])->name('gohighlevel.contact.tasks');
    Route::get("contacts/notes/{contactId}", [ContactsController::class, 'notes'])->name('gohighlevel.contact.notes');
    Route::get("calendars", [CalendarController::class, 'index'])->name('gohighlevel.calendars');
    Route::get("calendars/events", [CalendarController::class, 'events'])->name('gohighlevel.calendars.events');
    Route::get("calendars/slots/{calendarId}", [CalendarController::class, 'slots'])->name('gohighlevel.calendar.slots');
    Route::get("invoices", [InvoicesController::class, 'index'])->name('gohighlevel.invoices');
    Route::get("funnels", [FunnelsController::class, "index"])->name('gohighlevel.funnels');
    Route::get("funnel/pages/{funnelId}", [FunnelsController::class,"pages"])->name('gohighlevel.funnel.pages');
});

Route::get('gohigh-oauth/callback', [AuthController::class, 'callback'])->name('gohighlevel.callback');

