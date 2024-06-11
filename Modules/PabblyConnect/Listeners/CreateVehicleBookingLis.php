<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Fleet\Entities\Vehicle;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\VehicleBookingManagement\Entities\Route;
use Modules\VehicleBookingManagement\Events\CreateVehicleBooking;

class CreateVehicleBookingLis
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
    public function handle(CreateVehicleBooking $event)
    {
        if (module_is_active('PabblyConnect')) {
            $booking = $event->booking;
            $request = $event->request;

            $vehicle = Vehicle::find($booking->vehicle_id);
            $route = Route::find($booking->route_id);

            $pabbly_array = [
                'Vehicle' => $vehicle->name,
                'Starting Point' => $route->starting_point,
                'Ending Point' => $route->dropping_point,
                'Start Time' => $route->start_time,
                'End Time' => $route->end_time,
                'Price' => $route->price,
                'Trip Date' => $booking->date,
                'From' => $booking->from,
                'To' => $booking->to,
                'Booking Status' => $booking->status,
                'Booking Seats' => implode(',', $request->selectedSeats),
                'Customer Information' => $request['formData']['users'],
            ];

            $action = 'New Vehicle Booking';
            $module = 'VehicleBookingManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
