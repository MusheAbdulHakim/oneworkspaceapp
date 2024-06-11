<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Fleet\Entities\Booking;
use Modules\Fleet\Events\CreateFleetPayment;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFleetPaymentLis
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
    public function handle(CreateFleetPayment $event)
    {
        if(module_is_active('Zapier')){
            $request = $event->request;
            $payment = $event->Payment;

            $booking = Booking::find($payment->booking_id);
            $customer = User::find($booking->customer_name);

            $action = 'New Fleet Payment';
            $module = 'Fleet';
            $zap_array = array(
                "Customer Name" => $customer->name,
                "Trip Date"     => $booking->start_date,
                "Ammount Pay"   => $payment['pay_amount'],
                "Description"   => $payment['description'],
            );

            PabblySend::SendPabblyCall($module ,$zap_array,$action);
        }
    }
}