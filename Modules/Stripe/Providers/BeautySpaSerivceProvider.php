<?php

namespace Modules\Stripe\Providers;

use App\Models\WorkSpace;
use Illuminate\Support\ServiceProvider;

class BeautySpaSerivceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */

    public function boot(){

        view()->composer(['beautyspamanagement::frontend.append'], function ($view)
        {
                $slug = \Request::segment(2);
                $workspace = WorkSpace::where('id',$slug)->first();
                $company_settings = getCompanyAllSetting($workspace->created_by,$workspace->id);
                if((isset($company_settings['stripe_is_on']) ? $company_settings['stripe_is_on'] : 'off') == 'on' && !empty($company_settings['stripe_key']) && !empty($company_settings['stripe_secret']))
                {
                    $view->getFactory()->startPush('beauty_payment', view('stripe::payment.beauty_payment',compact('slug')));
                }
        });
    }

    public function register()
    {

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
