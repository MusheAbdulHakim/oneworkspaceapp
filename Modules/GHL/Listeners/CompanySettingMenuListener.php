<?php

namespace Modules\GHL\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingMenuEvent $event): void
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
