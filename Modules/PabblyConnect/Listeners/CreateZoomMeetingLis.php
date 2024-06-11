<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\ZoomMeeting\Events\CreateZoommeeting;

class CreateZoomMeetingLis
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
    public function handle(CreateZoommeeting $event)
    {
        $new = $event->new;

        if (module_is_active('PabblyConnect')) {
            $action = 'New Zoom Meeting';
            $module = 'ZoomMeeting';
            $pabbly_array = array(
                "title"      => $new['title'],
                "meeting_id" => $new['meeting_id'],
                "start_date" => $new['start_date'],
                "duration"   => $new['duration'],
                "start_url"  => $new['start_url'],
                "password"   => $new['password'],
                "join_url"   => $new['join_url'],
                "status"     => $new['status'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}