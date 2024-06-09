<?php

use Illuminate\Http\Request;
use Modules\Hrm\Http\Controllers\Api\HolidaylistApiController;
use Modules\Hrm\Http\Controllers\Api\AttendanceApiController;
use Modules\Hrm\Http\Controllers\Api\LeaveApiController;
use Modules\Hrm\Http\Controllers\Api\LeaveTypeApiController;
use Modules\Hrm\Http\Controllers\Api\HomeApiController;


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

Route::middleware('jwt.api.auth')->get('/hrm', function (Request $request) {
    return $request->user();
});

Route::prefix('Hrm')->group(function () {
    Route::middleware(['jwt.api.auth'])->group(function () {

        Route::post('home',[HomeApiController::class,'index']);

        Route::post('holidays-list',[HolidaylistApiController::class,'index']);

        Route::post('attendence-history',[AttendanceApiController::class,'attendenceHistory']);
        Route::post('clock-in-out',[AttendanceApiController::class,'clockInOut']);
        Route::post('clock-out',[AttendanceApiController::class,'clockOut']);

        Route::post('get-leaves',[LeaveApiController::class,'index']);
        Route::post('leave-request',[LeaveApiController::class,'store']);

        Route::post('get-leaves-types',[LeaveTypeApiController::class,'index']);

    });
});

