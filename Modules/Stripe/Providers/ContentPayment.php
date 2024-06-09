<?php

namespace Modules\Stripe\Providers;

use Illuminate\Support\ServiceProvider;

class ContentPayment extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(['tvstudio::storefront.*.checkout'], function ($view)
        {
            try {
                $ids = \Request::segment(2);
                if(!empty($ids))
                {
                    try {
                        $store = \Modules\TVStudio\Entities\TVStudioStore::where('slug',$ids)->first();
                        $company_settings = getCompanyAllSetting($store->created_by,$store->workspace);
                        if(module_is_active('Stripe', $store->created_by) && ((isset($company_settings['stripe_is_on']) ? $company_settings['stripe_is_on']:'off') == 'on') && (isset($company_settings['stripe_key'])) && (isset($company_settings['stripe_secret'])))
                        {
                            $view->getFactory()->startPush('content_payment', view('stripe::payment.content_payment',compact('store')));
                        }
                    } catch (\Throwable $th)
                    {

                    }
                }
            } catch (\Throwable $th) {

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
