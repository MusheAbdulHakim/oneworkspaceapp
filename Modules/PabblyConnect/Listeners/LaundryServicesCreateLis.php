<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\LaundryManagement\Events\LaundryServicesCreate;
use Modules\PabblyConnect\Entities\PabblySend;

class LaundryServicesCreateLis
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
    public function handle(LaundryServicesCreate $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $service = $event->service;

            $pabbly_array = [
                'Service Title' => $service->name,
                'Service Cost' => $service->cost,
            ];

            $action = 'New Laundry Service';
            $module = 'LaundryManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
