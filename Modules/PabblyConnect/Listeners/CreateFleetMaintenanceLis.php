<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Fleet\Entities\MaintenanceType;
use Modules\Fleet\Entities\Vehicle;
use Modules\Fleet\Events\CreateMaintenances;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFleetMaintenanceLis
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
    public function handle(CreateMaintenances $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $maintenance = $event->Maintenances;

            $service_for = User::find($maintenance->service_for);
            $vehicle = Vehicle::find($maintenance->vehicle_name);
            $maintenance_type = MaintenanceType::find($maintenance->maintenance_type);

            $pabbly_array = [
                'Service Type' => $maintenance->service_type,
                'Customer Name' => $service_for->name,
                'Customer Email' => $service_for->email,
                'Vehicle Name' => $vehicle->name,
                'Maintenance Type' => $maintenance_type->name,
                'Service Title' => $maintenance->service_name,
                'Service Charge' => $maintenance->charge,
                'Charge Bear By' => $maintenance->charge_bear_by,
                'Maintenance Date' => $maintenance->maintenance_date,
                'Maintenance Priority' => $maintenance->priority,
                'Maintenance Total Cost' => $maintenance->total_cost,
                'Notes' => $maintenance->notes
            ];

            $module = 'Fleet';
            $action = 'New Maintenance';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
