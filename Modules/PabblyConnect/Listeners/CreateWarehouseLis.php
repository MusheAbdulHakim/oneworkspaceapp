<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Pos\Events\CreateWarehouse;

class CreateWarehouseLis
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
    public function handle(CreateWarehouse $event)
    {
        if (module_is_active('PabblyConnect')) {
            $warehouse = $event->warehouse;
            $action = 'New Warehouse';
            $module = 'Pos';
            $pabbly_array = array(
                "name"     => $warehouse['name'],
                "address"  => $warehouse['address'],
                "city"     => $warehouse['city'],
                "city_zip" => $warehouse['city_zip'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}