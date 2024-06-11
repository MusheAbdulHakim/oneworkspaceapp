<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MedicalLabManagement\Entities\Patient;
use Modules\MedicalLabManagement\Events\CreatePatientCard;
use Modules\PabblyConnect\Entities\PabblySend;

class CreatePatientCardLis
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
    public function handle(CreatePatientCard $event)
    {
        if (module_is_active('PabblyConnect')) {
            $patientCard = $event->patientCard;

            $patient = Patient::find($patientCard->patient_id);

            $pabbly_array = [
                'Patient First Name' => $patient->first_name,
                'Patient Last Name' => $patient->last_name,
                'Patient Date of Birth' => $patient->dob,
                'Patient Gender' => $patient->gender == 0 ? 'Male' : 'Female',
                'Patient Contact' => $patient->contact,
                'Patient Address' => $patient->address,
                'Patient Blood Group' => $patient->blood_group,
                'Patient Email' => $patient->email,
                'Patient Insurance' => $patient->insurance,
                'Patient Card Issue Date' => $patientCard->issue_date,
                'Patient Card Expire Date' => $patientCard->expiry_date,
                'Patient Card Notes' => $patientCard->note
            ];

            $action = 'New Patient Card';
            $module = 'MedicalLabManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
