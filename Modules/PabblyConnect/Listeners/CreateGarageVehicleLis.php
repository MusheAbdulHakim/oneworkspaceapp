<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\GarageManagement\Entities\FuelType;
use Modules\GarageManagement\Entities\VehicleBrand;
use Modules\GarageManagement\Entities\VehicleColor;
use Modules\GarageManagement\Entities\VehicleType;
use Modules\GarageManagement\Events\CreateGarageVehicle;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateGarageVehicleLis
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
    public function handle(CreateGarageVehicle $event)
    {
        if (module_is_active('PabblyConnect')) {
            $garagevehicle = $event->garagevehicle;

            $vehicle_type = VehicleType::find($garagevehicle->vehicle_type);
            $vehicle_brand = VehicleBrand::find($garagevehicle->vehicle_brand);
            $vehicle_color = VehicleColor::find($garagevehicle->vehicle_color);
            $vehicle_fule_type = FuelType::find($garagevehicle->vehicle_fueltype);

            $pabbly_array = [
                'Vehicle Type' => $vehicle_type->name,
                'Vehicle Brand' => $vehicle_brand->name,
                'Vehicle Color' => $vehicle_color->name,
                'Vehicle Fule Type' => $vehicle_fule_type->name,
                'Vehicle Model Name' => $garagevehicle->model_name,
                'Vehicle Model Year' => $garagevehicle->model_year,
                'Vehicle Plate Number' => $garagevehicle->plate_number,
                'Vehicle Key Number' => $garagevehicle->key_number,
                'Vehicle Gear Box' => $garagevehicle->gear_box,
                'Vehicle Engine Number' => $garagevehicle->engine_number,
                'Vehicle Production Date' => $garagevehicle->production_date,
                'Vehicle Cost' => $garagevehicle->cost,
                'Vehicle Notes' => $garagevehicle->notes
            ];

            $action = 'New Garage Vehicle';
            $module = 'GarageManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
