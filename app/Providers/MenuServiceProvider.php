<?php
namespace App\Providers;

use App\Events\CompanyMenuEvent;
use App\Events\CompanySettingEvent;
use App\Events\CompanySettingMenuEvent;
use App\Events\SuperAdminMenuEvent;
use App\Events\SuperAdminSettingEvent;
use App\Events\SuperAdminSettingMenuEvent;
use App\Listeners\CompanyMenuListener;
use App\Listeners\CompanySettingListener;
use App\Listeners\CompanySettingMenuListener;
use App\Listeners\SuperAdminMenuListener;
use App\Listeners\SuperAdminSettingListener;
use App\Listeners\SuperAdminSettingMenuListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as Provider;

class MenuServiceProvider extends Provider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    
    protected $listen = [
        SuperAdminMenuEvent::class => [
            SuperAdminMenuListener::class
        ],
        SuperAdminSettingMenuEvent::class => [
            SuperAdminSettingMenuListener::class
        ],
        SuperAdminSettingEvent::class => [
            SuperAdminSettingListener::class
        ],
        CompanyMenuEvent::class => [
            CompanyMenuListener::class
        ],
        CompanySettingMenuEvent::class => [
            CompanySettingMenuListener::class
        ],
        CompanySettingEvent::class => [
            CompanySettingListener::class
        ],
    ];
}
