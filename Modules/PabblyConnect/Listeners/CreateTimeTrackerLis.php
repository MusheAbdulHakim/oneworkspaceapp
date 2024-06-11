<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\TimeTracker\Events\CreateTimeTracker;

class CreateTimeTrackerLis
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
    public function handle(CreateTimeTracker $event)
    {
        if (module_is_active('PabblyConnect')) {
            $tracker = $event->track;

            $pabbly_array = [
                'Project Name' => $tracker->project_name,
                'Project Task' => $tracker->project_task,
                'Project Workspace' => $tracker->project_workspace,
                'Tracker Start Time' => $tracker->start_time
            ];

            $action = 'New Tracker';
            $module = 'TimeTracker';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
