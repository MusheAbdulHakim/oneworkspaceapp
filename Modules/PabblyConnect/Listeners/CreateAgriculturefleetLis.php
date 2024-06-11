<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\AgricultureManagement\Events\CreateAgriculturefleet;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateAgriculturefleetLis
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
    public function handle(CreateAgriculturefleet $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $agriculture_fleet = $event->agriculture_fleet;

            $pabbly_array = [
                "Fleet Title" => $agriculture_fleet->name,
                "Fleet Capacity" => $agriculture_fleet->capacity,
                "Fleet Price" => $agriculture_fleet->price,
                "Fleet Quantity" => $agriculture_fleet->quantity,
            ];

            $action = 'New Agriculture Fleet';
            $module = 'AgricultureManagement';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
