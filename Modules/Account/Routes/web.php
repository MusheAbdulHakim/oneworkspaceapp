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
use Modules\Account\Http\Controllers\AccountController;
use Modules\Account\Http\Controllers\BankAccountController;
use Modules\Account\Http\Controllers\BillController;
use Modules\Account\Http\Controllers\ChartOfAccountController;
use Modules\Account\Http\Controllers\CreditNoteController;
use Modules\Account\Http\Controllers\CustomerController;
use Modules\Account\Http\Controllers\CustomerCreditNotesController;
use Modules\Account\Http\Controllers\CustomerDebitNotesController;
use Modules\Account\Http\Controllers\DebitNoteController;
use Modules\Account\Http\Controllers\PaymentController;
use Modules\Account\Http\Controllers\ReportController;
use Modules\Account\Http\Controllers\RevenueController;
use Modules\Account\Http\Controllers\TransactionController;
use Modules\Account\Http\Controllers\TransferController;
use Modules\Account\Http\Controllers\VenderController;

Route::group(['middleware' => 'PlanModuleCheck:Account'], function () {
    Route::middleware(['auth', 'verified'])->group(function () {

        Route::prefix('account')->group(function () {
            Route::get('/', 'AccountController@index');
        });
        // dashboard
        Route::get('dashboard/account', [AccountController::class, 'index'])->name('dashboard.account');

        // Bank account
        Route::resource('bank-account', BankAccountController::class);

        //chart-of-account
        Route::resource('chart-of-account', ChartOfAccountController::class);
        Route::post('chart-of-account/subtype', [ChartOfAccountController::class, 'getSubType'])->name('charofAccount.subType');

        // Transfer
        Route::resource('bank-transfer', TransferController::class);

        // customer
        Route::resource('customer', CustomerController::class);
        Route::get('customer-grid', [CustomerController::class, 'grid'])->name('customer.grid');
        Route::any('customer/{id}/statement', [CustomerController::class, 'statement'])->name('customer.statement');

        // Customer import
        Route::get('customer/import/export', [CustomerController::class, 'fileImportExport'])->name('customer.file.import');
        Route::post('customer/import', [CustomerController::class, 'fileImport'])->name('customer.import');
        Route::get('customer/import/modal', [CustomerController::class, 'fileImportModal'])->name('customer.import.modal');
        Route::post('customer/data/import/', [CustomerController::class, 'customerImportdata'])->name('customer.import.data');

        // Vendor
        Route::resource('vendors', VenderController::class);
        Route::get('vendors-grid', [VenderController::class, 'grid'])->name('vendors.grid');
        Route::any('vendors/{id}/statement', [VenderController::class, 'statement'])->name('vendor.statement');

        // Vendor import
        Route::get('vendor/import/export', [VenderController::class, 'fileImportExport'])->name('vendor.file.import');
        Route::post('vendor/import', [VenderController::class, 'fileImport'])->name('vendor.import');
        Route::get('vendor/import/modal', [VenderController::class, 'fileImportModal'])->name('vendor.import.modal');
        Route::post('vendor/data/import/', [VenderController::class, 'vendorImportdata'])->name('vendor.import.data');

        // credit note
        Route::get('invoice/{id}/credit-note', [CreditNoteController::class, 'create'])->name('invoice.credit.note');
        Route::post('invoice/{id}/credit-note', [CreditNoteController::class, 'store'])->name('invoice.credit.note');
        Route::get('invoice/{id}/credit-note/edit/{cn_id}', [CreditNoteController::class, 'edit'])->name('invoice.edit.credit.note');
        Route::post('invoice/{id}/credit-note/edit/{cn_id}', [CreditNoteController::class, 'update'])->name('invoice.edit.credit.note');
        Route::delete('invoice/{id}/credit-note/delete/{cn_id}', [CreditNoteController::class, 'destroy'])->name('invoice.delete.credit.note');

        // revenue
        Route::resource('revenue', RevenueController::class);

        //customer credit note

        Route::get('customer-credits-note', [CustomerCreditNotesController::class, 'index'])->name('custom-credit.note');
        Route::get('customer-credits', [CustomerCreditNotesController::class, 'customCreate'])->name('create.custom.credit.note');
        Route::post('custom-credit-store', [CustomerCreditNotesController::class, 'customStore'])->name('custom-credits.store');
        Route::get('credit-note/invoice', [CustomerCreditNotesController::class, 'getinvoice'])->name('invoice.get');
        Route::get('invoice/{id}/custom-credit/edit/{cn_id}', [CustomerCreditNotesController::class, 'edit'])->name('invoice.edit.custom-credit');
        Route::post('invoice/{id}/custom-credit-note/edit/{cn_id}', [CustomerCreditNotesController::class, 'update'])->name('invoice.custom-note.edit');
        Route::delete('invoice/{id}/custom-credit/delete/{cn_id}', [CustomerCreditNotesController::class, 'destroy'])->name('invoice.custom-note.delete');


        //customer debit note

        Route::get('debit-note', [CustomerDebitNotesController::class, 'index'])->name('debit.note');
        Route::get('custom-debit-note', [CustomerDebitNotesController::class, 'customCreate'])->name('bill.custom.debit.note');
        Route::post('custom-debit-note', [CustomerDebitNotesController::class, 'customStore'])->name('custom-debits.note');
        Route::get('debit-note/bill', [CustomerDebitNotesController::class, 'getbill'])->name('bill.get');
        Route::get('bill/{id}/custom-debit-note/edit/{cn_id}', [CustomerDebitNotesController::class, 'edit'])->name('bill.debit-custom.edit');
        Route::post('bill/debit-note/edit/{id}/{cn_id}', [CustomerDebitNotesController::class, 'update'])->name('bill.custom.edit');
        Route::delete('bill/custom-debit-note/delete/{id}/{cn_id}', [CustomerDebitNotesController::class, 'destroy'])->name('bill.delete.custom-debit');

        // bill payment
        Route::resource('payment', PaymentController::class);
        Route::post('bill-attechment/{id}', [BillController::class, 'billAttechment'])->name('bill.file.upload');
        Route::delete('bill-attechment/destroy/{id}', [BillController::class, 'billAttechmentDestroy'])->name('bill.attachment.destroy');
        Route::post('bill/vendors', [BillController::class, 'vendor'])->name('bill.vendor');
        Route::post('bill/product', [BillController::class, 'product'])->name('bill.product');
        Route::get('bill/items', [BillController::class, 'items'])->name('bill.items');
        Route::resource('bill', BillController::class);
        Route::get('bill-grid', [BillController::class, 'grid'])->name('bill.grid');
        Route::get('bill/create/{cid}', [BillController::class, 'create'])->name('bill.create');
        Route::post('bill/product/destroy', [BillController::class, 'productDestroy'])->name('bill.product.destroy');
        Route::get('bill/{id}/duplicate', [BillController::class, 'duplicate'])->name('bill.duplicate');
        Route::get('bill/{id}/sent', [BillController::class, 'sent'])->name('bill.sent');
        Route::get('bill/{id}/payment', [BillController::class, 'payment'])->name('bill.payment');
        Route::post('bill/{id}/payment', [BillController::class, 'createPayment'])->name('bill.payment');
        Route::post('bill/{id}/payment/{pid}/destroy', [BillController::class, 'paymentDestroy'])->name('bill.payment.destroy');
        Route::get('bill/{id}/resent', [BillController::class, 'resent'])->name('bill.resent');
        Route::post('bill/section/type', [BillController::class, 'BillSectionGet'])->name('bill.section.type');
        Route::get('bill/{id}/debit-note', [DebitNoteController::class, 'create'])->name('bill.debit.note');
        Route::post('bill/{id}/debit-note', [DebitNoteController::class, 'store'])->name('bill.debit.note');
        Route::get('bill/{id}/debit-note/edit/{cn_id}', [DebitNoteController::class, 'edit'])->name('bill.edit.debit.note');
        Route::post('bill/{id}/debit-note/edit/{cn_id}', [DebitNoteController::class, 'update'])->name('bill.edit.debit.note');
        Route::delete('bill/{id}/debit-note/delete/{cn_id}', [DebitNoteController::class, 'destroy'])->name('bill.delete.debit.note');

        // settig in account
        Route::post('/accounts-setting/store', [AccountController::class, 'setting'])->name('accounts.setting.save');


        // bill template settig in account
        Route::get('/bill/preview/{template}/{color}', [BillController::class, 'previewBill'])->name('bill.preview');
        Route::post('/account/setting/store', [BillController::class, 'saveBillTemplateSettings'])->name('bill.template.setting');

        //bank-account setting
        Route::post('disable-account', [BankAccountController::class, 'disableAccount'])->name('bankaccount.setting.store');

        //request destroy
        Route::delete('bankaccount-request/{id}/destroy', [BankAccountController::class, 'BankAccountRequestdestroy'])->name('invoice.bankaccount.request.destroy');


        // Account Report
        Route::get('report/transaction', [TransactionController::class, 'index'])->name('transaction.index');
        Route::get('report/account-statement-report', [ReportController::class, 'accountStatement'])->name('report.account.statement');
        Route::get('report/income-summary', [ReportController::class, 'incomeSummary'])->name('report.income.summary');
        Route::get('report/expense-summary', [ReportController::class, 'expenseSummary'])->name('report.expense.summary');
        Route::get('report/income-vs-expense-summary', [ReportController::class, 'incomeVsExpenseSummary'])->name('report.income.vs.expense.summary');
        Route::get('report/tax-summary', [ReportController::class, 'taxSummary'])->name('report.tax.summary');
        Route::get('report/profit-loss-summary', [ReportController::class, 'profitLossSummary'])->name('report.profit.loss.summary');
        Route::get('report/invoice-summary', [ReportController::class, 'invoiceSummary'])->name('report.invoice.summary');
        Route::get('report/bill-summary', [ReportController::class, 'billSummary'])->name('report.bill.summary');
        Route::get('report/product-stock-report', [ReportController::class, 'productStock'])->name('report.product.stock.report');
    });
});
// without login route.
Route::get('/bill/pay/{bill}', [BillController::class, 'paybill'])->name('pay.billpay');
Route::get('bill/pdf/{id}', [BillController::class, 'bill'])->name('bill.pdf');
Route::get('bill/{id}/send', [BillController::class, 'venderBillSend'])->name('vendor.bill.send');
Route::post('bill/{id}/send/mail', [BillController::class, 'venderBillSendMail'])->name('vendor.bill.send.mail');
Route::post('bank-account/details', [BankAccountController::class, 'bankAccount'])->name('bankaccount.details');
Route::post('/invoice-pay-with-bankaccount', [BankAccountController::class, 'invoicePayWithBankAccount'])->name('invoice.pay.with.bankaccount');

Route::get('invoice-bankaccount-request/{id}', [BankAccountController::class, 'invoiceBankAccountRequestEdit'])->name('invoice.bankaccount.request.edit');
Route::post('bankaccount-request-edit/{id}', [BankAccountController::class, 'invoiceBankAccountRequestupdate'])->name('invoice.bankaccount.request.update');
