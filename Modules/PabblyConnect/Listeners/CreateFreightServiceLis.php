<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\FreightManagementSystem\Events\CreateFreightService;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFreightServiceLis
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
    public function handle(CreateFreightService $event)
    {
        if (module_is_active('PabblyConnect')) {
            $service = $event->service;

            $pabbly_array = [
                'Service Title' => $service->name,
                'Service Cost Price' => $service->cost_price,
                'Service Sale Price' => $service->sale_price,
            ];

            $action = 'New Freight Service';
            $module = 'FreightManagementSystem';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
