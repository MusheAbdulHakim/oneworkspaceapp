<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Exam\Events\CreateExamGrade;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateExamGradeLis
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
    public function handle(CreateExamGrade $event)
    {
        if (module_is_active('PabblyConnect')) {
            $examGrade = $event->examGrade;

            $pabbly_array = [
                'Grade Name' => $examGrade->grade_name,
                'Grade Point' => $examGrade->grade_point,
                'Mark From' => $examGrade->mark_from,
                'Mark To' => $examGrade->mark_to,
                'Comment' => $examGrade->comment
            ];

            $action = 'New Exam Grade';
            $module = 'Exam';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
