<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\LegalCaseManagement\Events\CreateCourt;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCourtLis
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
    public function handle(CreateCourt $event)
    {
        if (module_is_active('PabblyConnect')) {
            $court = $event->court;

            $pabbly_array = [
                'Court Name' => $court->name,
                'Court Type' => $court->type,
                'Court Location' => $court->location,
                'Court Address' => $court->address,
            ];

            $action = 'New Court';
            $module = 'LegalCaseManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
