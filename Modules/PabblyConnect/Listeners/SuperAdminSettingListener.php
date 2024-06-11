<?php

namespace Modules\PabblyConnect\Listeners;
use App\Events\SuperAdminSettingEvent;

class SuperAdminSettingListener
{
    /**
     * Handle the event.
     */
    public function handle(SuperAdminSettingEvent $event): void
    {
        $module = 'PabblyConnect';
        $methodName = 'index';
        $controllerClass = "Modules\\PabblyConnect\\Http\\Controllers\\SuperAdmin\\SettingsController";
        if (class_exists($controllerClass)) {
            $controller = \App::make($controllerClass);
            if (method_exists($controller, $methodName)) {
                $html = $event->html;
                $settings = $html->getSettings();
                $output =  $controller->{$methodName}($settings);
                $html->add([
                    'html' => $output->toHtml(),
                    'order' => 750,
                    'module' => $module,
                    'permission' => 'pabbly manage'
                ]);
            }
        }
    }
}