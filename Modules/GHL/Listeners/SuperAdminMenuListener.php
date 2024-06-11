<?php

namespace Modules\GHL\Listeners;
use App\Events\SuperAdminMenuEvent;

class SuperAdminMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(SuperAdminMenuEvent $event): void
    {
        $module = 'GHL';
        $menu = $event->menu;
        $menu->add([
            'title' => 'GoHighLevel',
            'icon' => 'location',
            'name' => 'ghl',
            'parent' => null,
            'order' => 2,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => null,
            'module' => $module,
            // 'permission' => 'ghl manage'
        ]);
        $menu->add([
            'title' => __('Dashboard'),
            'icon' => '',
            'name' => 'ghl-dashboard',
            'parent' => 'ghl',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'ghl.dashboard',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
            'title' => __('Contacts'),
            'icon' => '',
            'name' => 'ghl-contacts',
            'parent' => 'ghl',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'ghl.contacts',
            'module' => $module,
            'permission' => ''
        ]);
        $menu->add([
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
    }
}
