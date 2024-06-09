<?php

namespace Modules\Paypal\Listeners;
use App\Events\SuperAdminSettingEvent;

class SuperAdminSettingListener
{
    /**
     * Handle the event.
     */
    public function handle(SuperAdminSettingEvent $event): void
    {
        $module = 'Paypal';
        if(in_array($module,$event->html->modules))
        {
            $methodName = 'index';
            $controllerClass = "Modules\\Paypal\\Http\\Controllers\\SuperAdmin\\SettingsController";
            if (class_exists($controllerClass)) {
                $controller = \App::make($controllerClass);
                if (method_exists($controller, $methodName)) {
                    $html = $event->html;
                    $settings = $html->getSettings();
                    $output =  $controller->{$methodName}($settings);
                    $html->add([
                        'html' => $output->toHtml(),
                        'order' => 1020,
                        'module' => $module,
                        'permission' => 'paypal manage'
                    ]);
                }
            }
        }
    }
}
