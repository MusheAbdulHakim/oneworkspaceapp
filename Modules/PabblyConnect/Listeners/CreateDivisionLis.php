<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\LegalCaseManagement\Entities\HighCourt;
use Modules\LegalCaseManagement\Events\CreateDivision;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateDivisionLis
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
    public function handle(CreateDivision $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $division = $event->division;

            $high_court = HighCourt::find($division->highcourt_id);

            $pabbly_array = [
                'Division Name' => $division->name,
                'High Court Title' => $high_court->name,
            ];

            $action = 'New High Court';
            $module = 'LegalCaseManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
