<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MusicInstitute\Events\CreateMusicTeacher;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateMusicTeacherLis
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
    public function handle(CreateMusicTeacher $event)
    {
        if (module_is_active('PabblyConnect')) {
            $teacher = $event->teacher;

            $pabbly_array = [
                'Teacher Name' => $teacher->name,
                'Teacher Email' => $teacher->email,
                'Date of Birth' => $teacher->dob,
                'Teacher Mobile Number' => $teacher->mobile_no,
                'Gender' => $teacher->gender,
                'Expertise' => $teacher->expertise,
                'Certification Detail' => $teacher->certification_detail
            ];

            $action = 'New Music Teacher';
            $module = 'MusicInstitute';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
