<?php

namespace Modules\Account\Listeners;
use App\Events\CompanySettingEvent;

class CompanySettingListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanySettingEvent $event): void
    {
        $module = 'Account';

        if(in_array($module,$event->html->modules))
        {
            $methodName = 'index';
            $controllerClass = "Modules\\Account\\Http\\Controllers\\Company\\SettingsController";
            if (class_exists($controllerClass)) {
                $controller = \App::make($controllerClass);
                if (method_exists($controller, $methodName)) {
                    $html = $event->html;
                    $settings = $html->getSettings();
                    $output =  $controller->{$methodName}($settings);
                    $html->add([
                        'html' => $output->toHtml(),
                        'order' => 100,
                        'module' => $module,
                        'permission' => 'account manage'
                    ]);
                }
            }
        }


        if(in_array($module,$event->html->modules))
        {
            $methodName = 'bankAccount';
            $controllerClass = "Modules\\Account\\Http\\Controllers\\Company\\SettingsController";
            if (class_exists($controllerClass)) {
                $controller = \App::make($controllerClass);
                if (method_exists($controller, $methodName)) {
                    $html = $event->html;
                    $settings = $html->getSettings();
                    $output =  $controller->{$methodName}($settings);
                    $html->add([
                        'html' => $output->toHtml(),
                        'order' => 1005,
                        'module' => $module,
                        'permission' => ''
                    ]);
                }
            }
        }
    }
}
