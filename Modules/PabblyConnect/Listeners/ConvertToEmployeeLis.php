<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Recruitment\Events\ConvertToEmployee;

class ConvertToEmployeeLis
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
    public function handle(ConvertToEmployee $event)
    {
        if (module_is_active('PabblyConnect')) {
            $employee = $event->employee;
            $action = 'Convert To Employee';
            $module = 'Recruitment';

            $pabbly_array = array(
                "name"        => $employee['name'],
                "dob"         => $employee['dob'],
                "gender"      => $employee['gender'],
                "phone"       => $employee['phone'],
                "address"     => $employee['address'],
                "email"       => $employee['email'],
                "company_doj" => $employee['company_doj'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}