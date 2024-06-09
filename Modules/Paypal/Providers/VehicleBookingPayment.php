<?php

namespace Modules\Paypal\Providers;

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

                    if (module_is_active('Paypal', $workspace->created_by) && ($company_settings['paypal_payment_is_on'] == 'on') && ($company_settings['company_paypal_client_id']) && ($company_settings['company_paypal_secret_key'])) {
                        $view->getFactory()->startPush('vehicle_booking_payment_div', view('paypal::payment.vehiclebooking_payment', compact('slug', 'vehicle_id')));
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
