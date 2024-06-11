<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\FixEquipment\Events\CreateManufacturer;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateManufacturerLis
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
    public function handle(CreateManufacturer $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $manufacturer = $event->manufacturer;

            $pabbly_array = [
                'Manufacturer Title' => $manufacturer->title
            ];

            $action = 'New Manufacturer';
            $module = 'FixEquipment';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
