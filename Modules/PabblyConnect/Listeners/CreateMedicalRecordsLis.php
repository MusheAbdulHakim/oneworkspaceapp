<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\HospitalManagement\Entities\Doctor;
use Modules\HospitalManagement\Entities\Patient;
use Modules\HospitalManagement\Events\CreateMedicalRecords;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateMedicalRecordsLis
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
    public function handle(CreateMedicalRecords $event)
    {
        if (module_is_active('PabblyConnect')) {
            $medicalrecord = $event->medicalrecord;

            $patient = Patient::find($medicalrecord->patient_id);
            $doctor = Doctor::find($medicalrecord->doctor_id);

            $pabbly_array = [
                'Patient Name' => $patient->name,
                'Patient Email' => $patient->email,
                'Patient Contact Number' => $patient->contact_no,
                'Doctor Name' => $doctor->name,
                'Doctor Email' => $doctor->email,
                'Doctor Contact Number' => $doctor->contact_no,
                'Date' => $medicalrecord->date,
                'Symptoms' => $medicalrecord->symptoms,
                'Follow Up Instructions' => $medicalrecord->follow_up_instructions,
                'Diagnosis' => $medicalrecord->diagnosis,
                'Prescription' => $medicalrecord->prescription,
                'Test Results' => $medicalrecord->testresults,
            ];

            $action = 'New Medical Records';
            $module = 'HospitalManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
