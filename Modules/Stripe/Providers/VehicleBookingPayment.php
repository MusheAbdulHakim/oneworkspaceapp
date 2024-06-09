<?php

namespace Modules\Stripe\Providers;

use App\Models\WorkSpace;
use Illuminate\Support\ServiceProvider;

class VehicleBookingPayment extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */

    public function boot()
    {
        view()->composer(['vehiclebookingmanagement::seat_booking.create'], function ($view) {
            try {
                $slug = \Request::segment(2);
                $vehicle_id = \Request::segment(3);

                if (!empty($slug)) {
                    $workspace = WorkSpace::where('slug', $slug)->where('is_disable', '1')->first();
                    $company_settings = getCompanyAllSetting($workspace->created_by, $workspace->id);
                    $type = 'vehiclebookingpayment';
                    if (module_is_active('Stripe', $workspace->created_by) && ((isset($company_settings['stripe_is_on']) ? $company_settings['stripe_is_on'] : 'off') == 'on') && (isset($company_settings['stripe_key'])) && (isset($company_settings['stripe_secret']))) {
                        $view->getFactory()->startPush('vehicle_booking_payment_div', view('stripe::payment.vehiclebooking_nav_containt_div', compact('type', 'slug', 'vehicle_id')));

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
