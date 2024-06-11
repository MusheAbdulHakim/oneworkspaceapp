<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Appointment\Events\CreateAppointment;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateAppointmentLis
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
    public function handle(CreateAppointment $event)
    {
        if (module_is_active('PabblyConnect')) {
            $post = $event->post;
            $action = 'New Appointment';
            $module = 'Appointment';

            $pabbly_array = array(
                "Name"             => $post['name'],
                "Appointment Type" => $post['appointment_type'],
                "Week Day"         => $post['week_day']
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}