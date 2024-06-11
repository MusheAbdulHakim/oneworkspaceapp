<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\AgricultureManagement\Events\CreateAgricultureEquipment;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateAgricultureEquipmentLis
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
    public function handle(CreateAgricultureEquipment $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $agriclutureequipment = $event->agriclutureequipment;

            $pabbly_array = [
                'Agriculture Equipment Title' => $agriclutureequipment->name,
                'Agriculture Equipment Price' => $agriclutureequipment->price,
                'Agriculture Equipment Quantity' => $agriclutureequipment->quantity,
            ];

            $action = 'New Agriculture Equipment';
            $module = 'AgricultureManagement';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
