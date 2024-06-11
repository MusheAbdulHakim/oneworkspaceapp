<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\VehicleInspectionManagement\Events\CreateInspectionVehicle;

class CreateInspectionVehicleLis
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
    public function handle(CreateInspectionVehicle $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $inspectionVehicle = $event->inspectionVehicle;

            $pabbly_array = [
                'Vehicle Model' => $inspectionVehicle->model,
                'Vehicle License Plate' => $inspectionVehicle->lincense_plate,
                'Vehicle Manufacture Year' => $inspectionVehicle->manufacture_year,
                'Vehicle Milage' => $inspectionVehicle->mileage,
                'Vehicle Number ID' => $inspectionVehicle->vehicle_id_number,
                'Vehicle Last Inspection Date' => $inspectionVehicle->last_inspection_date,
                'Vehicle Inspection Remaining Days' => $inspectionVehicle->inspection_reminder_days
            ];

            $action = 'New Inspection Vehicle';
            $module = 'VehicleInspectionManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
