<?php

namespace Modules\GHL\Listeners;
use App\Events\SuperAdminSettingMenuEvent;

class SuperAdminSettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(SuperAdminSettingMenuEvent $event): void
    {
        $module = 'GHL';
        $menu = $event->menu;
        $menu->add([
            'title' => 'GoHighLevel',
            'name' => 'ghl',
            'order' => 100,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'ghl-sidenav',
            'module' => $module,
            // 'permission' => 'ghl manage'
        ]);
    }
}
