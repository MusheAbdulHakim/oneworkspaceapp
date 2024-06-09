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
use Modules\Pos\Http\Controllers\PosController;
use Modules\Pos\Http\Controllers\ReportController;


Route::group(['middleware' => 'PlanModuleCheck:Pos'], function ()
{
    // Route::middleware(['auth','verified'])->group(function () {  // if any issue so remove commant and also remove ->middleware(['auth']) from all route

        // Route::prefix('pos')->group(function() {
        //     Route::get('/', 'PosController@index');
        // });

        Route::get('dashboard/pos',[PosController::class, 'dashboard'])->name('pos.dashboard')->middleware(['auth']);


        Route::post('pos/setting/store', [PosController::class, 'setting'])->name('pos.setting.store')->middleware(['auth']);


        Route::resource('pos', PosController::class)->middleware(['auth',]);

        Route::get('pos-grid', [PosController::class, 'grid'])->name('pos.grid');
        Route::get('report/pos', [PosController::class, 'report'])->name('pos.report')->middleware(['auth']);
        Route::get('search-products', [PosController::class, 'searchProducts'])->name('search.products')->middleware(['auth']);
        Route::get('name-search-products', [PosController::class, 'searchProductsByName'])->name('name.search.products')->middleware(['auth']);
        Route::post('warehouse-empty-cart', [PosController::class, 'warehouseemptyCart'])->name('warehouse-empty-cart')->middleware(['auth']);
        Route::get('product-categories', [PosController::class, 'getProductCategories'])->name('product.categories')->middleware(['auth']);
        Route::post('empty-cart', [PosController::class, 'emptyCart'])->middleware(['auth']);
        Route::get('add-to-cart/{id}/{session}/{war_id}', [PosController::class, 'addToCart'])->middleware(['auth']);
        Route::delete('remove-from-cart', [PosController::class, 'removeFromCart'])->middleware(['auth']);
        Route::patch('update-cart', [PosController::class, 'updateCart'])->middleware(['auth']);

        Route::get('pos/data/store', [PosController::class, 'store'])->name('pos.data.store')->middleware(['auth',]);

        // thermal print

        Route::get('printview/pos', [PosController::class, 'printView'])->name('pos.printview')->middleware(['auth',]);

        Route::post('/cartdiscount', [PosController::class, 'cartdiscount'])->name('cartdiscount')->middleware(['auth']);

        Route::get('pos/pdf/{id}', [PosController::class, 'pos'])->name('pos.pdf')->middleware(['auth']);
        Route::post('/pos/template/setting', [PosController::class, 'savePosTemplateSettings'])->name('pos.template.setting');
        Route::get('pos/preview/{template}/{color}', [PosController::class, 'previewPos'])->name('pos.preview')->middleware(['auth']);


        //Reports
        Route::get('reports-warehouse', [ReportController::class, 'warehouseReport'])->name('report.warehouse')->middleware(['auth']);
        Route::get('reports-daily-purchase', [ReportController::class, 'purchaseDailyReport'])->name('report.daily.purchase')->middleware(['auth']);
        Route::get('reports-monthly-purchase', [ReportController::class, 'purchaseMonthlyReport'])->name('report.monthly.purchase')->middleware(['auth']);
        Route::get('reports-daily-pos', [ReportController::class, 'posDailyReport'])->name('report.daily.pos')->middleware(['auth']);
        Route::get('reports-monthly-pos', [ReportController::class, 'posMonthlyReport'])->name('report.monthly.pos')->middleware(['auth']);
        Route::get('reports-pos-vs-purchase', [ReportController::class, 'posVsPurchaseReport'])->name('report.pos.vs.purchase')->middleware(['auth']);

	//pos barcode
        Route::get('barcode/pos', [PosController::class, 'barcode'])->name('pos.barcode')->middleware(['auth']);
        Route::get('setting/pos', [PosController::class, 'barcodeSetting'])->name('pos.setting')->middleware(['auth']);
        Route::post('barcode/settings', [PosController::class, 'BarcodesettingStore'])->name('barcode.setting');
        Route::get('print/pos', [PosController::class, 'printBarcode'])->name('pos.print')->middleware(['auth']);
        Route::post('pos/getproduct', [PosController::class, 'getproduct'])->name('pos.getproduct')->middleware(['auth']);
        Route::any('pos-receipt', [PosController::class, 'receipt'])->name('pos.receipt')->middleware(['auth']);
    // });
});


