<?php

namespace Modules\PabblyConnect\Listeners;
use App\Events\CompanySettingEvent;

class CompanySettingListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingEvent $event): void
    {
        $module = 'PabblyConnect';
        $methodName = 'index';
        $controllerClass = "Modules\\PabblyConnect\\Http\\Controllers\\Company\\SettingsController";
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