<?php

namespace Modules\Paypal\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingMenuEvent $event): void
    {
        $module = 'Paypal';
        $menu = $event->menu;
        $menu->add([
            'title' => 'Paypal',
            'name' => 'paypal',
            'order' => 1020,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'paypal-sidenav',
            'module' => $module,
            'permission' => 'paypal manage'
        ]);
    }
}
