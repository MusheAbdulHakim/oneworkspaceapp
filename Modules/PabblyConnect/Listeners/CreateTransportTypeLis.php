<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\TourTravelManagement\Events\CreateTransportType;

class CreateTransportTypeLis
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
    public function handle(CreateTransportType $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $transport_type = $event->transport_type;

            $pabbly_array = [
                'Transport Type Title' => $transport_type->transport_type_name,
            ];

            $action = 'New Transport Type';
            $module = 'TourTravelManagement';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
