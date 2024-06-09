<?php

namespace Modules\Paypal\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposer extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */

    public function boot()
    {
        view()->composer(['plans.marketplace','plans.planpayment'], function ($view)
        {
            if(\Auth::check())
            {
                $admin_settings = getAdminAllSetting();

                if(isset($admin_settings['paypal_payment_is_on']) && $admin_settings['paypal_payment_is_on'] == 'on' && !empty($admin_settings['company_paypal_client_id']) && !empty($admin_settings['company_paypal_secret_key']))
                {
                    $view->getFactory()->startPush('company_plan_payment', view('paypal::payment.plan_payment'));
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
