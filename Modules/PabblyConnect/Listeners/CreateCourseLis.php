<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\LMS\Entities\CourseCategory;
use Modules\LMS\Entities\CourseSubcategory;
use Modules\LMS\Events\CreateCourse;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCourseLis
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
    public function handle(CreateCourse $event)
    {
        if (module_is_active('PabblyConnect')) {
            $course = $event->course;
            $request = $event->request;

            $category = CourseCategory::find($course->category);
            $sub_category = CourseSubcategory::find($course->sub_category);

            $action = 'New Course';
            $module = 'LMS';
            $pabbly_array = array(
                "Course Title"        => $course['title'],
                "Course Requirements" => strip_tags($course['course_requirements']),
                "Course Description"  => strip_tags($course['course_description']),
                "Course Category"     => $category->name,
                "Course Sub Category" => !empty($sub_category) ? $sub_category->name : '',
                "Level"               => $course['level'],
                "Language"            => $course['lang'],
                "Duration"            => $course['duration'],
                "Has Certificate"     => $course['has_certificate'],
                "Course Type"         => $course['type'],
                "Course Price"        => $course['price'],
                "Discount"            => $course['discount'],
                "Is Preview"          => $course['is_preview'],
                "Featured Course"     => $course['featured_course'],
                "Course Status"       => $course['status'],
                "Meta Keywords"       => $course['meta_keywords'],
                "Meta Description"    => $course['meta_description'],
            );

            $status = PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}