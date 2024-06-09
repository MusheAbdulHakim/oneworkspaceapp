<?php

namespace Modules\LandingPage\Providers;

use Illuminate\Support\Facades\Route;
// use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\ServiceProvider;
use Modules\LandingPage\Entities\LandingPageSetting;
use Illuminate\Support\Facades\Schema;

class ViewComposer extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public $settings;


    public function boot(){

        view()->composer(['auth.*'], function ($view) {
            if (Schema::hasTable('landing_page_settings')) {
                $settings = LandingPageSetting::settings();
                $view->getFactory()->startPush('custom_page_links', view('landingpage::layouts.buttons',compact('settings')));
            }    
        });

        
    }

}
