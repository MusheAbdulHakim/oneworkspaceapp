<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\GarageManagement\Events\CreatevehicleType;
use Modules\PabblyConnect\Entities\PabblySend;

class CreatevehicleTypeLis
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
    public function handle(CreatevehicleType $event)
    {
        if (module_is_active('PabblyConnect')) {
            $vehicleType = $event->vehicleType;

            $pabbly_array = [
                'Vehicle Type Title' => $vehicleType->name,
            ];

            $action = 'New Vehicle Type';
            $module = 'GarageManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
