<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\VCard\Entities\Business;
use Modules\VCard\Events\CreateAppointment;

class CreateVCardAppointmentLis
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
            $request = $event->request;
            $appointment = $event->appointment;

            $business = Business::find($appointment->business_id);

            $action = 'New Appointment';
            $module = 'VCard';
            
            $pabbly_array = array(
                "Business Title"   => $business->title,
                "Appointee Name"   => $appointment['name'],
                "Appointee Email"  => $appointment['email'],
                "Appointee Phone"  => $appointment['phone'],
                "Appointment Date" => $appointment['date'],
                "Appointee Time"   => $appointment['time'],
            );
        }

        PabblySend::SendPabblyCall($module, $pabbly_array, $action);
    }
}