<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ChildcareManagement\Entities\Child;
use Modules\ChildcareManagement\Events\CreateChildAttendance;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateChildAttendanceLis
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
    public function handle(CreateChildAttendance $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $childAttendance = $event->childAttendance;

            $child = Child::find($childAttendance->child_id);

            $pabbly_array = [
                'Child First Name' => $child->first_name,
                'Child Last Name' => $child->last_name,
                'Date' => $childAttendance->date,
                'Clock In' => $childAttendance->clock_in,
                'Clock Out' => $childAttendance->clock_out,
                'Status' => $childAttendance->status,
            ];

            $action = 'New Child Attendance';
            $module = 'ChildcareManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
