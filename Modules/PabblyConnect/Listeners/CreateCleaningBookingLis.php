<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CleaningManagement\Events\CreateCleaningBooking;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCleaningBookingLis
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
    public function handle(CreateCleaningBooking $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $booking = $event->booking;

            $pabbly_array = [
                'Customer Name' => $booking->customer_name,
                'Building Type' => $booking->building_type,
                'Customer Address' => $booking->address,
                'Description' => $booking->description,
                'Booking Date' => $booking->booking_date,
                'Cleaning Date' => $booking->cleaning_date,
                'Status' => $booking->status == 0 ? 'Requested' : '',
            ];

            $action = 'New Cleaning Team';
            $module = 'CleaningManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
