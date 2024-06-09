<?php

namespace Modules\Stripe\Providers;

use App\Models\WorkSpace;
use Illuminate\Support\ServiceProvider;

class ParkingSerivceProvider extends ServiceProvider
{
    public function boot(){
        view()->composer(['parkingmanagement::frontend.detail'], function ($view)
        {
                $slug = \Request::segment(1);
                $lang = \Request::segment(3);
                $workspace = WorkSpace::where('slug',$slug)->first();
                $company_settings = getCompanyAllSetting($workspace->created_by,$workspace->id);
                if((isset($company_settings['stripe_is_on']) ? $company_settings['stripe_is_on'] : 'off') == 'on' && !empty($company_settings['stripe_key']) && !empty($company_settings['stripe_secret']))
                {
                    $view->getFactory()->startPush('parking_payment', view('stripe::payment.parking_payment',compact('slug','lang')));
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
