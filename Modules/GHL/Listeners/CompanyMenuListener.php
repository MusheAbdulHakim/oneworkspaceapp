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
            'category' => 'Erp-operation',
            'title' => __('GoHighLevel'),
            'icon' => 'location',
            'name' => 'gohigh',
            'parent' => 'erp-operations',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            // 'permission' => 'hrm manage'
        ]);
        $menu->add([
            'title' => __('Dashboard'),
            'icon' => '',
            'name' => 'ghl-dashboard',
            'parent' => 'gohigh',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'ghl.dashboard',
            'module' => $module,
            'permission' => ''
        ]);
    }
}
