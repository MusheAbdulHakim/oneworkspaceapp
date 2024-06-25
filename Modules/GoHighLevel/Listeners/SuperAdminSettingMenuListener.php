<?php

namespace Modules\GoHighLevel\Listeners;
use App\Events\SuperAdminSettingMenuEvent;

class SuperAdminSettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(SuperAdminSettingMenuEvent $event): void
    {
        $module = 'GoHighLevel';
        $menu = $event->menu;
        $menu->add([
            'title' => 'New GoHighLevel',
            'name' => 'gohighlevel',
            'order' => 100,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'home',
            'navigation' => 'gohighlevel-sidenav',
            'module' => $module,
            // 'permission' => 'manage-dashboard'
        ]);
    }
}
