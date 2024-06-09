<?php

namespace Modules\LandingPage\Listeners;
use App\Events\SuperAdminMenuEvent;

class SuperAdminMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(SuperAdminMenuEvent $event): void
    {
        $module = 'LandingPage';
        $menu = $event->menu;
        $menu->add([
            'category' => __('Operations'),
            'title' => __('CMS'),
            'icon' => 'package',
            'name' => 'landing-page',
            'parent' => null,
            'order' => 500,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => 'Base',
            'permission' => 'landingpage manage'
        ]);
        $menu->add([
            'category' => __('Operations'),
            'title' => __('Landing Page'),
            'icon' => 'settings',
            'name' => '',
            'parent' => 'landing-page',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'landingpage.index',
            'module' => 'Base',
            'permission' => 'landingpage manage'
        ]);
        $menu->add([
            'category' => __('Operations'),
            'title' => __('Marketplace'),
            'icon' => 'settings',
            'name' => '',
            'parent' => 'landing-page',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'marketplace.index',
            'module' => 'Base',
            'permission' => 'landingpage manage'
        ]);
        $menu->add([
            'category' => __('Operations'),
            'title' => __('Custom Pages'),
            'icon' => 'settings',
            'name' => '',
            'parent' => 'landing-page',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'custom_page.index',
            'module' => 'Base',
            'permission' => 'landingpage manage'
        ]);
    }
}