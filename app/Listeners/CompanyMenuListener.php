<?php

namespace App\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Base';
        $menu = $event->menu;
        $menu->add([
            'category' => 'General',
            'title' => __('Dashboards'),
            'icon' => 'dashboard',
            'name' => 'dashboard',
            'parent' => null,
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'category' => 'General',
            'title' => __('My Calendar'),
            'icon' => '',
            'name' => 'mycalendar',
            'parent' => 'dashboard',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'mycalendar.index',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('Onboarding'),
            'icon' => 'shopping-cart',
            'name' => 'onboarding',
            'parent' => null,
            'order' => 2,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'embeds.onboarding',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('DBoS Strategy'),
            'icon' => 'shopping-cart',
            'name' => 'strategy',
            'parent' => null,
            'order' => 3,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'embeds.strategy',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('Marketing Hub'),
            'icon' => 'map',
            'name' => 'marketing',
            'parent' => null,
            'order' => 4,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'embeds.marketing-hub',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('My Websites'),
            'icon' => '',
            'name' => 'my-websites',
            'parent' => 'ghl',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'embeds.marketing',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'category' => 'General',
            'title' => __('ERP & Operations'),
            'icon' => 'stack-2',
            'name' => 'operations',
            'parent' => null,
            'order' => 5,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('Proposal'),
            'icon' => 'replace',
            'name' => 'proposal',
            'parent' => 'operations',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => ['Account','Taskly'],
            'route' => 'proposal.index',
            'module' => $module,
            'permission' => 'proposal manage'
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('Invoice'),
            'icon' => 'file-invoice',
            'name' => 'invoice',
            'parent' => 'operations',
            'order' => 2,
            'ignore_if' => [],
            'depend_on' => ['Account','Taskly'],
            'route' => 'invoice.index',
            'module' => $module,
            'permission' => 'invoice manage'
        ]);

        $menu->add([
            'category' => 'General',
            'title' => __('Procurement'),
            'icon' => 'shopping-cart',
            'name' => 'purchases',
            'parent' => 'operations',
            'order' => 3,
            'ignore_if' => [],
            'depend_on' => ['Account','Taskly'],
            'route' => '',
            'module' => $module,
            'permission' => 'purchase manage'
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('Purchase'),
            'icon' => '',
            'name' => 'purchase',
            'parent' => 'purchases',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => ['Account','Taskly'],
            'route' => 'purchases.index',
            'module' => $module,
            'permission' => 'purchase manage'
        ]);

        $menu->add([
            'category' => 'General',
            'title' => __('Warehouse'),
            'icon' => '',
            'name' => 'warehouse',
            'parent' => 'purchases',
            'order' => 2,
            'ignore_if' => [],
            'depend_on' => ['Account','Taskly'],
            'route' => 'warehouses.index',
            'module' => $module,
            'permission' => 'warehouse manage'
        ]);

        $menu->add([
            'category' => 'General',
            'title' => __('Transfer'),
            'icon' => '',
            'name' => 'transfer',
            'parent' => 'purchases',
            'order' => 3,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'warehouses-transfer.index',
            'module' => $module,
            'permission' => 'warehouse manage'
        ]);

        $menu->add([
            'category' => 'General',
            'title' => __('Report'),
            'icon' => '',
            'name' => 'reports',
            'parent' => 'purchases',
            'order' => 4,
            'ignore_if' => [],
            'depend_on' => ['Account','Taskly'],
            'route' => '',
            'module' => $module,
            'permission' => 'report purchase'
        ]);

        $menu->add([
            'category' => 'General',
            'title' => __('Purchase Daily/Monthly Report'),
            'icon' => '',
            'name' => 'purchase-monthly',
            'parent' => 'reports',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'reports.daily.purchase',
            'module' => $module,
            'permission' => 'report purchase'
        ]);

        $menu->add([
            'category' => 'General',
            'title' => __('Warehouse Report'),
            'icon' => '',
            'name' => 'warehouse-report',
            'parent' => 'reports',
            'order' => 2,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'reports.warehouse',
            'module' => $module,
            'permission' => 'report warehouse'
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('Customer Success'),
            'icon' => 'stack-2',
            'name' => 'customer-success',
            'parent' => null,
            'order' => 6,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'embeds.customer-success',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('Process Automation'),
            'icon' => 'replace',
            'name' => 'process-automation',
            'parent' => null,
            'order' => 7,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'embeds.process-automation',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('App Integrations'),
            'icon' => 'pin',
            'name' => 'app-integrations',
            'parent' => null,
            'order' => 8,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'embeds.app-integrations',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('Marketplaces'),
            'icon' => 'cloud',
            'name' => 'marketplace',
            'parent' => null,
            'order' => 9,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'embeds.marketplace',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('Community'),
            'icon' => 'users',
            'name' => 'community',
            'parent' => null,
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'embeds.community',
            'module' => $module,
            'permission' => ''
        ]);

        $menu->add([
            'category' => 'General',
            'title' => __('Helpdesk'),
            'icon' => 'headphones',
            'name' => 'helpdesk',
            'parent' => null,
            'order' => 13,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('HelpDesk'),
            'icon' => 'headphones',
            'name' => 'main-helpdesk',
            'parent' => 'helpdesk',
            'order' => 13,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'helpdesk.index',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('Knowledgebase'),
            'icon' => 'book',
            'name' => 'knowledgebase',
            'parent' => 'helpdesk',
            'order' => 11,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'embeds.knowledgebase',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('Messenger'),
            'icon' => 'brand-hipchat',
            'name' => 'messenger',
            'parent' => 'helpdesk',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'chatify',
            'module' => $module,
            'permission' => 'user chat manage'
        ]);
        $menu->add([
            'category' => 'Settings',
            'title' => __('Settings'),
            'icon' => 'settings',
            'name' => 'settings',
            'parent' => null,
            'order' => 14,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'setting manage'
        ]);

        $menu->add([
            'category' => 'Settings',
            'title' => __('System Settings'),
            'icon' => '',
            'name' => 'system-settings',
            'parent' => 'settings',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'settings.index',
            'module' => $module,
            'permission' => 'setting manage'
        ]);
        $menu->add([
            'category' => 'Settings',
            'title' => __('Setup Subscription Plan'),
            'icon' => '',
            'name' => 'setup-subscription-plan',
            'parent' => 'settings',
            'order' => 2,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'plans.index',
            'module' => $module,
            'permission' => 'plan manage'
        ]);
        $menu->add([
            'category' => 'Settings',
            'title' => __('Referral Program'),
            'icon' => '',
            'name' => 'referral-program',
            'parent' => 'settings',
            'order' => 3,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'referral-program.company',
            'module' => $module,
            'permission' => 'referral program manage'
        ]);
        $menu->add([
            'category' => 'Settings',
            'title' => __('Order'),
            'icon' => '',
            'name' => 'order',
            'parent' => 'settings',
            'order' => 4,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'plan.order.index',
            'module' => $module,
            'permission' => 'plan orders'
        ]);

        $menu->add([
            'category' => 'Settings',
            'title' => __('User Management'),
            'icon' => 'users',
            'name' => 'user-management',
            'parent' => 'settings',
            'order' => 5,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'user manage'
        ]);
        $menu->add([
            'category' => 'Settings',
            'title' => __('User'),
            'icon' => '',
            'name' => 'user',
            'parent' => 'user-management',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'users.index',
            'module' => $module,
            'permission' => 'user manage'
        ]);
        $menu->add([
            'category' => 'Settings',
            'title' => __('Role'),
            'icon' => '',
            'name' => 'role',
            'parent' => 'user-management',
            'order' => 2,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'roles.index',
            'module' => $module,
            'permission' => 'roles manage'
        ]);
    }
}
