<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\AgricultureManagement\Entities\AgricultureDepartment;
use Modules\AgricultureManagement\Entities\AgricultureOffices;
use Modules\AgricultureManagement\Events\CreateAgricultureUser;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateAgricultureUserLis
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
    public function handle(CreateAgricultureUser $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $agricultureuser = $event->agricultureuser;

            $department = AgricultureDepartment::find($agricultureuser->department);
            $office = AgricultureOffices::find($agricultureuser->office);

            $pabbly_array = [
                'User Name' => $agricultureuser->name,
                'Email' => $agricultureuser->email,
                'Phone Number' => $agricultureuser->phone,
                'Department' => $department->name,
                'Office' => $office->name,
                'Total Area' => $agricultureuser->total_area,
                'Cultivate Areas' => $agricultureuser->cultivate_area,
            ];

            $action = 'New Agriculture User';
            $module = 'AgricultureManagement';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
