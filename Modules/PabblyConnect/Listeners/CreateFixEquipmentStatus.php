<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\FixEquipment\Events\CreateStatus;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFixEquipmentStatus
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
    public function handle(CreateStatus $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $status = $event->status;

            $pabbly_array = [
                'Status Title' => $status->title,
            ];

            $action = 'New Status';
            $module = 'FixEquipment';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
