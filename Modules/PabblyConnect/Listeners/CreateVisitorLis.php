<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\VisitorManagement\Entities\VisitReason;
use Modules\VisitorManagement\Events\CreateVisitor;

class CreateVisitorLis
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
    public function handle(CreateVisitor $event)
    {
        if (module_is_active('Zapier')) {
            $request = $event->request;
            $visitor = $event->visitor;

            $visit_resion = VisitReason::find($request->visit_reason);

            $pabbly_array = [
                'First Name' => $visitor->first_name,
                'Last Name' => $visitor->last_name,
                'Email' => $visitor->email,
                'Phone' => $visitor->phone,
                'Visitor Reason' => $visit_resion->reason,
                'Check In' => $request->check_in,
            ];

            $action = 'New Visitor';
            $module = 'VisitorManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
