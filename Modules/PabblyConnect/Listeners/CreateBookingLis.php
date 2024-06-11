<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Fleet\Entities\Vehicle;
use Modules\Fleet\Events\CreateBooking;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateBookingLis
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
    public function handle(CreateBooking $event)
    {
        if(module_is_active('PabblyConnect')){
            $request = $event->request;
            $booking = $event->bookings;
            
            $customer = User::find($booking->customer_name);
            $vehicle = Vehicle::find($booking->vehicle_name);
            
            $action = 'New Booking';
            $module = 'Fleet';
            $pabbly_array = array(
                "Customer Name"  => $customer->name,
                "Customer Email" => $customer->email,
                "Vehicle Title"  => $vehicle->name,
                "Trip Type"      => $booking['trip_type'],
                "Start Date"     => $booking['start_date'],
                "End Date"       => $booking['end_date'],
                "Start Address"  => $booking['start_address'],
                "End Address"    => $booking['end_address'],
                "Total Price"    => $booking['total_price'],
                "Booking Status" => $booking['status'],
                "Notes"          => $booking['notes']
            );

            PabblySend::SendPabblyCall($module ,$pabbly_array,$action);
        }
    }
}