<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ChildcareManagement\Events\UpdateChidcare;
use Modules\PabblyConnect\Entities\PabblySend;

class UpdateChidcareLis
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
    public function handle(UpdateChidcare $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $childcare = $event->childcare;

            $pabbly_array = [
                'Childcare Title' => $childcare->name,
                'Grade Level' => $childcare->grade_level,
                'Contact Number' => $childcare->contact_number,
                'Start Time' => $childcare->start_time,
                'End Time' => $childcare->end_time,
                'Address' => $childcare->address,
                'Notes' => $childcare->notes
            ];

            $action = 'Update Child Care';
            $module = 'ChildcareManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
