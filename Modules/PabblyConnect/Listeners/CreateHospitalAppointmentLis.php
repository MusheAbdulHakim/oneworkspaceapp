<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\HospitalManagement\Entities\Doctor;
use Modules\HospitalManagement\Entities\Patient;
use Modules\HospitalManagement\Events\CreateHospitalAppointment;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateHospitalAppointmentLis
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
    public function handle(CreateHospitalAppointment $event)
    {
        if (module_is_active('Zapier')) {
            $hospitalappointment = $event->hospitalappointment;

            $patient = Patient::find($hospitalappointment->patient_id);
            $doctor = Doctor::find($hospitalappointment->doctor_id);

            $pabbly_array = [
                'Patient Name' => $patient->name,
                'Patient Email' => $patient->email,
                'Patient Contact Number' => $patient->contact_no,
                'Doctor Name' => $doctor->name,
                'Doctor Email' => $doctor->email,
                'Doctor Contact Number' => $doctor->contact_no,
                'Appointment Date' => $hospitalappointment->date,
                'Appointment Start Time' => $hospitalappointment->start_time,
                'Appointment End Time' => $hospitalappointment->end_time,
            ];
            $action = 'New Hospital Appointment';
            $module = 'HospitalManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
