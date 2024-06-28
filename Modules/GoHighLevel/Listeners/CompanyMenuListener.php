<?php

namespace Modules\GoHighLevel\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'GoHighLevel';
        $menu = $event->menu;
        $menu->add([
            'category' => 'General',
            'title' => __('Dashboard'),
            'icon' => '',
            'name' => 'ghl-dashboard',
            'parent' => 'ghl',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'gohighlevel.dashboard',
            'module' => $module,
            // 'permission' => ''
        ]);
        $menu->add([
            'title' => 'GoHighLevel',
            'icon' => 'home',
            'name' => 'ghl',
            'parent' => null,
            'order' => 2,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'home',
            'module' => $module,
            'permission' => 'manage-dashboard'
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('GoHighLevel'),
            'icon' => 'location',
            'name' => 'ghl',
            'parent' => 'marketing',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            // 'permission' => 'hrm manage'
        ]);

        $menu->add([
            'category' => 'General',
            'title' => __('Contacts'),
            'icon' => '',
            'name' => 'ghl-contacts',
            'parent' => 'ghl',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'gohighlevel.contacts',
            'module' => $module,
            // 'permission' => ''
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('Calendars'),
            'icon' => '',
            'name' => 'ghl-calendars',
            'parent' => 'ghl',
            'order' => 2,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'gohighlevel.calendars',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('Calendars Events'),
            'icon' => '',
            'name' => 'ghl-calendars-events',
            'parent' => 'ghl',
            'order' => 2,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'gohighlevel.calendars.events',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('Funnels'),
            'icon' => '',
            'name' => 'ghl-funnels',
            'parent' => 'ghl',
            'order' => 2,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'gohighlevel.funnels',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('Invoices'),
            'icon' => '',
            'name' => 'ghl-invoices',
            'parent' => 'ghl',
            'order' => 3,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'gohighlevel.invoices',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('Campaigns'),
            'icon' => '',
            'name' => 'ghl-campaigns',
            'parent' => 'ghl',
            'order' => 3,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'gohighlevel.campaigns',
            'module' => $module,
            'permission' => ''
        ]);
    }
}
