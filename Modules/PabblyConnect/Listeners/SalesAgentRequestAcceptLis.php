<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\SalesAgent\Events\SalesAgentRequestAccept;

class SalesAgentRequestAcceptLis
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
    public function handle(SalesAgentRequestAccept $event)
    {
        if (module_is_active('PabblyConnect')) {
            $program = $event->program;
            $user_id = $event->user_id;

            $user = User::find($user_id);

            $pabbly_array = [
                'Program Title' => $program->name,
                'Program Start Date' => $program->from_date,
                'Program End Date' => $program->to_date,
                'Description' => $program->description,
                'Sales Agent Name' => $user->name,
                'Sales Agent Email' => $user->email,
                'Request Status' => 'Accepted',
            ];

            $action = 'Sales Agent Request Accept';
            $module = 'SalesAgent';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
