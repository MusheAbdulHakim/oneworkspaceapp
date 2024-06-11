<?php

namespace Modules\PabblyConnect\Listeners;
use App\Events\SuperAdminMenuEvent;

class SuperAdminMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(SuperAdminMenuEvent $event): void
    {
        $module = 'PabblyConnect';
        $menu = $event->menu;
        $menu->add([
            'title' => 'PabblyConnect',
            'icon' => 'home',
            'name' => 'pabblyconnect',
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