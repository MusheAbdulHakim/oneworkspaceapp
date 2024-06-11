<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\School\Events\CreateAdmission;

class CreateAdmissionLis
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
    public function handle(CreateAdmission $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $admission = $event->admission;

            $pabbly_array = [
                'Date of Addmission' => $admission->date,
                'Student Name' => $admission->student_name,
                'Student Date of Birth' => $admission->date_of_birth,
                'Student Gender' => $admission->gender,
                'Student Blood Group' => $admission->blood_group,
                'Student Address' => $admission->address,
                'State' => $admission->state,
                'City' => $admission->city,
                'Zip Code' => $admission->zip_code,
                'Student Phone Number' => $admission->phone,
                'Student Email' => $admission->email,
                'Student Previous School' => $admission->previous_school,
                'Student Medical History' => $admission->medical_history,
                'Student Father Name' => $admission->father_name,
                'Father Number' => $admission->father_number,
                'Father Occuupation' => $admission->father_occupation,
                'Father Email' => $admission->father_email,
                'Father Address' => $admission->father_address,
                'Mother Name' => $admission->mother_name,
                'Mother Number' => $admission->mother_number,
                'Mother Occuupation' => $admission->mother_occupation,
                'Mother Email' => $admission->mother_email,
                'Mother Address' => $admission->mother_address,
            ];

            $action = 'New Addmissions';
            $module = 'School';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
