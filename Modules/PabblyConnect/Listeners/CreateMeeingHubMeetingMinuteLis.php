<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MeetingHub\Events\CreateMeeingHubMeetingMinute;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateMeeingHubMeetingMinuteLis
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
    public function handle(CreateMeeingHubMeetingMinute $event)
    {
        if (module_is_active('PabblyConnect')) {
            $meeting_minute = $event->meeting_minute;

            $contact_user = User::find($meeting_minute->contact_user);
            $assign_user = User::find($meeting_minute->assign_user);

            $pabbly_array = [
                'Caller' => $meeting_minute->caller,
                'Contact User Name' => $contact_user->name,
                'Contact User Email' => $contact_user->email,
                'Contact Number' => $meeting_minute->phone_no,
                'Call Start Time' => $meeting_minute->call_start_time,
                'Call End Time' => $meeting_minute->call_end_time,
                'Log Type' => $meeting_minute->log_type,
                'Call Duration' => $meeting_minute->duration,
                'Important' => $meeting_minute->important,
                'Complete' => $meeting_minute->completed,
                'Priority' => $meeting_minute->priority,
                'Notes' => $meeting_minute->note,
                'Assign User Name' => $assign_user->name,
                'Assign User Email' => $assign_user->email
            ];

            $action = 'New Meeting Minute';
            $module = 'MeetingHub';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
