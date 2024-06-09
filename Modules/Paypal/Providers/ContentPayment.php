<?php

namespace Modules\Paypal\Providers;

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
                         if(module_is_active('Paypal', $store->created_by) && ($company_settings['paypal_payment_is_on']  == 'on') && ($company_settings['company_paypal_client_id']) && ($company_settings['company_paypal_secret_key']))
                         {
                             $view->getFactory()->startPush('content_payment', view('paypal::payment.content_payment',compact('store')));
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
