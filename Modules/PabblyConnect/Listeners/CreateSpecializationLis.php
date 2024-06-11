<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\HospitalManagement\Events\CreateSpecialization;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateSpecializationLis
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
    public function handle(CreateSpecialization $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $specialization = $event->specialization;

            $pabbly_array = [
                'Specialization Title' => $specialization->name
            ];

            $action = 'New Specialization';
            $module = 'HospitalManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
