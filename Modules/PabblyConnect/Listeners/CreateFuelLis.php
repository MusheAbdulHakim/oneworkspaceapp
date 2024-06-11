<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Fleet\Entities\Driver;
use Modules\Fleet\Entities\FuelType;
use Modules\Fleet\Entities\Vehicle;
use Modules\Fleet\Events\CreateFuel;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFuelLis
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
    public function handle(CreateFuel $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $fuel = $event->fuel;

            $driver = Driver::find($fuel->driver_name);
            $vehicle = Vehicle::find($fuel->vehicle_name);
            $fuel_type = FuelType::find($fuel->fuel_type);

            $pabbly_array = [
                'Driver Name' => $driver->name,
                'Driver Email' => $driver->email,
                'Vehicle Title' => $vehicle->name,
                'Fill Date' => $fuel->fill_date,
                'Fuel Type' => $fuel_type->name,
                'Quantity' => $fuel->quantity,
                'Cost' => $fuel->cost,
                'Total Cost' => $fuel->total_cost,
                'Odometer Reading' => $fuel->odometer_reading,
                'Notes' => $fuel->notes
            ];

            $module = 'Fleet';
            $action = 'New Fuel';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
