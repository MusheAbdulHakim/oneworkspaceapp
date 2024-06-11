<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CMMS\Entities\Location;
use Modules\CMMS\Entities\Part;
use Modules\CMMS\Events\CreatePms;
use Modules\PabblyConnect\Entities\PabblySend;

class CreatePmsLis
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
    public function handle(CreatePms $event)
    {
        if (module_is_active('PabblyConnect')) {
            $pms = $event->pms;
            $request = $event->request;

            $location = Location::find($pms->location_id);
            $partNames = Part::whereIn('id', $request->parts)->pluck('name');
            $partNamesString = implode(', ', $partNames->toArray());

            $action = 'New Pms';
            $module = 'CMMS';

            $pabbly_array = array(
                "PMS Name"    => $pms['name'],
                "Location"    => $location->name,
                "Parts"       => $partNamesString,
                "Tags"        => $pms->tags,
                "Description" => $request->description,
            );

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}