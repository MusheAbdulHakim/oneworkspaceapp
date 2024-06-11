<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Lead\Entities\Lead;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Sales\Events\CreateMeeting;

class CreateMeetingLis
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
    public function handle(CreateMeeting $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $meeting = $event->meeting;
            $user = User::where('id', $request->user)->first();
            $attendees_user = User::where('id', $request->attendees_user)->first();
            $attendees_contact = \Modules\Sales\Entities\Contact::where('id', $request->attendees_contact)->first();
            $attendees_lead =  Lead::where('id', $request->attendees_lead)->first();

            $meeting->user_name             = !empty($user) ? $user->name : '';
            // $meeting->status              = \Modules\Sales\Entities\Meeting::$status[$meeting->status];
            $meeting->parent_name           = $meeting->parent_name;
            $meeting->attendees_user      = !empty($attendees_user) ? $attendees_user->name : '';
            $meeting->attendees_contact   = !empty($attendees_contact) ? $attendees_contact->name : '';
            $meeting->attendees_lead      = !empty($attendees_lead) ? $attendees_lead->name : '';
            $action = 'New Meeting';
            $module = 'Sales';

            $pabbly_array = array(
                "Name"              => $meeting['name'],
                "User Name"         => $meeting->user_id,
                "Status"            => $meeting->status,
                "Start Date"        => $meeting['start_date'],
                "End Date"          => $meeting['end_date'],
                "Parent"            => $meeting['parent'],
                "Description"       => $meeting['description'],
                "Attendees User"    => $meeting->attendees_user,
                "Attendees Contact" => $meeting->attendees_contact,
                "Attendees Lead"    => $meeting->attendees_lead,
            );

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}