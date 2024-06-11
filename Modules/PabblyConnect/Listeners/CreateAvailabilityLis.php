<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Hrm\Entities\Employee;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Rotas\Events\CreateAvailability;

class CreateAvailabilityLis
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
    public function handle(CreateAvailability $event)
    {
        $availability = $event->availability;
        if (module_is_active('PabblyConnect')) {
            $user = Employee::where('id', $availability->employee_id)->first();
            
            $action = 'New Availabilitys';
            $module = 'Rotas';
            $pabbly_array = array(
                "User Name"  => $user->name,
                "name"       => $availability['name'],
                "start_date" => $availability['start_date'],
                "end_date"   => $availability['end_date'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}