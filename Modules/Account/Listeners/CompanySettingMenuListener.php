<?php

namespace Modules\Account\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingMenuEvent $event): void
    {
        $module = 'Account';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Account Settings'),
            'name' => 'account-setting',
            'order' => 100,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'account-sidenav',
            'module' => $module,
            'permission' => 'account manage'
        ]);
        $menu->add([
            'title' => __('Bill Print Settings'),
            'name' => 'bill-print-sidenav',
            'order' => 110,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'bill-print-sidenav',
            'module' => $module,
            'permission' => 'account manage'
        ]);
        $menu->add([
            'title' => __('Bank Accounts'),
            'name' => 'bank-accounts-settings',
            'order' => 1005,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'bank-accounts-sidenav',
            'module' => $module,
            'method' => 'bankAccount',
            'permission' => 'account manage'
        ]);
    }
}
