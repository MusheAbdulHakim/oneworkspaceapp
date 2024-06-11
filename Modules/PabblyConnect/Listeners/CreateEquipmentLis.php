<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\GymManagement\Events\CreateEquipment;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateEquipmentLis
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
    public function handle(CreateEquipment $event)
    {
        if (module_is_active('PabblyConnect')) {
            $equipment = $event->equipment;

            $pabbly_array = [
                'Equipment' => $equipment->name
            ];

            $action = 'New Equipment';
            $module = 'GymManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
