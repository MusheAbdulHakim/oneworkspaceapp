<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\HospitalManagement\Entities\Doctor;
use Modules\HospitalManagement\Entities\Specialization;
use Modules\HospitalManagement\Events\CreatePatient;
use Modules\PabblyConnect\Entities\PabblySend;

class CreatePatientLis
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
    public function handle(CreatePatient $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $patient = $event->patient;

            $doctor = Doctor::find($patient->doctor_id);
            $specialization = Specialization::find($doctor->specialization);

            $pabbly_array = [
                'Patient Name' => $patient->name,
                'Patient Email' => $patient->email,
                'Patient Date of Birth' => $patient->dob,
                'Patient Gender' => $patient->gender,
                'Patient Contact Number' => $patient->contact_no,
                'Patient Medical History' => $patient->medical_history,
                'Patient Address' => $patient->address,
                'Doctor Name' => $doctor->name,
                'Doctor Email' => $doctor->email,
                'Doctor Contact Number' => $doctor->contact_no,
                'Doctor Specialization' => $specialization->name
            ];

            $action = 'New Patient';
            $module = 'HospitalManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
