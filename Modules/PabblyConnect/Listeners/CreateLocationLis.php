<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CMMS\Events\CreateLocation;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateLocationLis
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
    public function handle(CreateLocation $event)
    {
        if (module_is_active('PabblyConnect')) {
            $location = $event->location;
            $request = $event->request;

            $action = 'New Location';
            $module = 'CMMS';

            $company = User::find($location->created_by);

            $pabbly_array = array(
                "Location" => $location['name'],
                "Address"  => $location['address'],
                "Name"     => $company['name'],
            );

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}