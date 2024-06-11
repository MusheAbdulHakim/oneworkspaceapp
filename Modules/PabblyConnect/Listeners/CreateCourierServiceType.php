<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CourierManagement\Events\Courierservicetypecreate;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCourierServiceType
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
    public function handle(Courierservicetypecreate $event)
    {
        if (module_is_active('PabblyConnect')) {
            $serviceType = $event->serviceType;

            $pabbly_array = [
                'Courier Service Type' => $serviceType->service_type,
            ];

            $action = 'New Courier Service Type';
            $module = 'CourierManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
