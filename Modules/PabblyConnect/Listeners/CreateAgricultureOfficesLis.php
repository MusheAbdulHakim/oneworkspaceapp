<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\AgricultureManagement\Entities\AgricultureDepartment;
use Modules\AgricultureManagement\Events\CreateAgricultureOffices;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateAgricultureOfficesLis
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
    public function handle(CreateAgricultureOffices $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $agricultureoffice = $event->agricultureoffice;

            $department = AgricultureDepartment::find($agricultureoffice->department);

            $pabbly_array = [
                'Agriculture Office Title' => $agricultureoffice->name,
                'Agriculture Office Department' => $department->name,
                'Agriculture Office Description' => $department->description,
            ];

            $action = 'New Agriculture Office';
            $module = 'AgricultureManagement';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
