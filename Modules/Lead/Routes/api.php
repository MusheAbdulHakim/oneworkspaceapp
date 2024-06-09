<?php

use Illuminate\Http\Request;

use Modules\Lead\Http\Controllers\Api\HomeApiController;
use Modules\Lead\Http\Controllers\Api\LeadApiController;
use Modules\Lead\Http\Controllers\Api\LeadStageApiController;
use Modules\Lead\Http\Controllers\Api\PipelineApiController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/lead', function (Request $request) {
    return $request->user();
});

Route::prefix('Lead')->group(function () {
    Route::middleware(['jwt.api.auth'])->group(function () {

        Route::post('home',[HomeApiController::class,'index']);

        // Route::post('get-workspace-users',[HomeApiController::class,'getWorkspaceUsers']);

        Route::post('pipelines',[PipelineApiController::class,'index']);

        Route::post('pipeline-create-update',[PipelineApiController::class,'store']);

        Route::post('lead-stages',[LeadStageApiController::class,'index']);

        Route::post('lead-stage-create-update',[LeadStageApiController::class,'store']);

        Route::post('leadboard',[LeadApiController::class,'leadboard']);

        Route::post('lead-create-update',[LeadApiController::class,'store']);

        Route::post('lead-details',[LeadApiController::class,'leadDetails']);

        Route::post('lead-delete',[LeadApiController::class,'destroy']);

        Route::post('lead-stage-update',[LeadApiController::class,'leadStageUpdate']);

    });
});
