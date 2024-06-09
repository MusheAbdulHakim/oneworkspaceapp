<?php

namespace Modules\Lead\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;



class ViewComposer extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot(){
        view()->composer(['reminder::reminder.create','reminder::reminder.edit'], function ($view)
        {

            if (Auth::check() && module_is_active('Lead'))
            {
                $view->getFactory()->startPush('module_name', view('lead::leads.module_name'));
            }

        });
    }
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
