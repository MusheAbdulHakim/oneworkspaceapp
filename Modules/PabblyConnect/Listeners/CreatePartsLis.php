<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CMMS\Entities\Location;
use Modules\CMMS\Events\CreatePart;
use Modules\PabblyConnect\Entities\PabblySend;

class CreatePartsLis
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
    public function handle(CreatePart $event)
    {
        if (module_is_active('PabblyConnect')) {
            $parts = $event->parts;
            $request = $event->request;

            $location = Location::find($parts->location_id);
            $action = 'New Part';
            $module = 'CMMS';

            $pabbly_array = array(
                "Part Title"    => $parts->name,
                "Location"      => $location->name,
                "Part Category" => $parts->category,
                "Part Number"   => $parts->number,
                "Part Price"    => $parts->price,
                "Part Quantity" => $parts->quantity,
            );

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}