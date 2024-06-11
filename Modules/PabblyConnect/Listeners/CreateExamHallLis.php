<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Exam\Events\CreateExamHall;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateExamHallLis
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
    public function handle(CreateExamHall $event)
    {
        if (module_is_active('PabblyConnect')) {
            $examhall = $event->examhall;

            $pabbly_array = [
                'Exam Hall Title' => $examhall->hall_name,
                'Exam Hall Capacity' => $examhall->hall_capacity,
                'Description' => $examhall->description
            ];

            $action = 'New Exam Hall';
            $module = 'Exam';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
