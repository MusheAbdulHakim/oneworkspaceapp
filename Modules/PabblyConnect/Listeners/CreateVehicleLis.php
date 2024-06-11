<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Fleet\Entities\Driver;
use Modules\Fleet\Entities\FuelType;
use Modules\Fleet\Entities\VehicleType;
use Modules\Fleet\Events\CreateVehicle;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateVehicleLis
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
    public function handle(CreateVehicle $event)
    {
        if(module_is_active('PabblyConnect')){
            $vehicle = $event->Vehicle;
            $request = $event->request;
            
            $vehicle_type = VehicleType::find($vehicle->vehicle_type);
            $fuel_type    = FuelType::find($vehicle->fuel_type);
            $driver       = Driver::find($vehicle->driver_name);

            $action = 'New Vehicle';
            $module = 'Fleet';
            
            $pabbly_array = array(
                "Vehicle Title"                    => $vehicle['name'],
                "Vehicle Type"                     => $vehicle_type->name,
                "Driver Name"                      => $driver->name,
                "Driver Phone"                     => $driver->phone,
                "Vehicle Registration Date"        => $vehicle['registration_date'],
                "Vehicle Registration Expire Date" => $vehicle['register_ex_date'],
                "Vehicle Lincense Plate"           => $vehicle['lincense_plate'],
                "Vehicle Id Number"                => $vehicle['vehical_id_num'],
                "Vehicle Model Year"               => $vehicle['model_year'],
                "Vehicle Fuel Type"                => $fuel_type->name,
                "Vehicle Seat Capacity"            => $vehicle['seat_capacity'],
                "Vehicle Status"                   => $vehicle['status']
            );

            PabblySend::SendPabblyCall($module ,$pabbly_array,$action);
        }
    }
}