<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\ParkingManagement\Entities\SlotType;
use Modules\ParkingManagement\Events\CreateSlot;

class CreateSlotLis
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
    public function handle(CreateSlot $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $slot = $event->slot;
            $slot_type = SlotType::find($slot->slot_type);

            $pabbly_array = [
                'Slot Title' => $slot->name,
                'Slote Type Title' => $slot_type->name,
                'Type' => $slot->type == 0 ? 'Public' : 'Private',
                'Number of Slots' => $slot->no_of_slot,
                'Start Time' => $slot->start_time,
                'End Time' => $slot->end_time,
                'Slot Status' => $slot->status == 0 ? 'Active' : 'Inactive',
                'Amount' => $slot->amount,
            ];

            $action = 'New Slot';
            $module = 'ParkingManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
