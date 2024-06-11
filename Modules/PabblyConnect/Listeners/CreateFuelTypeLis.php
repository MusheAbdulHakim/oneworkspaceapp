<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\GarageManagement\Events\CreateFuelType;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFuelTypeLis
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
    public function handle(CreateFuelType $event)
    {
        if (module_is_active('PabblyConnect')) {
            $fuelType = $event->fuelType;

            $pabbly_array = [
                'Fule Type' => $fuelType->name
            ];

            $action = 'New Fule Type';
            $module = 'GarageManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
