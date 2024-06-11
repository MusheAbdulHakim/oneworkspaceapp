<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Exam\Entities\ExamGrade;
use Modules\Exam\Events\CreateExamList;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\School\Entities\Classroom;

class CreateExamListLis
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
    public function handle(CreateExamList $event)
    {
        if (module_is_active('PabblyConnect')) {
            $examlist = $event->examlist;

            $grade = ExamGrade::find($examlist->grade);
            $class = Classroom::find($examlist->class);

            $pabbly_array = [
                'Exam Title' => $examlist->examlist,
                'Exam Grade' => $grade->grade_name,
                'Class Name' => $class->class_name,
                'Passing Marks' => $examlist->passing_marks,
                'Total Marks' => $examlist->total_marks,
                'Start Date' => $examlist->start_date,
                'End Date' => $examlist->end_date,
                'Description' => $examlist->description,
            ];

            $action = 'New Exam List';
            $module = 'Exam';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
