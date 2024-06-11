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
use Modules\SideMenuBuilder\Http\Controllers\SideMenuBuilderController;

Route::group(['middleware' => 'PlanModuleCheck:SideMenuBuilder'], function () {

    Route::resource('sidemenubuilder', SideMenuBuilderController::class)->middleware(
        [
            'auth',
        ]
    );

    Route::get('iframe/{id}', [SideMenuBuilderController::class, 'iFrameData'])->name('sidemenubuilder.iframe')->middleware(
        [
            'auth',
        ]
    );
});
