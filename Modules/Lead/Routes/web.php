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
use Illuminate\Support\Facades\Route;
use Modules\Lead\Http\Controllers\LeadController;
use Modules\Lead\Http\Controllers\PipelineController;
use Modules\Lead\Http\Controllers\DealController;
use Modules\Lead\Http\Controllers\LeadStageController;
use Modules\Lead\Http\Controllers\DealStageController;
use Modules\Lead\Http\Controllers\LabelController;
use Modules\Lead\Http\Controllers\SourceController;
use Modules\Lead\Http\Controllers\ReportController;


// Route::prefix('lead')->group(function() {
//     Route::get('/', 'LeadController@index');
// });
Route::group(['middleware' => 'PlanModuleCheck:Lead'], function ()
{
    Route::middleware(['auth'])->group(function () {

        Route::resource('leads', LeadController::class);
        Route::get('dashboard/crm', [LeadController::class, 'dashboard'])->name('lead.dashboard');

        Route::resource('pipelines', PipelineController::class);

        Route::post('/deals/change-pipeline', [DealController::class, 'changePipeline'])->name('deals.change.pipeline');

        Route::get('/leads-list', [LeadController::class, 'lead_list'])->name('leads.list');

        Route::resource('lead-stages', LeadStageController::class);
        Route::post('/lead_stages/order', [LeadStageController::class, 'order'])->name('lead_stages.order');

        Route::resource('deal-stages', DealStageController::class);
        Route::post('/deal_stages/order', [DealStageController::class, 'order'])->name('deal-stages.order');

        Route::resource('labels', LabelController::class);
        Route::resource('sources', SourceController::class);

        Route::get('/leads-deals/dashboard', [LeadController::class, 'dashboard'])->name('leads.dashboard');
        Route::post('/leads/order', [LeadController::class, 'order'])->name('leads.order');
        Route::post('/leads/json', [LeadController::class, 'json'])->name('leads.json');
        Route::post('/leads/{id}/file', [LeadController::class, 'fileUpload'])->name('leads.file.upload');
        Route::get('/leads/{id}/file/{fid}', [LeadController::class, 'fileDownload'])->name('leads.file.download');
        Route::delete('/leads/{id}/file/delete/{fid}', [LeadController::class, 'fileDelete'])->name('leads.file.delete');
        Route::post('/leads/{id}/note', [LeadController::class, 'noteStore'])->name('leads.note.store');
        Route::get('/leads/{id}/labels', [LeadController::class, 'labels'])->name('leads.labels');
        Route::post('/leads/{id}/labels', [LeadController::class, 'labelStore'])->name('leads.labels.store');
        Route::get('/leads/{id}/users', [LeadController::class, 'userEdit'])->name('leads.users.edit');
        Route::put('/leads/{id}/users', [LeadController::class, 'userUpdate'])->name('leads.users.update');
        Route::delete('/leads/{id}/users/{uid}', [LeadController::class, 'userDestroy'])->name('leads.users.destroy');
        Route::get('/leads/{id}/products', [LeadController::class, 'productEdit'])->name('leads.products.edit');
        Route::put('/leads/{id}/products', [LeadController::class, 'productUpdate'])->name('leads.products.update');
        Route::delete('/leads/{id}/products/{uid}', [LeadController::class, 'productDestroy'])->name('leads.products.destroy');
        Route::get('/leads/{id}/sources', [LeadController::class, 'sourceEdit'])->name('leads.sources.edit');
        Route::put('/leads/{id}/sources', [LeadController::class, 'sourceUpdate'])->name('leads.sources.update');
        Route::delete('/leads/{id}/sources/{uid}', [LeadController::class, 'sourceDestroy'])->name('leads.sources.destroy');
        Route::get('/leads/{id}/discussions', [LeadController::class, 'discussionCreate'])->name('leads.discussions.create');
        Route::post('/leads/{id}/discussions', [LeadController::class, 'discussionStore'])->name('leads.discussion.store');
        Route::get('/leads/{id}/show_convert', [LeadController::class, 'showConvertToDeal'])->name('leads.convert.deal');
        Route::post('/leads/{id}/convert', [LeadController::class, 'convertToDeal'])->name('leads.convert.to.deal');

        Route::get('/leads/{id}/call', [LeadController::class, 'callCreate'])->name('leads.calls.create');
        Route::post('/leads/{id}/call', [LeadController::class, 'callStore'])->name('leads.calls.store');
        Route::get('/leads/{id}/call/{cid}/edit', [LeadController::class, 'callEdit'])->name('leads.calls.edit');
        Route::put('/leads/{id}/call/{cid}', [LeadController::class, 'callUpdate'])->name('leads.calls.update');
        Route::delete('/leads/{id}/call/{cid}', [LeadController::class, 'callDestroy'])->name('leads.calls.destroy');

        // Lead Email
        Route::get('/leads/{id}/email', [LeadController::class, 'emailCreate'])->name('leads.emails.create');
        Route::post('/leads/{id}/email', [LeadController::class, 'emailStore'])->name('leads.emails.store');

        //Lead import
        Route::get('lead/import/export', [LeadController::class, 'fileImportExport'])->name('lead.file.import');
        Route::post('lead/import', [LeadController::class, 'fileImport'])->name('lead.import');
        Route::get('lead/import/modal', [LeadController::class, 'fileImportModal'])->name('lead.import.modal');
        Route::post('lead/data/import/', [LeadController::class, 'leadImportdata'])->name('lead.import.data');

        // Lead Task
        Route::get('/leads/{id}/task', [LeadController::class, 'taskCreate'])->name('leads.tasks.create');
        Route::post('/leads/{id}/task', [LeadController::class, 'taskStore'])->name('leads.tasks.store');
        Route::get('/leads/{id}/task/{tid}/edit', [LeadController::class, 'taskEdit'])->name('leads.tasks.edit');
        Route::put('/leads/{id}/task/{tid}', [LeadController::class, 'taskUpdate'])->name('leads.tasks.update');
        Route::put('/leads/{id}/task_status/{tid}', [LeadController::class, 'taskUpdateStatus'])->name('leads.tasks.update.status');
        Route::delete('/leads/{id}/task/{tid}', [LeadController::class, 'taskDestroy'])->name('leads.tasks.destroy');

        // Deal Module
        Route::post('/deals/user', [DealController::class, 'jsonUser'])->name('deal.user.json');
        Route::post('/deals/order', [DealController::class, 'order'])->name('deals.order');
        Route::post('/deals/change-pipeline', [DealController::class, 'changePipeline'])->name('deals.change.pipeline');
        Route::post('/deals/change-deal-status/{id}', [DealController::class, 'changeStatus'])->name('deals.change.status');
        Route::get('/deals/{id}/labels', [DealController::class, 'labels'])->name('deals.labels');
        Route::post('/deals/{id}/labels', [DealController::class, 'labelStore'])->name('deals.labels.store');
        Route::get('/deals/{id}/users', [DealController::class, 'userEdit'])->name('deals.users.edit');
        Route::put('/deals/{id}/users', [DealController::class, 'userUpdate'])->name('deals.users.update');
        Route::delete('/deals/{id}/users/{uid}', [DealController::class, 'userDestroy'])->name('deals.users.destroy');
        Route::get('/deals/{id}/clients', [DealController::class, 'clientEdit'])->name('deals.clients.edit');
        Route::put('/deals/{id}/clients', [DealController::class, 'clientUpdate'])->name('deals.clients.update');
        Route::delete('/deals/{id}/clients/{uid}', [DealController::class, 'clientDestroy'])->name('deals.clients.destroy');
        Route::get('/deals/{id}/products', [DealController::class, 'productEdit'])->name('deals.products.edit');
        Route::put('/deals/{id}/products', [DealController::class, 'productUpdate'])->name('deals.products.update');
        Route::delete('/deals/{id}/products/{uid}', [DealController::class, 'productDestroy'])->name('deals.products.destroy');
        Route::get('/deals/{id}/sources', [DealController::class, 'sourceEdit'])->name('deals.sources.edit');
        Route::put('/deals/{id}/sources', [DealController::class, 'sourceUpdate'])->name('deals.sources.update');
        Route::delete('/deals/{id}/sources/{uid}', [DealController::class, 'sourceDestroy'])->name('deals.sources.destroy');
        Route::post('/deals/{id}/file', [DealController::class, 'fileUpload'])->name('deals.file.upload');
        Route::get('/deals/{id}/file/{fid}', [DealController::class, 'fileDownload'])->name('deals.file.download');
        Route::delete('/deals/{id}/file/delete/{fid}', [DealController::class, 'fileDelete'])->name('deals.file.delete');
        Route::post('/deals/{id}/note', [DealController::class, 'noteStore'])->name('deals.note.store');
        Route::get('/deals/{id}/task', [DealController::class, 'taskCreate'])->name('deals.tasks.create');
        Route::post('/deals/{id}/task', [DealController::class, 'taskStore'])->name('deals.tasks.store');
        Route::get('/deals/{id}/task/{tid}/show', [DealController::class, 'taskShow'])->name('deals.tasks.show');
        Route::get('/deals/{id}/task/{tid}/edit', [DealController::class, 'taskEdit'])->name('deals.tasks.edit');
        Route::put('/deals/{id}/task/{tid}', [DealController::class, 'taskUpdate'])->name('deals.tasks.update');
        Route::put('/deals/{id}/task_status/{tid}', [DealController::class, 'taskUpdateStatus'])->name('deals.tasks.update_status');
        Route::delete('/deals/{id}/task/{tid}', [DealController::class, 'taskDestroy'])->name('deals.tasks.destroy');
        Route::get('/deals/{id}/discussions', [DealController::class, 'discussionCreate'])->name('deals.discussions.create');
        Route::post('/deals/{id}/discussions', [DealController::class, 'discussionStore'])->name('deals.discussion.store');
        Route::get('/deals/{id}/permission/{cid}', [DealController::class, 'permission'])->name('deals.client.permission');
        Route::put('/deals/{id}/permission/{cid}', [DealController::class, 'permissionStore'])->name('deals.client.permissions.store');
        Route::get('/deals/list', [DealController::class, 'deal_list'])->name('deals.list');

        // Deal Calls
        Route::get('/deals/{id}/call', [DealController::class, 'callCreate'])->name('deals.calls.create');
        Route::post('/deals/{id}/call', [DealController::class, 'callStore'])->name('deals.calls.store');
        Route::get('/deals/{id}/call/{cid}/edit', [DealController::class, 'callEdit'])->name('deals.calls.edit');
        Route::put('/deals/{id}/call/{cid}', [DealController::class, 'callUpdate'])->name('deals.calls.update');
        Route::delete('/deals/{id}/call/{cid}', [DealController::class, 'callDestroy'])->name('deals.calls.destroy');

        // Deal Email
        Route::get('/deals/{id}/email', [DealController::class, 'emailCreate'])->name('deals.emails.create');
        Route::post('/deals/{id}/email', [DealController::class, 'emailStore'])->name('deals.emails.store');

        Route::resource('deals', DealController::class);

        // end Deal Module

        Route::post('/stages/json', [DealStageController::class, 'json'])->name('stages.json');

        // Deal import
        Route::get('deal/import/export', [DealController::class, 'fileImportExport'])->name('deal.file.import');
        Route::post('deal/import', [DealController::class, 'fileImport'])->name('deal.import');
        Route::get('deal/import/modal', [DealController::class, 'fileImportModal'])->name('deal.import.modal');
        Route::post('deal/data/import/', [DealController::class, 'dealImportdata'])->name('deal.import.data');

        // Reports
        Route::get('lead-report', [ReportController::class,'leadReport'])->name('report.lead');
        Route::get('deal-report', [ReportController::class,'dealReport'])->name('report.deal');
    });
});
