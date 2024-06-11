<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Exam\Entities\ExamList;
use Modules\Exam\Events\CreateExamTimeTable;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateExamTimeTableLis
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
    public function handle(CreateExamTimeTable $event)
    {
        if (module_is_active('PabblyConnect')) {
            $timetable = $event->timetable;

            $exam = ExamList::find($timetable->exam_name);

            $pabbly_array = [
                'Exam Title' => $exam->examlist,
                'Subject' => json_decode($timetable->subject)
            ];

            $action = 'New Exam Time Table';
            $module = 'Exam';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
