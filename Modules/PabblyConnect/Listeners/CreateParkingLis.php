<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\ParkingManagement\Entities\Slot;
use Modules\ParkingManagement\Entities\SlotType;
use Modules\ParkingManagement\Events\CreateParking;

class CreateParkingLis
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
    public function handle(CreateParking $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $parking = $event->parking;

            $slot = Slot::find($parking->slot_id);
            $slot_type = SlotType::find($parking->slot_type_id);

            $pabbly_array = [
                'Slot' => $slot->name,
                'Slot Type' => $slot_type->name,
                'Name' => $parking->name,
                'Email' => $parking->email,
                'Mobile' => $parking->mobile,
                'Vehicle' => $parking->vehicle,
                'Vehicle Number' => $parking->vehicle_number,
                'Check In Time' => $parking->check_in,
                'Block of Slote' => $parking->block_of_slot,
            ];

            $action = 'New Parking';
            $module = 'ParkingManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
