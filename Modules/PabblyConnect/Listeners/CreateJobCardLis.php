<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\GarageManagement\Entities\Service;
use Modules\GarageManagement\Events\CreateJobCard;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateJobCardLis
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
    public function handle(CreateJobCard $event)
    {
        if (module_is_active('PabblyConnect')) {
            $jobcard = $event->jobcard;

            $service = Service::find($jobcard->service_id);

            $pabbly_array = [
                'Service Title' => $service->title,
                'Service Date' => $service->service_date,
                'Status' => $jobcard->status,
                'Service Items' => json_decode($jobcard->cost),
            ];

            $action = 'New Job Card';
            $module = 'GarageManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
