<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\AgricultureManagement\Events\CreateAgricultureDepartment;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateAgricultureDepartmentLis
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
    public function handle(CreateAgricultureDepartment $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $agriculturedepartment = $event->agriculturedepartment;

            $pabbly_array = [
                'Agriculture Department Title' => $agriculturedepartment->name,
                'Agriculture Department Description' => $agriculturedepartment->description,
            ];

            $action = 'New Agriculture Department';
            $module = 'AgricultureManagement';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
