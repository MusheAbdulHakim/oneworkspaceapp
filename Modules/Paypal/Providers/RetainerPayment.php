<?php

namespace Modules\Paypal\Providers;

use Illuminate\Support\ServiceProvider;

class RetainerPayment extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */

    public function boot(){
        view()->composer(['retainer::retainer.retainerpay'], function ($view)
        {
            $route = \Request::route()->getName();
            if($route =='pay.retainer')
            {
                try {
                    $ids = \Request::segment(3);
                    if(!empty($ids))
                    {
                        $id = \Illuminate\Support\Facades\Crypt::decrypt($ids);

                        $invoice = \Modules\Retainer\Entities\Retainer::where('id',$id)->first();
                        $type = 'retainer';
                        $company_settings = getCompanyAllSetting( $invoice->created_by,$invoice->workspace);
                        if(module_is_active('Paypal', $invoice->created_by) && ($company_settings['paypal_payment_is_on']  == 'on') && ($company_settings['company_paypal_client_id']) && ($company_settings['company_paypal_secret_key']))
                        {
                            $view->getFactory()->startPush('retainer_payment_tab', view('paypal::payment.sidebar'));
                            $view->getFactory()->startPush('retainer_payment_div', view('paypal::payment.nav_containt_div',compact('type','invoice','company_settings')));
                        }
                    }
                } catch (\Throwable $th) {

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
