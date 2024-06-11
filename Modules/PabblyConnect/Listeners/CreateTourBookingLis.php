<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\TourTravelManagement\Entities\Tour;
use Modules\TourTravelManagement\Entities\TourInquiry;
use Modules\TourTravelManagement\Events\CreateTourBooking;

class CreateTourBookingLis
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
    public function handle(CreateTourBooking $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $tour_booking = $event->tour_booking;

            $tour_data = Tour::find($tour_booking->tour_id);
            $inquiry_data = TourInquiry::find($tour_booking->inquiry_id);

            $pabbly_array = [
                'Tour Title' => $tour_data->tour_name,
                'Inquiry Person Name' => $inquiry_data->person_name,
                'Inquiry Person Email' => $inquiry_data->email_id,
                'Inquiry Person Mobile Number' => $inquiry_data->mobile_no,
                'Inquiry Date' => $inquiry_data->inquiry_date,
            ];

            $action = 'New Tour Booking';
            $module = 'TourTravelManagement';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
