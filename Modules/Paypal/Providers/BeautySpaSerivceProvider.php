<?php

namespace Modules\Paypal\Providers;

use App\Models\WorkSpace;
use Illuminate\Support\Facades\Request;
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
            $slug = Request::segment(2);
            $workspace = WorkSpace::where('id',$slug)->first();
            $company_settings = getCompanyAllSetting($workspace->created_by,$workspace->id);
            if((isset($company_settings['paypal_payment_is_on']) ? $company_settings['paypal_payment_is_on'] : 'off') == 'on' && !empty($company_settings['company_paypal_client_id']) && !empty($company_settings['company_paypal_secret_key']))
            {
                $view->getFactory()->startPush('beauty_payment', view('paypal::payment.beauty_payment',compact('slug')));


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
