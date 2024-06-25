<?php

namespace Modules\GoHighLevel\Listeners;
use App\Events\SuperAdminMenuEvent;

class SuperAdminMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(SuperAdminMenuEvent $event): void
    {
        $module = 'GoHighLevel';
        $menu = $event->menu;
        $menu->add([
            'title' => 'GoHighLevel',
            'icon' => 'home',
            'name' => 'gohighlevel',
            'parent' => null,
            'order' => 2,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'home',
            'module' => $module,
            'permission' => 'manage-dashboard'
        ]);
    }
}
