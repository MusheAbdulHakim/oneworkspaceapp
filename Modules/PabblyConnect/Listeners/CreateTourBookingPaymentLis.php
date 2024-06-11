<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\TourTravelManagement\Entities\Tour;
use Modules\TourTravelManagement\Entities\TourInquiry;
use Modules\TourTravelManagement\Events\CreateTourBookingPayment;

class CreateTourBookingPaymentLis
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
    public function handle(CreateTourBookingPayment $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $payment = $event->payment;

            $tour_data = Tour::find($payment->tour_id);
            $inquiry_data = TourInquiry::find($payment->inquiry_id);


            $pabbly_array = [
                'Tour Title' => $tour_data->tour_name,
                'Inquiry Person Name' => $inquiry_data->person_name,
                'Person Email' => $inquiry_data->email_id,
                'Payment Date' => $payment->payment_date,
                'Payment Amount' => $payment->amount,
                'Payment Status' => $payment->status,
                'Description' => $payment->description,
                'Reference' => $payment->reference,
            ];

            $action = 'New Tour Booking Payment';
            $module = 'TourTravelManagement';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
