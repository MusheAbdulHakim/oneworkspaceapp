<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\VehicleInspectionManagement\Entities\InspectionList;
use Modules\VehicleInspectionManagement\Entities\InspectionVehicle;
use Modules\VehicleInspectionManagement\Events\CreateInspectionRequest;

class CreateInspectionRequestLis
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
    public function handle(CreateInspectionRequest $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $inspectionRequest = $event->inspectionRequest;

            $inspection_id = explode(',', $inspectionRequest->inspections);
            $vehicle = InspectionVehicle::find($inspectionRequest->vehicle_id);
            $inspections = InspectionList::whereIn('id', $inspection_id)->get();

            $attributesArray = [];

            foreach ($inspections as $inspection) {
                $attributes = [
                    'Inspection Name' => $inspection->name,
                    'Inspection Period' => $inspection->period,
                    'Inspection Notes' => $inspection->notes,

                ];
                $attributesArray[] = $attributes;
            }

            $pabbly_array = [
                'Inspector Name' => $inspectionRequest->inspector_name,
                'Inspector Email' => $inspectionRequest->inspector_email,
                'Inspection Vehicle' => $vehicle->model,
                'Inspections' => $attributesArray,
            ];

            $action = 'New Vehicle Inspection Request';
            $module = 'VehicleInspectionManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
