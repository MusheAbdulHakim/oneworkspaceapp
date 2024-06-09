<?php

namespace Modules\Stripe\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingMenuEvent $event): void
    {
        $module = 'Stripe';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Stripe'),
            'name' => 'stripe',
            'order' => 1010,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'stripe-sidenav',
            'module' => $module,
            'permission' => 'stripe manage'
        ]);
    }
}
