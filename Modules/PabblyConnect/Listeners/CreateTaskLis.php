<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Taskly\Entities\Milestone;
use Modules\Taskly\Entities\Project;
use Modules\Taskly\Entities\Stage;
use Modules\Taskly\Events\CreateTask;

class CreateTaskLis
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
    public function handle(CreateTask $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $task    = $event->task;
            
            $project            = Project::where('id', $task->project_id)->first();
            $stage              = Stage::where('id', $task->status)->first();
            $assign_user        = User::whereIn('id', $request->assign_to)->get()->pluck('name')->toArray();
            $milestone          = Milestone::where('id', $request->milestone_id)->first();
            $task->status       = $stage->name;
            $task->project_name   = $project->name;
            $task->milestone_name = !empty($milestone) ? $milestone->title : '';
            $task->assign_to    = (count($assign_user) > 0) ? implode(',', $assign_user) : 'Not Found';
            
            $action = 'New Task';
            $module = 'Taskly';
            
            $zap_array = array(
                "Project Name" => $project->name,
                "Title"        => $task['title'],
                "Status"       => $stage->name,
                "Priority"     => $task['priority'],
                "Start Date"   => $task['start_date'],
                "Due Date"     => $task['due_date'],
                "Description"  => $task['description'],
                "Assign Users" => $assign_user,
            );
            PabblySend::SendPabblyCall($module, $zap_array, $action);
        }
    }
}