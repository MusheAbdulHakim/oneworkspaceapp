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
use Modules\PabblyConnect\Http\Controllers\PabblyConnectController;

Route::prefix('pabblyconnect')->group(function() {
    Route::group(['middleware' => 'PlanModuleCheck:PabblyConnect'], function ()
    {
        Route::get('/', [PabblyConnectController::class, 'index'])->name('pabbly.connect.index');
        Route::get('/create', [PabblyConnectController::class, 'create'])->name('pabbly.connect.create');
        Route::post('/store', [PabblyConnectController::class, 'store'])->name('pabbly.connect.store');
        Route::get('/edit/{id}', [PabblyConnectController::class, 'edit'])->name('pabbly.connect.edit');
        Route::post('/update/{id}', [PabblyConnectController::class, 'update'])->name('pabbly.connect.update');
        Route::DELETE('/delete/{id}', [PabblyConnectController::class, 'destroy'])->name('pabbly.connect.delete');
    });
});
