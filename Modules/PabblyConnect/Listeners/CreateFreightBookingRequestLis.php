<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\FreightManagementSystem\Events\CreateFreightBookingRequest;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFreightBookingRequestLis
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
    public function handle(CreateFreightBookingRequest $event)
    {
        if (module_is_active('PabblyConnect')) {
            $booking_request = $event->booking_request;

            $pabbly_array = [
                'Direction' => $booking_request->direction,
                'Transport' => $booking_request->transport,
                'Customer Name' => $booking_request->customer_name,
                'Customer Email' => $booking_request->customer_email,
                'Loading Port' => $booking_request->loading_port,
                'Discharge Port' => $booking_request->discharge_port,
                'Vessel' => $booking_request->vessel,
                'Date' => $booking_request->date,
                'Barcode' => $booking_request->barcode,
                'Tracking Number' => $booking_request->tracking_no
            ];

            $action = 'New Freight Booking Request';
            $module = 'FreightManagementSystem';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
