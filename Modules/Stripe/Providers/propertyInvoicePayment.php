<?php

namespace Modules\Stripe\Providers;

use Illuminate\Support\ServiceProvider;

class propertyInvoicePayment extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */


    public function boot(){
        view()->composer(['propertymanagement::propertyinvoice.view'], function ($view)
        {
            if(\Auth::check())
            {
                $company_settings = getCompanyAllSetting();
                if(module_is_active('Stripe', \Auth::user()->created_by) && (isset($company_settings['stripe_is_on']) ? $company_settings['stripe_is_on'] : 'off') == 'on' && !empty($company_settings['stripe_key']) && !empty($company_settings['stripe_secret']))
                {
                    $view->getFactory()->startPush('company_property_payment', view('stripe::payment.property_payment'));
                }
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
