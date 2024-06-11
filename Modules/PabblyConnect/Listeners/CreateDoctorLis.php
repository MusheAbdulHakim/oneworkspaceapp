<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\HospitalManagement\Entities\Specialization;
use Modules\HospitalManagement\Events\CreateDoctor;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateDoctorLis
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
    public function handle(CreateDoctor $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $doctor = $event->doctor;

            $specialization = Specialization::find($doctor->specialization);

            $pabbly_array = [
                'Doctor Name' => $doctor->name,
                'Doctor Specialization' => $specialization->name,
                'Doctor Contact Number' => $doctor->contact_no,
                'Doctor Email' => $doctor->email,
                'Doctor License Number' => $doctor->license_number,
                'Gender' => $doctor->gender,
                'Doctor Years of Experience' => $doctor->years_of_experience,
                'Doctor Consultation Fee' => $doctor->consultation_fee,
            ];

            $action = 'New Doctor';
            $module = 'HospitalManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
