<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Exam\Entities\ExamHall;
use Modules\Exam\Entities\ExamList;
use Modules\Exam\Events\CreateExamHallReceipt;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateExamHallReceiptLis
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
    public function handle(CreateExamHallReceipt $event)
    {
        if (module_is_active('PabblyConnect')) {
            $examhallreceipt = $event->examhallreceipt;

            $exam = ExamList::find($examhallreceipt->exam_name);
            $hall = ExamHall::find($examhallreceipt->exam_hall);

            $pabbly_array = [
                'Exam' => $exam->examlist,
                'Exam Hall' => $hall->hall_name,
                'Students' => json_decode($examhallreceipt->student_name, true),
            ];

            $action = 'New Exam Hall Receipt';
            $module = 'Exam';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
