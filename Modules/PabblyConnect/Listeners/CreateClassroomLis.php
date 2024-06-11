<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\School\Events\CreateClassroom;

class CreateClassroomLis
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
    public function handle(CreateClassroom $event)
    {
        if (module_is_active('PabblyConnect')) {
            $classroom = $event->classroom;

            $pabbly_array = [
                'Class Name' => $classroom->class_name,
                'Class Capacity' => $classroom->class_capacity,
            ];

            $action = 'New Classroom';
            $module = 'School';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
