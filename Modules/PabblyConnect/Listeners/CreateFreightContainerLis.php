<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\FreightManagementSystem\Events\CreateFreightContainer;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFreightContainerLis
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
    public function handle(CreateFreightContainer $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $container = $event->container;

            $pabbly_array = [
                'Container Code' => $request->code,
                'Container Number' => $request->container_number,
                'Container Status' => $container->status,
                'Container Name' => $container->name,
                'Container Size' => $container->size,
                'Container Size UOM' => $container->size_uom,
                'Container Volume Price' => $container->volume_price,
            ];

            $action = 'New Freight Container';
            $module = 'FreightManagementSystem';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
