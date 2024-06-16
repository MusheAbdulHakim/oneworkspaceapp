<?php

namespace Modules\SideMenuBuilder\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'SideMenuBuilder';
        $menu = $event->menu;
        $menu->add([
            'category' => 'General',
            'title' => __('Custom Menus'),
            'icon' => 'circle-plus',
            'name' => 'sidemenubuilder',
            'parent' => 'operations',
            'order' => 1300,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'sidemenubuilder.index',
            'module' => $module,
            'permission' => 'sidemenubuilder manage'
        ]);
    }
}
