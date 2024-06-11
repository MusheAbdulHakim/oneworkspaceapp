<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MedicalLabManagement\Entities\LabTest;
use Modules\MedicalLabManagement\Entities\Patient;
use Modules\MedicalLabManagement\Events\CreateMedicalAppoinment;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateMedicalAppointmentLis
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
    public function handle(CreateMedicalAppoinment $event)
    {
        if (module_is_active('PabblyConnect')) {
            $labAppoinment = $event->labAppoinment;

            $patient = Patient::find($labAppoinment->patient_id);
            $lab = LabTest::find($labAppoinment->lab_id);

            $pabbly_array = [
                'Patient First Name' => $patient->first_name,
                'Patient Last Name' => $patient->last_name,
                'Patient Date of Birth' => $patient->dob,
                'Test Lab Title' => $lab->name,
                'Test Lab Cost' => $lab->cost,
                'Tests' => json_decode($lab->items),
                'Appointment Date' => $labAppoinment->date,
                'Appointment Time' => $labAppoinment->time,
            ];

            $action = 'New Medical Appointment';
            $module = 'MedicalLabManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
