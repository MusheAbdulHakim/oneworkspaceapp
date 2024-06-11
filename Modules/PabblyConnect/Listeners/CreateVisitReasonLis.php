<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\VisitorManagement\Events\CreateVisitReason;

class CreateVisitReasonLis
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
    public function handle(CreateVisitReason $event)
    {
        if (module_is_active('PabblyConnect')) {
            $visitorReason = $event->visitorReason;

            $pabbly_array = [
                'Visior Reason' => $visitorReason->reason,
            ];

            $action = 'New Visit Reason';
            $module = 'VisitorManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
