<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MedicalLabManagement\Events\CreateLabPatient;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateLabPatientLis
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
    public function handle(CreateLabPatient $event)
    {
        if (module_is_active('PabblyConnect')) {
            $patient = $event->patient;

            $pabbly_array = [
                'First Name' => $patient->first_name,
                'Last Name' => $patient->last_name,
                'Date Of Birth' => $patient->dob,
                'Patient Gender' => $patient->gender == 0 ? 'Male' : 'Female',
                'Patient Contact Number' => $patient->contact,
                'Patient Address' => $patient->address,
                'Patient Blood Group' => $patient->blood_group,
                'Patient Email' => $patient->email,
                'Insurance' => $patient->insurance
            ];

            $action = 'New Lab Patient';
            $module = 'MedicalLabManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
