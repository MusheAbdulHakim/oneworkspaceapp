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
            'category' => 'Settings',
            'title' => __('Side Menu Builder'),
            'icon' => 'circle-plus',
            'name' => 'sidemenubuilder',
            'parent' => null,
            'order' => 1300,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'sidemenubuilder.index',
            'module' => $module,
            'permission' => 'sidemenubuilder manage'
        ]);
    }
}
