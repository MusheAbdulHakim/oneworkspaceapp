<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\HospitalManagement\Events\CreateWard;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateWardLis
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
    public function handle(CreateWard $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $ward = $event->ward;

            $pabbly_array = [
                'Ward Title' => $ward->name
            ];

            $action = 'New Ward';
            $module = 'HospitalManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
