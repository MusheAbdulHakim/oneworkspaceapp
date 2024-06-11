<?php

namespace Modules\PabblyConnect\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingMenuEvent $event): void
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
