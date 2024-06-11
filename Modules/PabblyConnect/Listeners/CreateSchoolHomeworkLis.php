<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\School\Entities\Classroom;
use Modules\School\Entities\Subject;
use Modules\School\Events\CreateSchoolHomework;

class CreateSchoolHomeworkLis
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
    public function handle(CreateSchoolHomework $event)
    {
        if (module_is_active('PabblyConnect')) {
            $homework = $event->homework;

            $class = Classroom::find($homework->classroom);
            $subject = Subject::find($homework->subject);

            $pabbly_array = [
                'Homework Title' => $homework->title,
                'Class' => $class->class_name,
                'Subject' => $subject->subject_name,
                'Homework Submission Date' => $homework->submission_date,
                'Homework Content' => $homework->content,
            ];

            $action = 'New Homework';
            $module = 'School';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
