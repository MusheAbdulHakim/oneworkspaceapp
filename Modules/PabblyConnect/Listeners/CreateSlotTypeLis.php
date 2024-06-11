<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\ParkingManagement\Events\CreateSlotType;

class CreateSlotTypeLis
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
    public function handle(CreateSlotType $event)
    {
        if (module_is_active('PabblyConnect')) {
            $slot_type = $event->slot_type;

            $pabbly_array = [
                'Slot Type Title' => $slot_type->name,
                'Slot Type Location' => $slot_type->location,
            ];

            $action = 'New Slot Type';
            $module = 'ParkingManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
