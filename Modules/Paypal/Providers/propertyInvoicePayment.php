<?php

namespace Modules\Paypal\Providers;

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
                if(module_is_active('Paypal', \Auth::user()->created_by) && isset($company_settings['paypal_payment_is_on']) && $company_settings['paypal_payment_is_on'] == 'on' && !empty($company_settings['company_paypal_client_id']) && !empty($company_settings['company_paypal_secret_key']))
                {
                    $view->getFactory()->startPush('company_property_payment', view('paypal::payment.property_payment'));
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
