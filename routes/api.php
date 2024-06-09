<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('{module}')->group(function () {

    Route::post('/login',[AuthApiController::class,'login']);
    Route::post('/logout',[AuthApiController::class,'logout'])->middleware('jwt.api.auth');
    Route::post('/refresh',[AuthApiController::class,'refresh'])->middleware('jwt.api.auth');
    Route::post('/edit-profile',[AuthApiController::class,'editProfile'])->middleware('jwt.api.auth');
    Route::post('/change-password',[AuthApiController::class,'changePassword'])->middleware('jwt.api.auth');
    Route::post('/delete-account',[AuthApiController::class,'deleteAccount'])->middleware('jwt.api.auth');
    Route::post('get-workspace-users',[AuthApiController::class,'getWorkspaceUsers'])->middleware('jwt.api.auth');

});




