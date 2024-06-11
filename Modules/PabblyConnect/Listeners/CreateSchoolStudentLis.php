<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\School\Events\CreateSchoolStudent;

class CreateSchoolStudentLis
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
    public function handle(CreateSchoolStudent $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $student = $event->student;

            $pabbly_array = [
                'Student Name' => $student->name,
                'Student Date of Birth' => $student->std_date_of_birth,
                'Student Gender' => $student->student_gender,
                'Student Roll Number' => $student->roll_number,
                'Student Address' => $student->std_address,
                'Student State' => $student->std_state,
                'Student City' => $student->std_city,
                'Student Zip Code' => $student->std_zip_code,
                'Student Contact' => $student->contact,
                'Student Email' => $student->email,
            ];

            $action = 'New Students';
            $module = 'School';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
