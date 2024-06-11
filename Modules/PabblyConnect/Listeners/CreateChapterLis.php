<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\LMS\Entities\Course;
use Modules\LMS\Entities\Header;
use Modules\LMS\Events\CreateChapter;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateChapterLis
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
    public function handle(CreateChapter $event)
    {
        if (module_is_active('PabblyConnect')) {
            $chapter = $event->chapters;
            $request = $event->request;

            $course = Course::find($chapter->course_id);
            $header = Header::find($chapter->header_id);

            $action = 'New Chapter';
            $module = 'LMS';
            $pabbly_array = array(
                "Chapter Title"       => $chapter['name'],
                "Course Title"        => $course->title,
                "Chapter Description" => strip_tags($chapter['chapter_description']),
                "Header Title"        => $header->title,
                "Chapter Type"        => $chapter['type'],
                "Video URL"           => $chapter['video_url'],
            );

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}