<?php

namespace Modules\SideMenuBuilder\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\SideMenuBuilder\Entities\SideMenuBuilder;

class AddHookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        view()->composer(['partials.sidebar'], function ($modules) {
            $active_module =  ActivatedModule();
            $dependency = explode(',', 'SideMenuBuilder');
            if (!empty(array_intersect($dependency, $active_module))) {
                $module = SideMenuBuilder::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get();
                
                $modules->getFactory()->startPush('custom_side_menu', view('sidemenubuilder::layouts.addhook', compact('module')));
            }
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
