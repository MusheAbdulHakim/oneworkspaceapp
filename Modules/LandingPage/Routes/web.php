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
use Modules\LandingPage\Http\Controllers\LandingPageController;
use Modules\LandingPage\Http\Controllers\CustomPageController;
use Modules\LandingPage\Http\Controllers\HomeController;
use Modules\LandingPage\Http\Controllers\FeaturesController;
use Modules\LandingPage\Http\Controllers\ScreenshotsController;
use Modules\LandingPage\Http\Controllers\PricingPlanController;
use Modules\LandingPage\Http\Controllers\JoinUsController;
use Modules\LandingPage\Http\Controllers\FooterController;
use Modules\LandingPage\Http\Controllers\ReviewController;
use Modules\LandingPage\Http\Controllers\DedicatedSectionController;
use Modules\LandingPage\Http\Controllers\BuiltTechSectionController;
use Modules\LandingPage\Http\Controllers\PackageDetailsController;
use Modules\LandingPage\Http\Controllers\FaqController;
use Modules\LandingPage\Http\Controllers\MarketPlaceController;
use Modules\LandingPage\Http\Controllers\PixelController;



// Route::resource('landingpage', LandingPageController::class)->middleware(['auth']);

Route::resource('landingpagePixel', PixelController::class)->middleware(['auth']);

Route::get('landingpagePixel/delete/{key}', [PixelController::class, 'destroy'])
    ->name('pixel.destroy')
    ->middleware(['auth']);

Route::get('landingpage', [LandingPageController::class, 'index'])
    ->name('landingpage.index')
    ->middleware(['auth']);

Route::post('landingpage/google-fonts', [CustomPageController::class, 'googleFonts'])
    ->name('landingpage.google.fonts')
    ->middleware(['auth']);

Route::post('landingpage/store', [LandingPageController::class, 'store'])
    ->name('landingpage.store')
    ->middleware(['auth']);

Route::get('change-blocks', [LandingPageController::class, 'changeBlocks'])
    ->name('landing.change.blocks')
    ->middleware(['auth']);

Route::post('/change-blocks-store', [LandingPageController::class, 'changeBlocksStoreAjax'])->name('change.blocks.store.ajax');

Route::post('landingpage/cookie-settings-save', [LandingPageController::class, 'CookieSetting'])->name('landingpage.cookie.setting.store');
Route::post('landingpage/seo/setting/save', [LandingPageController::class, 'seoSetting'])->name('landingpage.seo.setting.save');
Route::post('landingpage/pwa/setting/save', [LandingPageController::class, 'pwaSetting'])->name('landingpage.pwa.setting.save');
Route::post('landingpage/custom-js-css/setting/save', [LandingPageController::class, 'customJsCssSetting'])->name('landingpage.custom-js-css.setting.save');

Route::get('landingpage/seo', [LandingPageController::class, 'seo'])
    ->name('landing.seo')
    ->middleware(['auth']);

Route::get('landingpage/cookie', [LandingPageController::class, 'cookie'])
    ->name('landing.cookie')
    ->middleware(['auth']);

Route::get('landingpage/PWA', [LandingPageController::class, 'pwa'])
    ->name('landing.pwa')
    ->middleware(['auth']);

Route::get('landingpage/QR-code', [LandingPageController::class, 'qrCode'])
    ->name('landing.qrCode')
    ->middleware(['auth']);

Route::any('landingpage/QR-code/store', [LandingPageController::class, 'qrCodeStore'])
    ->name('landingpage.qrcode_setting')
    ->middleware(['auth']);

Route::get('landingpage_qr//download/', [LandingPageController::class, 'downloadqr'])->middleware('auth')->name('download.qr');

    //
Route::any('change-blocks/store', [LandingPageController::class, 'changeBlocksStore'])
    ->name('landing.change.blocks.store')
    ->middleware(['auth']);

Route::any('image-view/{slug}/{section?}', [LandingPageController::class ,'getInfoImages'])
    ->name('info.image.view')
    ->middleware(['auth']);

Route::resource('custom_page', CustomPageController::class)->middleware(['auth']);

Route::get('landingpage/custom', [CustomPageController::class, 'custom'])
    ->name('landingpage.custom')
    ->middleware(['auth']);

Route::post('custom_store/', [CustomPageController::class,'customStore'])
    ->name('custom_store')
    ->middleware(['auth']);
Route::get('pages/{slug}', [CustomPageController::class, 'customPage'])
    ->name('custom.page');

Route::resource('homesection', HomeController::class)->middleware(['auth']);

Route::resource('features', FeaturesController::class)->middleware(['auth']);

Route::get('feature/create', [FeaturesController::class, 'feature_create'])
    ->name('feature_create')
    ->middleware(['auth']);
Route::post('feature/store', [FeaturesController::class, 'feature_store'])
    ->name('feature_store')
    ->middleware(['auth']);
Route::get('feature/edit/{key}', [FeaturesController::class, 'feature_edit'])
    ->name('feature_edit')
    ->middleware(['auth']);
Route::post('feature/update/{key}', [FeaturesController::class, 'feature_update'])
    ->name('feature_update')
    ->middleware(['auth']);
Route::get('feature/delete/{key}', [FeaturesController::class, 'feature_delete'])
    ->name('feature_delete')
    ->middleware(['auth']);

Route::post('feature_highlight_store', [FeaturesController::class, 'feature_highlight_store'])
    ->name('feature_highlight_store')
    ->middleware(['auth']);

Route::get('features/create', [FeaturesController::class, 'features_create'])
    ->name('features_create')
    ->middleware(['auth']);
Route::post('features/store', [FeaturesController::class, 'features_store'])
    ->name('features_store')
    ->middleware(['auth']);
Route::get('features/edit/{key}', [FeaturesController::class, 'features_edit'])
    ->name('features_edit')
    ->middleware(['auth']);
Route::post('features/update/{key}', [FeaturesController::class, 'features_update'])
    ->name('features_update')
    ->middleware(['auth']);
Route::get('features/delete/{key}', [FeaturesController::class, 'features_delete'])
    ->name('features_delete')
    ->middleware(['auth']);


Route::resource('screenshots', ScreenshotsController::class)->middleware(['auth']);

Route::get('screenshots/create', [ScreenshotsController::class, 'screenshots_create'])
    ->name('screenshots_create')
    ->middleware(['auth']);

Route::post('screenshots/store', [ScreenshotsController::class, 'screenshots_store'])
    ->name('screenshots_store')
    ->middleware(['auth']);

Route::get('screenshots/edit/{key}', [ScreenshotsController::class, 'screenshots_edit'])
    ->name('screenshots_edit')
    ->middleware(['auth']);

Route::post('screenshots/update/{key}', [ScreenshotsController::class, 'screenshots_update'])
    ->name('screenshots_update')
    ->middleware(['auth']);

Route::get('screenshots/delete/{key}', [ScreenshotsController::class, 'screenshots_delete'])
    ->name('screenshots_delete')
    ->middleware(['auth']);

Route::resource('pricing_plan', PricingPlanController::class)->middleware(['auth']);


Route::resource('join_us', JoinUsController::class);

Route::get('join_us/delete/{key}', [JoinUsController::class, 'destroy'])
    ->name('join_us_destroy')
    ->middleware(['auth']);

Route::post('join_us/store', [JoinUsController::class, 'joinUsUserStore'])
    ->name('join_us_store');

Route::resource('footer', FooterController::class);

Route::post('footer_store', [FooterController::class, 'store'])
    ->name('footer_store')
    ->middleware(['auth']);

Route::get('footer/create', [FooterController::class, 'footer_section_create'])
    ->name('footer_section_create')
    ->middleware(['auth']);

Route::post('footer/store', [FooterController::class, 'footer_section_store'])
    ->name('footer_section_store')
    ->middleware(['auth']);

Route::get('footer/edit/{key}', [FooterController::class, 'footer_section_edit'])
    ->name('footer_section_edit')
    ->middleware(['auth']);

Route::post('footer/update/{key}', [FooterController::class, 'footer_section_update'])
    ->name('footer_section_update')
    ->middleware(['auth']);

Route::get('footers/delete/{key}', [FooterController::class, 'footer_section_delete'])
    ->name('footer_section_delete')
    ->middleware(['auth']);

Route::resource('review', ReviewController::class);

Route::get('review/create', [ReviewController::class, 'review_create'])
    ->name('review_create')
    ->middleware(['auth']);

Route::post('review/store', [ReviewController::class, 'review_store'])
    ->name('review_store')
    ->middleware(['auth']);

Route::get('review/edit/{key}', [ReviewController::class, 'review_edit'])
    ->name('review_edit')
    ->middleware(['auth']);

Route::post('review/update/{key}', [ReviewController::class, 'review_update'])
    ->name('review_update')
    ->middleware(['auth']);

Route::get('review/delete/{key}', [ReviewController::class, 'review_delete'])
    ->name('review_delete')
    ->middleware(['auth']);

Route::resource('dedicated', DedicatedSectionController::class)->middleware(['auth']);

Route::post('dedicated/store', [DedicatedSectionController::class, 'dedicated_store'])
    ->name('dedicated_store')
    ->middleware(['auth']);

Route::get('dedicated/create', [DedicatedSectionController::class, 'dedicated_card_create'])
    ->name('dedicated_card_create')
    ->middleware(['auth']);

Route::post('dedicateds/store', [DedicatedSectionController::class, 'dedicated_card_store'])
    ->name('dedicated_card_store')
    ->middleware(['auth']);

Route::get('dedicated/edit/{key}', [DedicatedSectionController::class, 'dedicated_card_edit'])
    ->name('dedicated_card_edit')
    ->middleware(['auth']);

Route::post('dedicated/update/{key}', [DedicatedSectionController::class, 'dedicated_card_update'])
    ->name('dedicated_card_update')
    ->middleware(['auth']);

Route::get('dedicated/delete/{key}', [DedicatedSectionController::class, 'dedicated_card_delete'])
    ->name('dedicated_card_delete')
    ->middleware(['auth']);

Route::resource('buildtech', BuiltTechSectionController::class)->middleware(['auth']);

Route::post('buildtech/store', [BuiltTechSectionController::class, 'buildtech_store'])
    ->name('buildtech_store')
    ->middleware(['auth']);

Route::get('buildtech/create', [BuiltTechSectionController::class, 'buildtech_card_create'])
    ->name('buildtech_card_create')
    ->middleware(['auth']);

Route::post('buildtechs/store', [BuiltTechSectionController::class, 'buildtech_card_store'])
    ->name('buildtech_card_store')
    ->middleware(['auth']);

Route::get('buildtech/edit/{key}', [BuiltTechSectionController::class, 'buildtech_card_edit'])
    ->name('buildtech_card_edit')
    ->middleware(['auth']);

Route::post('buildtech/update/{key}', [BuiltTechSectionController::class, 'buildtech_card_update'])
    ->name('buildtech_card_update')
    ->middleware(['auth']);

Route::get('buildtech/delete/{key}', [BuiltTechSectionController::class, 'buildtech_card_delete'])
    ->name('buildtech_card_delete')
    ->middleware(['auth']);

Route::resource('packagedetails', PackageDetailsController::class)->middleware(['auth']);

Route::post('packagedetails/store', [PackageDetailsController::class, 'packagedetails_store'])
    ->name('packagedetails_store')
    ->middleware(['auth']);

Route::resource('faq', FaqController::class)->middleware(['auth']);
Route::get('faq/create/', 'FaqController@faq_create')->name('faq_create')->middleware(['auth']);
Route::post('faq/store/', 'FaqController@faq_store')->name('faq_store')->middleware(['auth']);
Route::get('faq/edit/{key}', 'FaqController@faq_edit')->name('faq_edit')->middleware(['auth']);
Route::post('faq/update/{key}', 'FaqController@faq_update')->name('faq_update')->middleware(['auth']);
Route::get('faq/delete/{key}', 'FaqController@faq_delete')->name('faq_delete')->middleware(['auth']);


// *******************// Marketplace Controller starts// ************************//


// Route::resource('marketplace', MarketPlaceController::class);

Route::any('marketplace/{slug?}', [MarketPlaceController::class, 'marketplaceindex'])
    ->name('marketplace.index')
    ->middleware(['auth']);
// *******************// Product Main Section Starts// ************************//
Route::any('marketplace/{slug}/product', [MarketPlaceController::class, 'productindex'])
    ->name('marketplace_product')
    ->middleware(['auth']);

Route::post('marketplace/{slug}/product/store', [MarketPlaceController::class, 'product_main_store'])
    ->name('product_main_store')
    ->middleware(['auth']);

 // *******************// Product Main Section Ends// ************************//



// *******************// Dedicated Section Starts// ************************//

Route::any('marketplace/{slug}/dedicated', [MarketPlaceController::class, 'dedicatedindex'])
    ->name('marketplace_dedicated')
    ->middleware(['auth']);

Route::post('marketplaces/{slug}/dedicated/store', [MarketPlaceController::class, 'dedicated_theme_header_store'])
    ->name('dedicated_theme_header_store')
    ->middleware(['auth']);

Route::get('marketplace/{slug}/dedicated/create', [MarketPlaceController::class, 'dedicated_theme_create'])
    ->name('dedicated_theme_section_create')
    ->middleware(['auth']);

Route::post('marketplace/{slug}/dedicated/store', [MarketPlaceController::class, 'dedicated_theme_store'])
    ->name('dedicated_theme_section_store')
    ->middleware(['auth']);

Route::get('marketplace/{slug}/dedicated/edit/{key}', [MarketPlaceController::class, 'dedicated_theme_edit'])
    ->name('dedicated_theme_section_edit')
    ->middleware(['auth']);

Route::post('marketplace/{slug}/dedicated/update/{key}', [MarketPlaceController::class, 'dedicated_theme_update'])
    ->name('dedicated_theme_section_update')
    ->middleware(['auth']);

Route::get('marketplace/{slug}/dedicated/delete/{key}', [MarketPlaceController::class, 'dedicated_theme_delete'])
    ->name('dedicated_theme_section_delete')
    ->middleware(['auth']);

// *******************// Dedicated Section ends// ************************//



// *******************// Whychoose Section Starts// ************************//

Route::any('marketplace/{slug}/whychoose', [MarketPlaceController::class, 'whychooseindex'])
    ->name('marketplace_whychoose')
    ->middleware(['auth']);

Route::post('marketplace/{slug}/whychoose/store', [MarketPlaceController::class, 'whychoose_store'])
    ->name('whychoose_store')
    ->middleware(['auth']);

Route::get('marketplace/{slug}/create', [MarketPlaceController::class, 'pricing_plan_create'])
    ->name('pricing_plan_create')
    ->middleware(['auth']);

Route::post('marketplace/{slug}/store', [MarketPlaceController::class, 'pricing_plan_store'])
    ->name('pricing_plan_store')
    ->middleware(['auth']);

Route::get('marketplace/{slug}/edit/{key}', [MarketPlaceController::class, 'pricing_plan_edit'])
    ->name('pricing_plan_edit')
    ->middleware(['auth']);

Route::post('marketplace/{slug}/update/{key}', [MarketPlaceController::class, 'pricing_plan_update'])
    ->name('pricing_plan_update')
    ->middleware(['auth']);

Route::get('marketplace/{slug}/delete/{key}', [MarketPlaceController::class, 'pricing_plan_delete'])
    ->name('pricing_plan_delete')
    ->middleware(['auth']);

// *******************// Whychoose Section Ends// ************************//



// *******************// Screenshot Section Starts// ************************//

Route::any('marketplace/{slug}/screenshot', [MarketPlaceController::class, 'screenshotindex'])
    ->name('marketplace_screenshot')
    ->middleware(['auth']);

Route::get('marketplace/{slug}/screenshot/create', [MarketPlaceController::class, 'screenshots_create'])
    ->name('marketplace_screenshots_create')
    ->middleware(['auth']);

Route::post('marketplace/{slug}/screenshot/store', [MarketPlaceController::class, 'screenshots_store'])
    ->name('marketplace_screenshots_store')
    ->middleware(['auth']);

Route::get('marketplace/{slug}/screenshot/edit/{key}', [MarketPlaceController::class, 'screenshots_edit'])
    ->name('marketplace_screenshots_edit')
    ->middleware(['auth']);

Route::post('marketplace/{slug}/screenshot/update/{key}', [MarketPlaceController::class, 'screenshots_update'])
    ->name('marketplace_screenshots_update')
    ->middleware(['auth']);

Route::get('marketplace/{slug}/screenshot/delete/{key}', [MarketPlaceController::class, 'screenshots_delete'])
    ->name('marketplace_screenshots_delete')
    ->middleware(['auth']);

// *******************// Screenshot Section Ends// ************************//



// *******************// Add-on Section Starts// ************************//

Route::any('marketplace/{slug}/addon', [MarketPlaceController::class, 'addonindex'])
    ->name('marketplace_addon')
    ->middleware(['auth']);

Route::post('marketplaces/{slug}/addon/store', [MarketPlaceController::class, 'addon_store'])
    ->name('addon_store')
    ->middleware(['auth']);

// *******************// Add-on Section Ends// ************************//



