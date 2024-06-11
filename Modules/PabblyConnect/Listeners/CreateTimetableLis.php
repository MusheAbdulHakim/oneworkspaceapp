<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\School\Entities\Classroom;
use Modules\School\Entities\Subject;
use Modules\School\Events\CreateTimetable;

class CreateTimetableLis
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
    public function handle(CreateTimetable $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $timetable = $event->timetable;

            $class = Classroom::find($timetable->class_id);
            $subject_ids = explode(',', $timetable->subject_ids);
            $subjects = Subject::WhereIn('id', $subject_ids)->get();

            $subjectData = [];

            foreach ($subjects as $subject) {
                $subjectCode = $subject->subject_code;
                $subjectName = $subject->subject_name;

                $subjectData[] = [
                    'subject_code' => $subjectCode,
                    'subject_name' => $subjectName,
                ];
            }

            $timeTable = json_decode($timetable->all_time, true);
            $result = [];

            foreach ($timeTable as $day => $slots) {
                foreach ($slots as $slotNumber => $slot) {
                    $result[] = [
                        'day' => $day,
                        'first_time' => $slot['first_time'],
                        'last_time' => $slot['last_time']
                    ];
                }
            }

            $pabbly_array = [
                'Class' => $class->class_name,
                'Start Time' => $timetable->start_time,
                'End Time' => $timetable->end_time,
                'Subject' => $subjectData,
                'Time Table' => $result
            ];

            $action = 'New Time Table';
            $module = 'School';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
