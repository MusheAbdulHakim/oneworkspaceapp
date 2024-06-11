<?php

namespace Modules\PabblyConnect\Listeners;

use App\Events\SuperAdminSettingMenuEvent;

class SuperAdminSettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(SuperAdminSettingMenuEvent $event): void
    {
        $module = 'PabblyConnect';
        $menu = $event->menu;
        $menu->add([
            'title' => 'Pabbly Connect',
            'name' => 'pabblyconnect',
            'order' => 750,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'pabbly-sidenav',
            'module' => $module,
            'permission' => 'pabbly manage'
        ]);
    }
}
