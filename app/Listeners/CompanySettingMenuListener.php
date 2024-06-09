<?php

namespace App\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingMenuEvent $event): void
    {
        $module = 'Base';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Brand Settings'),
            'name' => 'brand-settings',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'site-settings',
            'module' => $module,
            'permission' => 'setting manage'
        ]);
        $menu->add([
            'title' => __('System Settings'),
            'name' => 'system-settings',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'system-settings',
            'module' => $module,
            'permission' => 'setting manage'
        ]);
        $menu->add([
            'title' => __('Company Settings'),
            'name' => 'company-settings',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'company-setting-sidenav',
            'module' => $module,
            'permission' => 'setting manage'
        ]);
        $menu->add([
            'title' => __('Currency Settings'),
            'name' => 'company-settings',
            'order' => 35,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'currency-setting-sidenav',
            'module' => $module,
            'permission' => 'setting manage'
        ]);
        $menu->add([
            'title' => __('Proposal Print Settings'),
            'name' => 'proposal-settings',
            'order' => 40,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'proposal-print-sidenav',
            'module' => $module,
            'permission' => 'proposal manage'
        ]);
        $menu->add([
            'title' => __('Invoice Print Settings'),
            'name' => 'invoice-settings',
            'order' => 60,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'invoice-print-sidenav',
            'module' => $module,
            'permission' => 'invoice manage'
        ]);

        $menu->add([
            'title' => __('Purchase Print Settings'),
            'name' => 'purchase-print-setting',
            'order' => 65,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'purchase-print-sidenav',
            'module' => $module,
            'permission' => 'purchase manage'
        ]);

        $menu->add([
            'title' => __('Email Settings'),
            'name' => 'email-settings',
            'order' => 500,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'email-sidenav',
            'module' => $module,
            'permission' => 'setting manage'
        ]);
        $menu->add([
            'title' => __('Email Notification Settings'),
            'name' => 'email-notification-settings',
            'order' => 510,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'email-notification-sidenav',
            'module' => $module,
            'permission' => 'setting manage'
        ]);
        $menu->add([
            'title' => __('Bank Transfer'),
            'name' => 'bank-transfer-settings',
            'order' => 1000,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'bank-transfer-sidenav',
            'module' => $module,
            'permission' => 'setting manage'
        ]);
    }
}
