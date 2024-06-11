<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Taskly\Entities\Comment;
use Modules\Taskly\Entities\Task;
use Modules\Taskly\Events\CreateTaskComment;

class CreateTaskCommentLis
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
    public function handle(CreateTaskComment $event)
    {
        if (module_is_active('PabblyConnect')) {
            $comment = $event->comment;
            
            $task = Task::where('id', $comment->task_id)->first();
            
            $comments             = new Comment;
            $comments->comment    = $comment->comment;
            $comments->created_by = $comment->created_by;
            $comments->user_type  = $comment->user_type;
            $comments->task_title    = $task->title;
            
            $action = 'New Task Comment';
            $module = 'Taskly';
            
            $pabbly_array = array(
                "Task Title" => $task->title,
                "Comment"    => $comment['comment'],
                "user_type"  => $comment['user_type'],
                "User"       => \Auth::user()->name,
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}