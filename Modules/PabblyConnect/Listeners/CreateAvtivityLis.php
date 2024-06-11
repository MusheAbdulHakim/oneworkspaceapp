<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ChildcareManagement\Events\CreateAvtivity;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateAvtivityLis
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
    public function handle(CreateAvtivity $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $activity = $event->activity;

            $pabbly_array = [
                'Activity Title' => $activity->name,
                'Activity Start Time' => $activity->start_time,
                'Activity End Time' => $activity->end_time,
                'Activity Description' => $activity->description
            ];

            $action = 'New Activity';
            $module = 'ChildcareManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
