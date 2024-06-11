<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Taskly\Events\CreateMilestone;

class CreateMilestoneLis
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
    public function handle(CreateMilestone $event)
    {
        if (module_is_active('PabblyConnect')) {
            $milestone = $event->milestone;
            $project = \Modules\Taskly\Entities\Project::where('id', $milestone->project_id)->first();

            $action = 'New Milestone';
            $module = 'Taskly';
            $pabbly_array = array(
                "Project Name"    => $project->name,
                "Milestone title" => $milestone['title'],
                "Status"          => $milestone['status'],
                "Cost"            => $milestone['cost'],
                "Summary"         => $milestone['summary'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}