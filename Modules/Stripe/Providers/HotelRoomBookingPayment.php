<?php

namespace Modules\Stripe\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Holidayz\Entities\Hotels;

class HotelRoomBookingPayment extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot(){
        view()->composer(['holidayz::frontend.*.checkout'], function ($view) //try * replace to theme1
        {
            try {
                $slug = \Request::segment(2);
                if(!empty($slug))
                {
                    $hotel = Hotels::where('slug',$slug)->where('is_active', '1')->first();
                    $company_settings = getCompanyAllSetting($hotel->created_by,$hotel->workspace);
                    $type = 'roombookinginvoice';
                    if(module_is_active('Stripe', $hotel->created_by) && ((isset($company_settings['stripe_is_on'])? $company_settings['stripe_is_on'] : 'off')  == 'on') && (isset($company_settings['stripe_key'])) && (isset($company_settings['stripe_secret'])))
                    {
                        $view->getFactory()->startPush('hotel_room_booking_payment_div', view('stripe::payment.holidayz_nav_containt_div',compact('type','slug')));

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
