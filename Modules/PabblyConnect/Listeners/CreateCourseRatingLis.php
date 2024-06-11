<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\LMS\Entities\Course;
use Modules\LMS\Entities\Student;
use Modules\LMS\Events\CreateRatting;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCourseRatingLis
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
    public function handle(CreateRatting $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $rating = $event->ratting;

            $student = Student::find($rating->student_id);
            $tutor = User::find($rating->tutor_id);
            $course = Course::find($rating->course_id);

            $action = 'New Ratting';
            $module = 'LMS';
            $pabbly_array = array(
                "Rating Title"         => $rating['title'],
                "Rating Name"          => $rating['name'],
                "Course Title"         => $course->title,
                "Student Name"         => $student->name,
                "Student Email"        => $student->email,
                "Student Phone Number" => $student->phone_number,
                "Tutor Name"           => $tutor->name,
                "Rating"               => $rating['ratting'],
                "Description"          => $rating['description'],
            );

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}