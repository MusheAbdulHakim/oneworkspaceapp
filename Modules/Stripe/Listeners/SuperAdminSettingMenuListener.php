<?php

namespace Modules\Stripe\Listeners;
use App\Events\SuperAdminSettingMenuEvent;

class SuperAdminSettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(SuperAdminSettingMenuEvent $event): void
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
