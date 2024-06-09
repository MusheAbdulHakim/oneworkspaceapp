<?php

namespace App\Listeners;

use App\Events\SuperAdminSettingEvent;

class SuperAdminSettingListener
{
    /**
     * Handle the event.
     */
    public function handle(SuperAdminSettingEvent $event): void
    {

        $module = 'Base';
        $methodName = 'index';
        $controllerClass = "App\\Http\\Controllers\\SuperAdmin\\SettingsController";

        if (class_exists($controllerClass)) {
            $controller = \App::make($controllerClass);
            if (method_exists($controller, $methodName)) {
                $html = $event->html;
                $settings = $html->getSettings();
                $output =  $controller->{$methodName}($settings);
                $html->add([
                    'html' => $output->toHtml(),
                    'order' => 10,
                    'module' => $module,
                    'permission' => 'setting manage'
                ]);
            }
        }

        // for email setting

        $methodName = 'emailSettingGet';
        $controllerClass = "App\\Http\\Controllers\\SettingsController";

        if (class_exists($controllerClass)) {
            $controller = \App::make($controllerClass);
            if (method_exists($controller, $methodName)) {
                $html = $event->html;
                $settings = $html->getSettings();
                $output =  $controller->{$methodName}($settings);
                $html->add([
                    'html' => $output->toHtml(),
                    'order' => 500,
                    'module' => $module,
                    'permission' => 'setting manage'
                ]);
            }
        }

        // for bank payment setting

        $methodName = 'settingGet';
        $controllerClass = "App\\Http\\Controllers\\BanktransferController";

        if (class_exists($controllerClass)) {
            $controller = \App::make($controllerClass);
            if (method_exists($controller, $methodName)) {
                $html = $event->html;
                $settings = $html->getSettings();
                $output =  $controller->{$methodName}($settings);
                $html->add([
                    'html' => $output->toHtml(),
                    'order' => 1000,
                    'module' => $module,
                    'permission' => 'setting manage'
                ]);
            }
        }

    }
}
