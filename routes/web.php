<?php


use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BanktransferController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Company\SettingsController as CompanySettingsController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomDomainRequestController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\HelpdeskConversionController;
use App\Http\Controllers\HelpdeskTicketController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseDebitNoteController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SuperAdmin\SettingsController as SuperAdminSettingsController;
use App\Http\Controllers\WarehouseTransferController;
use App\Http\Controllers\WorkSpaceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReferralProgramController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Auth::routes();
require __DIR__.'/auth.php';

// custom domain code
Route::middleware('domain-check')->group(function () {
    Route::get('/register/{lang?}', [RegisteredUserController::class, 'create'])->name('register');
    Route::get('/login/{lang?}', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::get('/forgot-password/{lang?}', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::get('/verify-email/{lang?}', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');

    // module page before login
    Route::get('add-on', [HomeController::class, 'Software'])->name('apps.software');
    Route::get('add-on/details/{slug}', [HomeController::class, 'SoftwareDetails'])->name('software.details');
    Route::get('pricing', [HomeController::class, 'Pricing'])->name('apps.pricing');
    Route::get('pages', [HomeController::class, 'CustomPage'])->name('custompage');
    Route::get('/', [HomeController::class, 'index'])->name('start');
});
Route::middleware(['auth','verified'])->group(function () {

    //Role & Permission
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    //dashbord
    if(module_is_active('GoogleAuthentication'))
    {
        Route::get('/dashboard', [HomeController::class,'Dashboard'])->name('dashboard')->middleware(
            [
                '2fa',
            ]
        );
        Route::get('/home', [HomeController::class,'Dashboard'])->name('home')->middleware(
            [
                '2fa',
            ]
        );
    }
    else
    {
        Route::get('/dashboard', [HomeController::class,'Dashboard'])->name('dashboard');
        Route::get('/home', [HomeController::class,'Dashboard'])->name('home');
    }

     // settings
    Route::resource('settings', SettingsController::class);
    Route::post('settings-save', [CompanySettingsController::class, 'store'])->name('settings.save');
    Route::post('company/settings-save', [CompanySettingsController::class, 'store'])->name('company.settings.save');
    Route::post('super-admin/settings-save', [SuperAdminSettingsController::class, 'store'])->name('super.admin.settings.save');
    Route::post('super-admin/system-settings-save', [SuperAdminSettingsController::class, 'SystemStore'])->name('super.admin.system.setting.store');
    Route::post('company/system-settings-save', [CompanySettingsController::class, 'SystemStore'])->name('company.system.setting.store');
    Route::post('company-setting-save', [CompanySettingsController::class, 'companySettingStore'])->name('company.setting.save');
    Route::post('comapny-currency-settings', [CompanySettingsController::class, 'saveCompanyCurrencySettings'])->name('company.setting.currency.settings');
    Route::post('company/update-note-value', [SuperAdminSettingsController::class,'updateNoteValue'])->name('company.update.note.value');

    Route::post('email-settings-save', [SettingsController::class, 'mailStore'])->name('email.setting.store');
    Route::post('test-mail', [SettingsController::class, 'testMail'])->name('test.mail');
    Route::post('test-mail-send', [SettingsController::class, 'sendTestMail'])->name('test.mail.send');
    Route::post('email/getfields',[SettingsController::class,'getfields'])->name('get.emailfields');
    Route::post('email-notification-settings-save', [SettingsController::class, 'mailNotificationStore'])->name('email.notification.setting.store');

    Route::post('cookie-settings-save', [SuperAdminSettingsController::class, 'CookieSetting'])->name('cookie.setting.store');
    Route::post('pusher-setting', [SuperAdminSettingsController::class, 'savePusherSettings'])->name('pusher.setting');
    Route::post('seo/setting/save', [SuperAdminSettingsController::class, 'seoSetting'])->name('seo.setting.save');
    Route::post('storage-settings-save', [SuperAdminSettingsController::class, 'storageStore'])->name('storage.setting.store');
    Route::post('ai/key/setting/save', [SuperAdminSettingsController::class, 'aiKeySettingSave'])->name('ai.key.setting.save');
    Route::post('currency-settings', [SuperAdminSettingsController::class, 'saveCurrencySettings'])->name('super.admin.currency.settings');
    Route::post('/update-note-value', [SuperAdminSettingsController::class,'updateNoteValue'])->name('admin.update.note.value');

    Route::get('/setting/section/{module}/{methord?}', [SettingsController::class,'getSettingSection'])->name('setting.section.get');

    // bank-transfer
    Route::resource('bank-transfer-request', BanktransferController::class);
    Route::post('bank-transfer-setting', [BanktransferController::class, 'setting'])->name('bank.transfer.setting');
    Route::post('/bank/transfer/pay', [BanktransferController::class, 'planPayWithBank'])->name('plan.pay.with.bank');


    Route::get('invoice-bank-request/{id}', [BanktransferController::class, 'invoiceBankRequestEdit'])->name('invoice.bank.request.edit');
    Route::post('bank-transfer-request-edit/{id}', [BanktransferController::class, 'invoiceBankRequestupdate'])->name('invoice.bank.request.update');

     // domain Request Module
     Route::resource('custom_domain_request', CustomDomainRequestController::class);
     Route::get('custom_domain_request/{id}/{response}', [CustomDomainRequestController::class, 'acceptRequest'])->name('custom_domain_request.request');

    //users
    Route::resource('users', UserController::class);
    Route::get('users/list/view', [UserController::class, 'List'])->name('users.list.view');
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::post('edit-profile', [UserController::class, 'editprofile'])->name('edit.profile');
    Route::post('change-password', [UserController::class, 'updatePassword'])->name('update.password');
    Route::any('user-reset-password/{id}', [UserController::class, 'UserPassword'])->name('users.reset');
    Route::get('user-login/{id}', [UserController::class, 'LoginManage'])->name('users.login');
    Route::post('user-reset-password/{id}', [UserController::class, 'UserPasswordReset'])->name('user.password.update');
    Route::get('users/{id}/login-with-company', [UserController::class, 'LoginWithCompany'])->name('login.with.company');
    Route::get('company-info/{id}', [UserController::class, 'CompnayInfo'])->name('company.info');
    Route::post('user-unable', [UserController::class, 'UserUnable'])->name('user.unable');

    //User Log
    Route::get('users/logs/history', [UserController::class, 'UserLogHistory'])->name('users.userlog.history');
    Route::get('users/logs/{id}', [UserController::class, 'UserLogView'])->name('users.userlog.view');
    Route::delete('users/logs/destroy/{id}', [UserController::class, 'UserLogDestroy'])->name('users.userlog.destroy');

    // users import
    Route::get('users/import/export', [UserController::class, 'fileImportExport'])->name('users.file.import');
    Route::get('users/import/modal', [UserController::class, 'fileImportModal'])->name('users.import.modal');
    Route::post('users/import', [UserController::class, 'fileImport'])->name('users.import');
    Route::post('users/data/import/', [UserController::class, 'UserImportdata'])->name('users.import.data');


    // impersonating
    Route::get('login-with-company/exit', [UserController::class, 'ExitCompany'])->name('exit.company');

    // Language
    Route::get('/lang/change/{lang}', [LanguageController::class, 'changeLang'])->name('lang.change');
    Route::get('langmanage/{lang?}/{module?}', [LanguageController::class, 'index'])->name('lang.index');
    Route::get('create-language', [LanguageController::class, 'create'])->name('create.language');
    Route::post('langs/{lang?}/{module?}', [LanguageController::class, 'storeData'])->name('lang.store.data');
    Route::post('disable-language',[LanguageController::class,'disableLang'])->name('disablelanguage');
    Route::any('store-language', [LanguageController::class, 'store'])->name('store.language');
    Route::delete('/lang/{id}', [LanguageController::class, 'destroy'])->name('lang.destroy');
    // End Language

    // Workspace
    Route::resource('workspace', WorkSpaceController::class);
    Route::get('workspace/change/{id}', [WorkSpaceController::class, 'change'])->name('workspace.change');
    Route::post('workspace/check', [WorkSpaceController::class, 'workspaceCheck'])->name('workspace.check');

    // End Workspace

    // Plans
    Route::resource('plans', PlanController::class);

    Route::get('plan/list', [PlanController::class, 'PlanList'])->name('plan.list');
    Route::post('plan/store', [PlanController::class, 'PlanStore'])->name('plan.store');
    Route::get('plan/active', [PlanController::class, 'ActivePlans'])->name('active.plans');
    Route::get('upgrade-plan/{id}',[PlanController::class,'upgradePlan'])->name('upgrade.plan');
    Route::get('plan/buy/{plan_id}/{user_id}',[PlanController::class,'planDetail'])->name('plan.details');
    Route::get('modules/buy/{user_id}',[PlanController::class,'moduleBuy'])->name('module.buy');
    Route::post('direct-assign-plan-to-user/{plan_id}/{user_id}',[PlanController::class,'directAssignPlanToUser'])->name('assign.plan.user');
    Route::any('plan/package-data', [PlanController::class, 'PackageData'])->name('package.data');
    Route::get('plan/plan-buy/{id}', [PlanController::class, 'PlanBuy'])->name('plan.buy');
    Route::get('plan/plan-trial/{id}', [PlanController::class, 'PlanTrial'])->name('plan.trial');
    Route::get('plan/order', [PlanController::class, 'orders'])->name('plan.order.index');
    Route::get('add-one/detail/{id}', [PlanController::class, 'AddOneDetail'])->name('add-one.detail');
    Route::post('add-one/detail/save/{id}', [PlanController::class, 'AddOneDetailSave'])->name('add-one.detail.save');
    Route::post('update-plan-status', [PlanController::class, 'updateStatus'])->name('update.plan.status');
    Route::get('plan/refund/{id}/{user_id}', [PlanController::class, 'refund'])->name('order.refund');

    Route::post('company/settings-save', [CompanySettingsController::class, 'store'])->name('company.settings.save');
    Route::post('super-admin/settings-save', [SuperAdminSettingsController::class, 'store'])->name('super.admin.settings.save');
    Route::post('storage-settings-save', [SuperAdminSettingsController::class, 'storageStore'])->name('storage.setting.store');

    // Coupon
    Route::resource('coupons', CouponController::class);
    Route::get('/apply-coupon', [CouponController::class, 'applyCoupon'])->name('apply.coupon');
    // end Coupon

    // Module Install
    Route::get('modules/list', [ModuleController::class, 'index'])->name('module.index');
    Route::get('modules/add', [ModuleController::class, 'add'])->name('module.add');
    Route::post('install-modules', [ModuleController::class, 'install'])->name('module.install');
    Route::post('remove-modules/{module}', [ModuleController::class, 'remove'])->name('module.remove');
    Route::post('modules-enable', [ModuleController::class, 'enable'])->name('module.enable');
    Route::get('cancel/add-on/{name}', [ModuleController::class, 'CancelAddOn'])->name('cancel.add.on');
    // End Module Install

    // Email Templates
    Route::resource('email-templates', EmailTemplateController::class);
    Route::get('email_template_lang/{id}/{lang?}', [EmailTemplateController::class, 'show'])->name('manage.email.language');
    Route::put('email_template_store/{pid}', [EmailTemplateController::class, 'storeEmailLang'])->name('store.email.language');
    Route::put('email_template_status/{id}', [EmailTemplateController::class, 'updateStatus'])->name('status.email.language');
    Route::resource('email_template', EmailTemplateController::class);
    // End Email Templates

    // helpdesk
    Route::resource('helpdesk', HelpdeskTicketController::class);
    Route::resource('helpdeskticket-category', HelpdeskTicketCategoryController::class);
    Route::get('helpdesk-tickets/search/{status?}', [HelpdeskTicketController::class, 'index'])->name('helpdesk-tickets.search');
    Route::post('helpdesk-ticket/getUser', [HelpdeskTicketController::class, 'getUser'])->name('helpdesk-tickets.getuser');
    Route::post('helpdesk-ticket/{id}/conversion', [HelpdeskConversionController::class, 'store'])->name('helpdesk-ticket.conversion.store');
    Route::post('helpdesk-ticket/{id}/note', [HelpdeskTicketController::class, 'storeNote'])->name('helpdesk-ticket.note.store');
    Route::delete('helpdesk-ticket-attachment/{tid}/destroy/{id}', [HelpdeskTicketController::class, 'attachmentDestroy'])->name('helpdesk-ticket.attachment.destroy');
    // End helpdesk


    Route::group(['middleware' => 'PlanModuleCheck:Account-Taskly'], function ()
    {
        // invoice
        Route::post('invoice/customer', [InvoiceController::class, 'customer'])->name('invoice.customer');
        Route::post('invoice-attechment/{id}', [InvoiceController::class, 'invoiceAttechment'])->name('invoice.file.upload');
        Route::delete('invoice-attechment/destroy/{id}', [InvoiceController::class, 'invoiceAttechmentDestroy'])->name('invoice.attachment.destroy');
        Route::post('invoice/product', [InvoiceController::class, 'product'])->name('invoice.product');
        Route::get('invoice/{id}/duplicate', [InvoiceController::class, 'duplicate'])->name('invoice.duplicate');
        Route::get('invoice/{id}/recurring', [InvoiceController::class, 'recurring'])->name('invoice.recurring');
        Route::get('invoice/items', [InvoiceController::class, 'items'])->name('invoice.items');
        Route::post('invoice/product/destroy', [InvoiceController::class, 'productDestroy'])->name('invoice.product.destroy');
        Route::get('invoice/grid/view', [InvoiceController::class, 'Grid'])->name('invoice.grid.view');
        Route::resource('invoice', InvoiceController::class);
        Route::get('invoice/create/{cid}', [InvoiceController::class, 'create'])->name('invoice.create');
        Route::get('/invoice/pay/{invoice}', [InvoiceController::class, 'payinvoice'])->name('pay.invoice');
        Route::get('invoice/{id}/sent', [InvoiceController::class, 'sent'])->name('invoice.sent');
        Route::get('invoice/{id}/resent', [InvoiceController::class, 'resent'])->name('invoice.resent');
        Route::get('invoice/{id}/payment/reminder', [InvoiceController::class, 'paymentReminder'])->name('invoice.payment.reminder');
        Route::get('invoice/pdf/{id}', [InvoiceController::class, 'invoice'])->name('invoice.pdf');
        Route::get('invoice/{id}/payment', [InvoiceController::class, 'payment'])->name('invoice.payment');
        Route::post('invoice/{id}/payment', [InvoiceController::class, 'createPayment'])->name('invoice.payment');
        Route::post('invoice/{id}/payment/{pid}/', [InvoiceController::class, 'paymentDestroy'])->name('invoice.payment.destroy');
        Route::get('invoice/{id}/send', [InvoiceController::class, 'customerInvoiceSend'])->name('invoice.send');
        Route::post('invoice/{id}/send/mail', [InvoiceController::class,'customerInvoiceSendMail'])->name('invoice.send.mail');
        Route::post('invoice/section/type', [InvoiceController::class, 'InvoiceSectionGet'])->name('invoice.section.type');
        Route::get('delivery-form/pdf/{id}', [InvoiceController::class, 'pdf'])->name('delivery-form.pdf');

        Route::post('/get-invoice-customers', [InvoiceController::class,'getInvoiceCustomers'])->name('invoice.customers');

        Route::post('invoice-item-detail', [InvoiceController::class, 'getInvoicItemeDetail'])->name('newspaper.invoice.item.details');

        Route::post('invoice/course', [InvoiceController::class, 'course'])->name('invoice.course');
        Route::get('invoice/status/view', [InvoiceController::class, 'InvocieStatus'])->name('invoice.status.view');

        // Proposal
        Route::post('proposal-attechment/{id}', [ProposalController::class, 'proposalAttechment'])->name('proposal.file.upload');
        Route::delete('proposal-attechment/destroy/{id}', [ProposalController::class, 'proposalAttechmentDestroy'])->name('proposal.attachment.destroy');
        Route::post('proposal/customer', [ProposalController::class, 'customer'])->name('proposal.customer');
        Route::post('proposal/product', [ProposalController::class, 'product'])->name('proposal.product');
        Route::get('proposal/{id}/convert', [ProposalController::class, 'convert'])->name('proposal.convert');
        Route::get('proposal/{id}/duplicate', [ProposalController::class, 'duplicate'])->name('proposal.duplicate');
        Route::get('proposal/items', [ProposalController::class, 'items'])->name('proposal.items');
        Route::post('proposal/product/destroy', [ProposalController::class, 'productDestroy'])->name('proposal.product.destroy');
        Route::resource('proposal', ProposalController::class);
        Route::get('proposal/grid/view', [ProposalController::class, 'Grid'])->name('proposal.grid.view');
        Route::get('proposal/create/{cid}', [ProposalController::class, 'create'])->name('proposal.create');
        Route::get('proposal/{id}/status/change', [ProposalController::class, 'statusChange'])->name('proposal.status.change');
        Route::get('proposal/{id}/resent', [ProposalController::class, 'resent'])->name('proposal.resent');
        Route::post('proposal/section/type', [ProposalController::class, 'ProposalSectionGet'])->name('proposal.section.type');
        Route::get('proposal/{id}/sent', [ProposalController::class, 'sent'])->name('proposal.sent');
        Route::get('proposal/stats/view', [ProposalController::class, 'ProposalQuickStats'])->name('proposal.stats.view');

        // purchase
        Route::resource('purchases', PurchaseController::class);
        Route::get('purchases-grid', [PurchaseController::class, 'grid'])->name('purchases.grid');
        Route::post('purchases/items', [PurchaseController::class, 'items'])->name('purchases.items');
        Route::get('purchases/{id}/payment', [PurchaseController::class, 'payment'])->name('purchases.payment');
        Route::post('purchases/{id}/payment', [PurchaseController::class, 'createPayment'])->name('purchases.payment');
        Route::post('purchases/{id}/payment/{pid}/destroy', [PurchaseController::class, 'paymentDestroy'])->name('purchases.payment.destroy');

        Route::post('purchases/product/destroy', [PurchaseController::class, 'productDestroy'])->name('purchases.product.destroy');
        Route::post('purchases/vender', [PurchaseController::class, 'vender'])->name('purchases.vender');
        Route::post('purchases/product', [PurchaseController::class, 'product'])->name('purchases.product');
        Route::get('purchases/create/{cid}', [PurchaseController::class, 'create'])->name('purchases.create');
        Route::get('purchases/{id}/sent', [PurchaseController::class, 'sent'])->name('purchases.sent');
        Route::get('purchases/{id}/resent', [PurchaseController::class, 'resent'])->name('purchases.resent');


        Route::get('purchases/{id}/debit-note', [PurchaseDebitNoteController::class, 'create'])->name('purchases.debit.note')->middleware(
            [
                'auth',
            ]
        );
        Route::post('purchases/{id}/debit-note', [PurchaseDebitNoteController::class, 'store'])->name('purchases.debit.note')->middleware(
            [
                'auth',
            ]
        );
        Route::get('purchases/{id}/debit-note/edit/{cn_id}', [PurchaseDebitNoteController::class, 'edit'])->name('purchases.edit.debit.note')->middleware(
            [
                'auth',
            ]
        );
        Route::post('purchases/{id}/debit-note/edit/{cn_id}', [PurchaseDebitNoteController::class, 'update'])->name('purchases.edit.debit.note')->middleware(
            [
                'auth',
            ]
        );
        Route::delete('purchases/{id}/debit-note/delete/{cn_id}', [PurchaseDebitNoteController::class, 'destroy'])->name('purchases.delete.debit.note')->middleware(
            [
                'auth',
            ]
        );

        Route::post('purchase/{id}/file',[PurchaseController::class, 'fileUpload'])->name('purchases.files.upload')->middleware(['auth']);
        Route::delete("purchase/{id}/destroy", [PurchaseController::class, 'fileUploadDestroy'])->name("purchases.attachment.destroy")->middleware(['auth']);
        //warehouse

          Route::resource('warehouses', WarehouseController::class)->middleware(['auth',]);

          //warehouse import
          Route::get('warehouses/import/export', [WarehouseController::class, 'fileImportExport'])->name('warehouses.file.import')->middleware(['auth']);
          Route::post('warehouses/import', [WarehouseController::class, 'fileImport'])->name('warehouses.import')->middleware(['auth']);
          Route::get('warehouses/import/modal', [WarehouseController::class, 'fileImportModal'])->name('warehouses.import.modal')->middleware(['auth']);
          Route::post('warehouses/data/import/', [WarehouseController::class, 'warehouseImportdata'])->name('warehouses.import.data')->middleware(['auth']);

          Route::get('productservice/{id}/detail', [WarehouseController::class, 'warehouseDetail'])->name('productservices.detail');

         //warehouse-transfer
         Route::resource('warehouses-transfer', WarehouseTransferController::class)->middleware(['auth']);
         Route::post('warehouses-transfer/getproduct', [WarehouseTransferController::class, 'getproduct'])->name('warehouses-transfer.getproduct')->middleware(['auth']);
         Route::post('warehouses-transfer/getquantity', [WarehouseTransferController::class, 'getquantity'])->name('warehouses-transfer.getquantity')->middleware(['auth']);

        //Reports
        Route::get('reports-warehouses', [ReportController::class, 'warehouseReport'])->name('reports.warehouse')->middleware(['auth']);
        Route::get('reports-daily-purchases', [ReportController::class, 'purchaseDailyReport'])->name('reports.daily.purchase')->middleware(['auth']);
        Route::get('reports-monthly-purchases', [ReportController::class, 'purchaseMonthlyReport'])->name('reports.monthly.purchase')->middleware(['auth']);

    });
    // invoices template setting save
    Route::post('/invoices/template/setting', [InvoiceController::class, 'saveTemplateSettings'])->name('invoice.template.setting');
    Route::get('/invoices/preview/{template}/{color}', [InvoiceController::class, 'previewInvoice'])->name('invoice.preview');

    // proposal template setting save
    Route::get('/proposal/preview/{template}/{color}', [ProposalController::class, 'previewInvoice'])->name('proposal.preview');
    Route::post('/proposal/template/setting', [ProposalController::class, 'saveTemplateSettings'])->name('proposal.template.setting');

    // purchase template setting save
    Route::get('purchases/preview/{template}/{color}', [PurchaseController::class, 'previewPurchase'])->name('purchases.preview');
    Route::post('/purchase/template/setting', [PurchaseController::class, 'savePurchaseTemplateSettings'])->name('purchases.template.setting');


    //notification
    Route::resource('notification-template', NotificationController::class);
    Route::get('notification-template/{id}/{lang?}', [NotificationController::class, 'show'])->name('manage.notification.language');
    Route::post('notification-template/{pid}', [NotificationController::class, 'storeNotificationLang'])->name('store.notification.language');

    // Referral Program
    Route::resource('referral-program',ReferralProgramController::class);
    Route::get('referral-program-company', [ReferralProgramController::class, 'companyIndex'])->name('referral-program.company');
    Route::get('request-amount-sent/{id}', [ReferralProgramController::class, 'requestedAmountSent'])->name('request.amount.sent');
    Route::post('request-amount-store/{id}', [ReferralProgramController::class, 'requestedAmountStore'])->name('request.amount.store');
    Route::get('request-amount-cancel/{id}', [ReferralProgramController::class, 'requestCancel'])->name('request.amount.cancel');
    Route::get('request-amount/{id}/{status}', [ReferralProgramController::class, 'requestedAmount'])->name('amount.request');
});
Route::get('module/reset', [ModuleController::class, 'ModuleReset'])->name('module.reset');
Route::post('guest/module/selection', [ModuleController::class, 'GuestModuleSelection'])->name('guest.module.selection');

// cookie
Route::get('cookie/consent', [SuperAdminSettingsController::class, 'CookieConsent'])->name('cookie.consent');

// cache
Route::get('/config-cache', function()
{
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    return redirect()->back()->with('success', 'Cache Clear Successfully');

})->name('config.cache');

//helpdesk
Route::post('helpdesk-ticket/{id}', [HelpdeskTicketController::class, 'reply'])->name('helpdesk-ticket.reply');
Route::get('helpdesk-ticket-show/{id}', [HelpdeskTicketController::class, 'show'])->name('helpdesk.view');

// invoice
Route::get('/invoice/pay/{invoice}', [InvoiceController::class, 'payinvoice'])->name('pay.invoice');
Route::get('invoice/pdf/{id}', [InvoiceController::class, 'invoice'])->name('invoice.pdf');
Route::post('/bank/transfer/invoice', [BanktransferController::class, 'invoicePayWithBank'])->name('invoice.pay.with.bank');

// proposal
Route::get('/proposal/pay/{proposal}', [ProposalController::class, 'payproposal'])->name('pay.proposalpay');
Route::get('proposal/pdf/{id}', [ProposalController::class, 'proposal'])->name('proposal.pdf');


// purchase
Route::get('/vendor/purchases/{id}', [PurchaseController::class, 'purchaseLink'])->name('purchases.link.copy');
Route::get('/vend0r/bill/{id}/', [PurchaseController::class, 'invoiceLink'])->name('bill.link.copy')->middleware(['auth']);
Route::get('purchases/pdf/{id}', [PurchaseController::class, 'purchase'])->name('purchases.pdf');

