<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\LegalCaseManagement\Entities\Court;
use Modules\LegalCaseManagement\Events\CreateHighCourt;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateHighCourtLis
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
    public function handle(CreateHighCourt $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $highCourt = $event->highCourt;

            $court = Court::find($highCourt->court_id);

            $pabbly_array = [
                'High Court Title' => $highCourt->name,
                'Court Name' => $court->name
            ];

            $action = 'New High Court';
            $module = 'LegalCaseManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
