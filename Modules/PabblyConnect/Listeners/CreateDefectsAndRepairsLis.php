<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\VehicleInspectionManagement\Entities\InspectionRequest;
use Modules\VehicleInspectionManagement\Entities\InspectionVehicle;
use Modules\VehicleInspectionManagement\Events\CreateDefectsAndRepairs;

class CreateDefectsAndRepairsLis
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
    public function handle(CreateDefectsAndRepairs $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $invoice = $event->invoice;

            $inspection_request = InspectionRequest::find($request->request_id);
            $vehicle = InspectionVehicle::find($inspection_request->vehicle_id);

            $pabbly_array = [
                'Inspector Name' => $inspection_request->inspector_name,
                'Inspector Email' => $inspection_request->inspector_email,
                'Inspecton Vehicle' => $vehicle->model,
                'Status' => $inspection_request->status,
                'Invoice Issue Date' => $invoice->issue_date,
                'Invoice Due Date' => $invoice->due_date,
                'Service Charge' => $invoice->service_charge,
                'Parts' => $request->items
            ];

            $action = 'New Defects And Repairs';
            $module = 'VehicleInspectionManagement';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
