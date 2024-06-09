<?php

namespace Modules\Hrm\Listeners;

use App\Events\CompanySettingMenuEvent;

class CompanySettingMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingMenuEvent $event): void
    {
        $module = 'Hrm';
        $menu = $event->menu;
        $menu->add([
            'title' => __('Hrm Settings'),
            'name' => 'hrm-setting',
            'order' => 130,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'hrm-sidenav',
            'module' => $module,
            'permission' => 'hrm manage'
        ]);
        $menu->add([
            'title' => __('Joining Letter Settings'),
            'name' => 'joining-letter-settings',
            'order' => 150,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'joining-letter-settings',
            'module' => $module,
            'permission' => 'hrm manage'
        ]);
        $menu->add([
            'title' => __('Certificate of Experience Settings'),
            'name' => 'experience-certificate-settings',
            'order' => 160,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'experience-certificate-settings',
            'module' => $module,
            'permission' => 'hrm manage'
        ]);
        $menu->add([
            'title' => __('No Objection Certificate Settings'),
            'name' => 'noc-settings',
            'order' => 170,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'navigation' => 'noc-settings',
            'module' => $module,
            'permission' => 'hrm manage'
        ]);
    }
}
