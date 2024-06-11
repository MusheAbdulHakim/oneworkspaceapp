<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\HospitalManagement\Events\CreateBedType;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateBedTypeLis
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
    public function handle(CreateBedType $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $bedtype = $event->bedtype;

            $pabbly_array = [
                'Bad Type Title' => $bedtype->name
            ];

            $action = 'New Bed Type';
            $module = 'HospitalManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
