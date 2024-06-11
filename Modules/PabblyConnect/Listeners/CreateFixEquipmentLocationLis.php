<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\FixEquipment\Events\CreateLocation;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFixEquipmentLocationLis
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
    public function handle(CreateLocation $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $location = $event->location;

            $pabbly_array = [
                'Location Title' => $location->location_name,
                'Location Address' => $location->address,
                'Location Description' => $location->location_description,
            ];

            $action = 'New Location';
            $module = 'FixEquipment';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
