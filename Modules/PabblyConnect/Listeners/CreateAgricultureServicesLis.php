<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\AgricultureManagement\Entities\AgricultureActivities;
use Modules\AgricultureManagement\Events\CreateAgricultureServices;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateAgricultureServicesLis
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
    public function handle(CreateAgricultureServices $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $agricultureservice = $event->agricultureservice;

            $activity = AgricultureActivities::find($agricultureservice->activity);

            $pabbly_array = [
                'Agriculture Services Title' => $agricultureservice->name,
                'Agriculture Activity Title' => $activity->name,
                'Quantity' => $agricultureservice->qty,
                'UTM' => $agricultureservice->utm,
                'Unit Price' => $agricultureservice->unit_price,
                'Total Price' => $agricultureservice->total_price,
            ];

            $action = 'New Agriculture Services';
            $module = 'AgricultureManagement';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
