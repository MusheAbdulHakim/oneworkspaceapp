<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Fleet\Entities\Vehicle;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\VehicleBookingManagement\Events\CreateVehicleRoute;

class CreateVehicleRouteLis
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
    public function handle(CreateVehicleRoute $event)
    {
        if (module_is_active('PabblyConnect')) {
            $route = $event->route;

            $vehicle = Vehicle::find($route->vehicle_id);

            $pabbly_array = [
                'Vehicle Name' => $vehicle->name,
                'Starting Point' => $route->starting_point,
                'Dropping Point' => $route->dropping_point,
                'Start Address' => $route->start_address,
                'End Address' => $route->end_address,
                'Start Time' => $route->start_time,
                'End Time' => $route->end_time,
                'Price' => $route->price
            ];

            $action = 'New Vehicle Route';
            $module = 'VehicleBookingManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
