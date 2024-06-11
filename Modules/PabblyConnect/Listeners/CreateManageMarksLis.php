<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Exam\Entities\ExamGrade;
use Modules\Exam\Entities\ExamList;
use Modules\Exam\Events\CreateManageMarks;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\School\Entities\Classroom;
use Modules\School\Entities\Subject;

class CreateManageMarksLis
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
    public function handle(CreateManageMarks $event)
    {
        if (module_is_active('PabblyConnect')) {
            $manageMark = $event->manageMark;

            $grade = ExamGrade::find($manageMark->grade);
            $class = Classroom::find($manageMark->class);
            $exam = ExamList::find($manageMark->exam);
            $subject = Subject::find($manageMark->subject);

            $pabbly_array = [
                'Grade' => $grade->grade_name,
                'Class' => $class->class_name,
                'Exam' => $exam->examlist,
                'Subject' => $subject->subject_name,
                'Student Marks' => json_decode($manageMark->student_marks)
            ];

            $action = 'New Mange Marks';
            $module = 'Exam';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
