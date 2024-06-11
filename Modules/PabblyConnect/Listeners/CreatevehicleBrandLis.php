<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\GarageManagement\Events\CreatevehicleBrand;
use Modules\PabblyConnect\Entities\PabblySend;

class CreatevehicleBrandLis
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
    public function handle(CreatevehicleBrand $event)
    {
        if (module_is_active('PabblyConnect')) {
            $vehicleBrand = $event->vehicleBrand;

            $pabbly_array = [
                'Vehicle Brand Title' => $vehicleBrand->name
            ];

            $action = 'New Vehicle Brand';
            $module = 'GarageManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
