<?php

namespace Modules\GHL\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'GHL';
        $menu = $event->menu;
        $menu->add([
            'category' => 'General',
            'title' => __('Marketing'),
            'icon' => 'location',
            'name' => 'ghl',
            'parent' => 'operations',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            // 'permission' => 'hrm manage'
        ]);
        $menu->add([
            'category' => 'General',
            'title' => __('Marketing Dashboard'),
            'icon' => '',
            'name' => 'ghl-dashboard',
            'parent' => 'dashboard',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'ghl.dashboard',
            'module' => $module,
            // 'permission' => ''
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
            'route' => 'ghl.contacts',
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
            'route' => 'ghl.calendars',
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
            'route' => 'ghl.calendars.events',
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
            'route' => 'ghl.funnels',
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
            'route' => 'ghl.invoices',
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
            'route' => 'ghl.campaigns',
            'module' => $module,
            'permission' => ''
        ]);

    }
}
