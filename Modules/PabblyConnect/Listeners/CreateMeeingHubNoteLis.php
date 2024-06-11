<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MeetingHub\Events\CreateMeeingHubNote;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateMeeingHubNoteLis
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
    public function handle(CreateMeeingHubNote $event)
    {
        if (module_is_active('PabblyConnect')) {
            $notes = $event->notes;

            $user = User::find($notes->user_id);

            $pabbly_array = [
                'User Name' => $user->name,
                'User Email' => $user->email,
                'User Mobile Number' => $user->mobile_no,
                'Note' => $notes->note
            ];

            $action = 'New Meeting Note';
            $module = 'MeetingHub';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
