<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Taskly\Entities\BugStage;
use Modules\Taskly\Entities\Project;
use Modules\Taskly\Events\CreateBug;

class CreateBugLis
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
    public function handle(CreateBug $event)
    {
        if (module_is_active('PabblyConnect')) {
            $bug = $event->bug;
            $project = Project::where('id', $bug->project_id)->first();
            $stage = BugStage::where('id', $bug->status)->first();
            $assign_user = User::where('id', $bug->assign_to)->first();

            $action = 'New Bug';
            $module = 'Taskly';
            $pabbly_array = array(
                "Project Name" => $project->name,
                "title"        => $bug['title'],
                "priority"     => $bug['priority'],
                "Stage"        => $stage->name,
                "Assign user"  => $assign_user->name,
                "description"  => $bug['description'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}