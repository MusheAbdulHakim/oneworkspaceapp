<?php

namespace App\Listeners;

use App\Events\SuperAdminMenuEvent;

class SuperAdminMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(SuperAdminMenuEvent $event): void
    {
        $module = 'Base';
        $menu = $event->menu;
        $menu->add([
            'category' => 'General',
            'title' => __('Dashboard'),
            'icon' => 'home',
            'name' => 'dashboard',
            'parent' => null,
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'dashboard',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('Customers'),
            'icon' => 'users',
            'name' => 'customers',
            'parent' => null,
            'order' => 50,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'users.index',
            'module' => $module,
            'permission' => 'user manage'
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('Subscription'),
            'icon' => 'trophy',
            'name' => 'subscription',
            'parent' => null,
            'order' => 100,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('Subscription Setting'),
            'icon' => '',
            'name' => 'subscription-setting',
            'parent' => 'subscription',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'plan.list',
            'module' => $module,
            'permission' => 'plan manage'
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('Coupon'),
            'icon' => '',
            'name' => 'coupon',
            'parent' => 'subscription',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'coupons.index',
            'module' => $module,
            'permission' => 'coupon manage'
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('Order'),
            'icon' => '',
            'name' => 'order',
            'parent' => 'subscription',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'plan.order.index',
            'module' => $module,
            'permission' => 'plan orders'
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('Bank Transfer Request'),
            'icon' => '',
            'name' => 'bank-transfer',
            'parent' => 'subscription',
            'order' => 40,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'bank-transfer-request.index',
            'module' => $module,
            'permission' => 'plan orders'
        ]);

        $menu->add([
            'category' => 'General',
            'title' => __('Custom Domain Request'),
            'icon' => '',
            'name' => 'custom-domain',
            'parent' => 'subscription',
            'order' => 50,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'custom_domain_request.index',
            'module' => $module,
            'permission' => 'plan orders'
        ]);

        $menu->add([
            'category' => 'General',
            'title' => __('Referral Program'),
            'icon' => '',
            'name' => 'referral-program',
            'parent' => 'subscription',
            'order' => 50,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'referral-program.index',
            'module' => $module,
            'permission' => 'referral program manage'
        ]);

        $menu->add([
            'category' => 'Operations',
            'title' => __('Helpdesk'),
            'icon' => 'headphones',
            'name' => 'helpdesk',
            'parent' => null,
            'order' => 200,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'helpdesk manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Tickets'),
            'icon' => '',
            'name' => 'tickets',
            'parent' => 'helpdesk',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'helpdesk.index',
            'module' => $module,
            'permission' => 'helpdesk ticket manage'
        ]);

        $menu->add([
            'category' => 'Operations',
            'title' => __('System Setup'),
            'icon' => '',
            'name' => 'system-setup',
            'parent' => 'helpdesk',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'helpdeskticket-category.index',
            'module' => $module,
            'permission' => 'helpdeskticket setup manage'
        ]);

        $menu->add([
            'category' => 'Settings',
            'title' => __('Email Template'),
            'icon' => 'template',
            'name' => 'email-templates',
            'parent' => null,
            'order' => 150,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'email-templates.index',
            'module' => $module,
            'permission' => 'email template manage'
        ]);
        $menu->add([
            'category' => 'Settings',
            'title' => __('Notification Template'),
            'icon' => 'notification',
            'name' => 'system-setup',
            'parent' => null,
            'order' => 170,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'notification-template.index',
            'module' => $module,
            'permission' => 'notification template manage'
        ]);

        $menu->add([
            'category' => 'Settings',
            'title' => __('Settings'),
            'icon' => 'settings',
            'name' => 'settings',
            'parent' => null,
            'order' => 1000,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'settings.index',
            'module' => $module,
            'permission' => 'setting manage'
        ]);
        $company_settings = getCompanyAllSetting();
        if(!empty($company_settings['category_wise_sidemenu']) && $company_settings['category_wise_sidemenu'] == 'on'){
            $category_wise_add_ons = json_decode(file_get_contents("https://dash-demo.workdo.io/cronjob/dash-addon.json"),true);
            $categories  =  array_map(function($item) {
                return [
                    "name" => $item["name"],
                    "icon" => $item["icon"]
                ];
            }, $category_wise_add_ons);

            foreach ($categories as $key => $category)
            {
                $menu->add([
                    'category' => 'Addon Manager',
                    'title' =>  $category['name'],
                    'icon' =>  $category['icon'] ,
                    'name' => '',
                    'parent' => null,
                    'order' => 1100,
                    'ignore_if' => [],
                    'depend_on' => [],
                    'route' => 'module.index',
                    'module' => $module,
                    'permission' => ''
                ]);
            }
        }
        else{
            $menu->add([
                'category' => 'Addon Manager',
                'title' => __('Add-on Manager'),
                'icon' => 'layout-2',
                'name' => 'add-on-manager',
                'parent' => null,
                'order' => 1100,
                'ignore_if' => [],
                'depend_on' => [],
                'route' => 'module.index',
                'module' => $module,
                'permission' => 'module manage'
            ]);
        }

    }
}
