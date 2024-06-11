<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\School\Entities\Classroom;
use Modules\School\Events\CreateSubject;

class CreateSubjectLis
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
    public function handle(CreateSubject $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $subject = $event->subject;

            $class = Classroom::find($subject->class_id);
            $teacher = User::find($subject->teacher);

            $pabbly_array = [
                'Class' => $class->class_name,
                'Teacher Name' => $teacher->name,
                'Teacher Email' => $teacher->email,
                'Teacher Mobile Number' => $teacher->mobile_no,
                'Subject Name' => $subject->subject_name,
                'Subject Code' => $subject->subject_code
            ];

            $action = 'New Subject';
            $module = 'School';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
