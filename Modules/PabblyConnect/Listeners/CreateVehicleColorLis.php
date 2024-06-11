<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\GarageManagement\Events\CreateVehicleColor;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateVehicleColorLis
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
    public function handle(CreateVehicleColor $event)
    {
        if (module_is_active('PabblyConnect')) {
            $vehicleColor = $event->vehicleColor;

            $pabbly_array = [
                'Vehicle Color Title' => $vehicleColor->name,
            ];

            $action = 'New Vehicle Color';
            $module = 'GarageManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
