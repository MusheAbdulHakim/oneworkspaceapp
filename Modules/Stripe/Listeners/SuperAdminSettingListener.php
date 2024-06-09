<?php

namespace Modules\Stripe\Listeners;
use App\Events\SuperAdminSettingEvent;

class SuperAdminSettingListener
{
    /**
     * Handle the event.
     */
    public function handle(SuperAdminSettingEvent $event): void
    {
        $module = 'Stripe';
        if(in_array($module,$event->html->modules))
        {
            $methodName = 'index';
            $controllerClass = "Modules\\Stripe\\Http\\Controllers\\SuperAdmin\\SettingsController";
            if (class_exists($controllerClass)) {
                $controller = \App::make($controllerClass);
                if (method_exists($controller, $methodName)) {
                    $html = $event->html;
                    $settings = $html->getSettings();
                    $output =  $controller->{$methodName}($settings);
                    $html->add([
                        'html' => $output->toHtml(),
                        'order' => 1010,
                        'module' => $module,
                        'permission' => 'stripe manage'
                    ]);
                }
            }
        }
    }
}
