<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MeetingHub\Events\CreateMeeingHubTask;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateMeeingHubTaskLis
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
    public function handle(CreateMeeingHubTask $event)
    {
        if (module_is_active('Webhook')) {
            $task = $event->task;

            $pabbly_array = [
                'Task Title' => $task->name,
                'Task Date' => $task->date,
                'Task Time' => $task->time,
                'Task Priority' => $task->priority,
                'Task Status' => MeetingHubMeeting::$statues[$task->status],
            ];

            $action = 'New Meeting Task';
            $module = 'MeetingHub';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
