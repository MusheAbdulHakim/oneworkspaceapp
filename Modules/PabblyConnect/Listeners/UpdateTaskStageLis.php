<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Taskly\Entities\Stage;
use Modules\Taskly\Entities\Task;
use Modules\Taskly\Events\UpdateTaskStage;

class UpdateTaskStageLis
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
    public function handle(UpdateTaskStage $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $task = $event->task;
            
            $new_status   = Stage::where('id', $request->new_status)->first();
            
            $task_status          = new Task;
            $task_status->task_title = $task->title;
            $task_status->status  = $new_status->name;
            
            $action = 'Task Stage Update';
            $module = 'Taskly';
            
            $pabbly_array = array(
                "Title"       => $task['title'],
                "New Status"  => $new_status->name,
                "priority"    => $task['priority'],
                "description" => $task['description'],
                "start_date"  => $task['start_date'],
                "due_date"    => $task['due_date'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}