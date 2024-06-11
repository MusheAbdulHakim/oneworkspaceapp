<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Holidayz\Events\CreateRoomFacility;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateRoomFacilityLis
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
    public function handle(CreateRoomFacility $event)
    {
        if (module_is_active('PabblyConnect')) {

            $request = $event->request;
            $facility = $event->facility;

            $childFacilitiesData = [];

            foreach ($request['child_facilities'] as $facilitys) {
                $subName = $facilitys['sub_name'];
                $subPrice = $facilitys['sub_price'];

                $childFacilitiesData[] = [
                    'sub_name' => $subName,
                    'sub_price' => $subPrice,
                ];
            }

            $pabbly_array = [
                'Facility Title' => $facility->name,
                'Short Description' => strip_tags($facility->short_description),
                'Child Facilities' => $childFacilitiesData
            ];

            $action = 'New Facilities';
            $module = 'Holidayz';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
