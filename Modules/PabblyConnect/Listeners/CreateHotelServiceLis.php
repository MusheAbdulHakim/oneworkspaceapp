<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Holidayz\Events\CreateHotelService;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateHotelServiceLis
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
    public function handle(CreateHotelService $event)
    {
        if (module_is_active('PabblyConnect')) {

            $request = $event->request;
            $service = $event->service;

            $childServiceData = [];

            foreach ($request['category-group'] as $group) {
                $subName = $group['sub_services'];

                $childServiceData[] = [
                    'sub_services' => $subName,
                ];
            }

            $pabbly_array = [
                'Service Title' => $service->name,
                'Sub Service' => $childServiceData,
            ];

            $action = 'New Hotel Services';
            $module = 'Holidayz';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
