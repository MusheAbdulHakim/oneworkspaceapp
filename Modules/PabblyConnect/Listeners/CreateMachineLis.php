<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MachineRepairManagement\Events\CreateMachine;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateMachineLis
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
    public function handle(CreateMachine $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $machine = $event->machine;

            $pabbly_array = [
                'Machine Title' => $machine->name,
                'Machine Manufacturer' => $machine->manufacturer,
                'Machine Model' => $machine->model,
                'Machine Installation Date' => $machine->installation_date,
                'Machine Description' => $machine->description,
                'Machine Last Maintenance Date' => $machine->last_maintenance_date,
                'Machine Status' => $machine->status,
            ];

            $action = 'New Machine';
            $module = 'MachineRepairManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
