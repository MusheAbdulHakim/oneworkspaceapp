<?php

namespace Modules\Account\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Account';
        $menu = $event->menu;
        $menu->add([
            'category' => 'General',
            'title' => __('Accounting Dashboard'),
            'icon' => '',
            'name' => 'account-dashboard',
            'parent' => 'dashboard',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'dashboard.account',
            'module' => $module,
            'permission' => 'account dashboard manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Accounting'),
            'icon' => 'layout-grid-add',
            'name' => 'accounting',
            'parent' => null,
            'order' => 400,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'account manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Customer'),
            'icon' => '',
            'name' => 'customer',
            'parent' => 'accounting',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'customer.index',
            'module' => $module,
            'permission' => 'customer manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Vendor'),
            'icon' => '',
            'name' => 'vendor',
            'parent' => 'accounting',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'vendors.index',
            'module' => $module,
            'permission' => 'vendor manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Banking'),
            'icon' => '',
            'name' => 'banking',
            'parent' => 'accounting',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'sidebar banking manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Account'),
            'icon' => '',
            'name' => 'bank-account',
            'parent' => 'banking',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'bank-account.index',
            'module' => $module,
            'permission' => 'bank account manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Chart Of Accounts'),
            'icon' => '',
            'name' => 'chart-of-account',
            'parent' => 'banking',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'chart-of-account.index',
            'module' => $module,
            'permission' => 'chartofaccount manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Transfer'),
            'icon' => '',
            'name' => 'bank-transfer',
            'parent' => 'banking',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'bank-transfer.index',
            'module' => $module,
            'permission' => 'bank transfer manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Income'),
            'icon' => '',
            'name' => 'income',
            'parent' => 'accounting',
            'order' => 25,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'sidebar income manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Revenue'),
            'icon' => '',
            'name' => 'revenue',
            'parent' => 'income',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'revenue.index',
            'module' => $module,
            'permission' => 'revenue manage'
        ]);


        $menu->add([
            'category' => 'Finance',
            'title' => __('Credit Notes'),
            'icon' => '',
            'name' => 'credit-notes',
            'parent' => 'income',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'custom-credit.note',
            'module' => $module,
            'permission' => 'creditnote manage'
        ]);


        $menu->add([
            'category' => 'Finance',
            'title' => __('Expense'),
            'icon' => '',
            'name' => 'expense',
            'parent' => 'accounting',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'sidebar expanse manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Bill'),
            'icon' => '',
            'name' => 'bill',
            'parent' => 'expense',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'bill.index',
            'module' => $module,
            'permission' => 'bill manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Payment'),
            'icon' => '',
            'name' => 'payment',
            'parent' => 'expense',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'payment.index',
            'module' => $module,
            'permission' => 'bill payment manage'
        ]);

        $menu->add([
            'category' => 'Finance',
            'title' => __('Debit Notes'),
            'icon' => '',
            'name' => 'debit-notes',
            'parent' => 'expense',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'debit.note',
            'module' => $module,
            'permission' => 'bill payment manage'
        ]);

        $menu->add([
            'category' => 'Finance',
            'title' => __('Report'),
            'icon' => '',
            'name' => 'account-report',
            'parent' => 'accounting',
            'order' => 50,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'report manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Transaction'),
            'icon' => '',
            'name' => 'transaction',
            'parent' => 'account-report',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'transaction.index',
            'module' => $module,
            'permission' => 'report transaction manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Account Statement'),
            'icon' => '',
            'name' => 'account-statement',
            'parent' => 'account-report',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'report.account.statement',
            'module' => $module,
            'permission' => 'report statement manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Income Summary'),
            'icon' => '',
            'name' => 'income-summary',
            'parent' => 'account-report',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'report.income.summary',
            'module' => $module,
            'permission' => 'report income manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Expense Summary'),
            'icon' => '',
            'name' => 'expense-summary',
            'parent' => 'account-report',
            'order' => 25,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'report.expense.summary',
            'module' => $module,
            'permission' => 'report expense manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Income Vs Expense'),
            'icon' => '',
            'name' => 'income&expense-summary',
            'parent' => 'account-report',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'report.income.vs.expense.summary',
            'module' => $module,
            'permission' => 'report income vs expense manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Tax Summary'),
            'icon' => '',
            'name' => 'tax-summary',
            'parent' => 'account-report',
            'order' => 35,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'report.tax.summary',
            'module' => $module,
            'permission' => 'report tax manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Profit & Loss'),
            'icon' => '',
            'name' => 'profit&loss-summary',
            'parent' => 'account-report',
            'order' => 40,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'report.profit.loss.summary',
            'module' => $module,
            'permission' => 'report loss & profit  manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Invoice Summary'),
            'icon' => '',
            'name' => 'invoice-summary',
            'parent' => 'account-report',
            'order' => 45,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'report.invoice.summary',
            'module' => $module,
            'permission' => 'report invoice manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Bill Summary'),
            'icon' => '',
            'name' => 'bill-summary',
            'parent' => 'account-report',
            'order' => 50,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'report.bill.summary',
            'module' => $module,
            'permission' => 'report bill manage'
        ]);
        $menu->add([
            'category' => 'Finance',
            'title' => __('Product Stock'),
            'icon' => '',
            'name' => 'product stock',
            'parent' => 'account-report',
            'order' => 55,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'report.product.stock.report',
            'module' => $module,
            'permission' => 'report stock manage'
        ]);
    }
}
