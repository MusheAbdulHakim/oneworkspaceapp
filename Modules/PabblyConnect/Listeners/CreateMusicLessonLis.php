<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MusicInstitute\Entities\MusicClass;
use Modules\MusicInstitute\Events\CreateMusicLesson;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateMusicLessonLis
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
    public function handle(CreateMusicLesson $event)
    {
        if (module_is_active('PabblyConnect')) {
            $lesson = $event->lesson;

            $class = MusicClass::find($lesson->class_id);

            $studentId = explode(',', $lesson->student_id);

            $usersData = User::whereIn('id', $studentId)->get();

            $studentInfo = [];
            foreach ($usersData as $user) {
                $studentInfo[] = [
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            }

            $pabbly_array = [
                'Class Name' => $class->name,
                'Class Start Date Time' => $class->start_date_time,
                'Class End Date Time' => $class->end_date_time,
                'Students' => $studentInfo
            ];

            $action = 'New Music Lesson';
            $module = 'MusicInstitute';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
