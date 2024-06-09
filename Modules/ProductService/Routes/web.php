<?php
use Illuminate\Support\Facades\Route;
use Modules\ProductService\Http\Controllers\CategoryController;
use Modules\ProductService\Http\Controllers\ProductsLogTimeController;
use Modules\ProductService\Http\Controllers\ProductServiceController;
use Modules\ProductService\Http\Controllers\ProductStockController;
use Modules\ProductService\Http\Controllers\TaxController;
use Modules\ProductService\Http\Controllers\UnitController;

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

Route::middleware(['PlanModuleCheck:ProductService','auth'])->group(function ()
{
    Route::resource('product-service', ProductServiceController::class);
    Route::resource('units', UnitController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('tax', TaxController::class);
    Route::resource('product-service', ProductServiceController::class);
    Route::resource('productslogtime', ProductsLogTimeController::class);

    Route::get('product-service-grid', [ProductServiceController::class, 'grid'])->name('product-service.grid');

    // Product Stock
    Route::resource('productstock', ProductStockController::class);

    //Product & Service import
    Route::get('product-service/import/export', [ProductServiceController::class, 'fileImportExport'])->name('product-service.file.import');
    Route::post('product-service/import', [ProductServiceController::class, 'fileImport'])->name('product-service.import');
    Route::get('product-service/import/modal', [ProductServiceController::class, 'fileImportModal'])->name('product-service.import.modal');
    Route::post('product-service/data/import/', [ProductServiceController::class, 'productserviceImportdata'])->name('product-service.import.data');
    Route::post('get-taxes', [ProductServiceController::class, 'getTaxes'])->name('get.taxes');
    Route::any('product-service/get-item', [ProductServiceController::class, 'GetItem'])->name('get.item');


    Route::post('category/getaccount', [CategoryController::class, 'getAccount'])->name('category.getaccount');

    Route::post('product-service/section/type', [ProductServiceController::class, 'ProductSectionGet'])->name('product.section.type');

});
