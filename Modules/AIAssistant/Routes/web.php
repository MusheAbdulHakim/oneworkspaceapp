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
use Modules\AIAssistant\Http\Controllers\AIAssistantController;
Route::group(['middleware' => 'PlanModuleCheck:AIAssistant'], function ()
{
    Route::prefix('aiassistant')->group(function() {
		
		Route::get('/generate/{template_module}/{module}', [AIAssistantController::class, 'create'])->name('aiassistant.generate')->middleware(['auth']);
		Route::post('/generate/keywords/{id}', [AIAssistantController::class, 'GetKeywords'])->name('aiassistant.generate.keywords')->middleware(['auth']);
		Route::any('/generate/response', [AIAssistantController::class, 'AiGenerate'])->name('aiassistant.generate.response')->middleware(['auth']);
		Route::any('/generate/{template_module}/{module}/{id}', [AIAssistantController::class, 'vcard_create_business'])->name('aiassistant.generate_business')->middleware(['auth']);
		Route::any('/generate_service/{template_module}/{module}/{id}', [AIAssistantController::class, 'vcard_create_service'])->name('aiassistant.generate_vcard_service')->middleware(['auth']);
		Route::any('/generate_testimonial/{template_module}/{module}/{id}', [AIAssistantController::class, 'vcard_create_testimonial'])->name('aiassistant.generate_vcard_testimonial')->middleware(['auth']);
		Route::any('/cmms-generate/{template_module}/{module}', [AIAssistantController::class, 'cmms_create'])->name('cmms_aiassistant.generate')->middleware(['auth']);
	


    });
});
