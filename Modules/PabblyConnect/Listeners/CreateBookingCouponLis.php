<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Holidayz\Events\CreateBookingCoupon;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateBookingCouponLis
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CreateBookingCoupon $event)
    {
        if (module_is_active('PabblyConnect')) {

            $request = $event->request;
            $coupon = $event->coupon;

            $zap_array = [
                'Coupon Title' => $coupon->name,
                'Coupon Discount' => $coupon->discount,
                'Coupon Limit' => $coupon->limit,
                'Coupon Code' => $request->autoCode ? $request->autoCode : $request->manualCode,
            ];

            $action = 'New Booking Coupon';
            $module = 'Holidayz';
            PabblySend::SendPabblyCall($module, $zap_array, $action);
        }
    }
}
