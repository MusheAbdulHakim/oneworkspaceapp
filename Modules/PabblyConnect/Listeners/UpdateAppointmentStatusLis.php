<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Appointment\Entities\Appointment;
use Modules\Appointment\Events\AppointmentStatus;
use Modules\PabblyConnect\Entities\PabblySend;

class UpdateAppointmentStatusLis
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
    public function handle(AppointmentStatus $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $schedule = $event->schedule;

            $appointment = Appointment::find($schedule->appointment_id);

            $action = 'Appointment Status Update';
            $module = 'Appointment';
            
            $pabbly_array = array(
                "Appointment Title"       => $appointment->name,
                "Appointment Type"        => $appointment->appointment_type,
                "Appointment Day"         => $appointment->week_day,
                "Applicant Title"         => $schedule['name'],
                "Applicant Email"         => $schedule['email'],
                "Applicant Modile Number" => $schedule->phone,
                "Appointment Date"        => $schedule->date,
                "Starting Time"           => $schedule->start_time,
                "End Time"                => $schedule->end_time,

            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}