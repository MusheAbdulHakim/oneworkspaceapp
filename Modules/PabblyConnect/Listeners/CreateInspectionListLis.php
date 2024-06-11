<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\VehicleInspectionManagement\Events\CreateInspectionList;

class CreateInspectionListLis
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
    public function handle(CreateInspectionList $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $inspectionList = $event->inspectionList;

            $pabbly_array = [
                'Inspection Name' => $inspectionList->name,
                'Inspection Period' => $inspectionList->period,
                'Inspection Notes' => $inspectionList->notes
            ];

            $action = 'New Inspection List';
            $module = 'VehicleInspectionManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
