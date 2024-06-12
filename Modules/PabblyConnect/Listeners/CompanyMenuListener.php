<?php

namespace Modules\PabblyConnect\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'PabblyConnect';
        $menu = $event->menu;
        $menu->add([
            'category' => 'Erp-operation',
            'title' => 'PabblyConnect',
            'icon' => 'home',
            'name' => 'pabblyconnect',
            'parent' => 'erp-operations',
            'order' => 2,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'home',
            'module' => $module,
            'permission' => 'manage-dashboard'
        ]);
    }
}
