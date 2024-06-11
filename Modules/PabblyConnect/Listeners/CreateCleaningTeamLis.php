<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CleaningManagement\Events\CreateCleaningTeam;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCleaningTeamLis
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
    public function handle(CreateCleaningTeam $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $cleaning_team = $event->cleaning_team;

            $user_id = explode(',', $cleaning_team->user_id);

            $member_data = User::whereIn('id', $user_id)->get();

            $memberArray = [];

            foreach ($member_data as $member) {
                $attributes = [
                    'Member Name' => $member->name,
                    'Member Email' => $member->email,
                    'Member Mobile Number' => $member->mobile_no
                ];
                $memberArray[] = $attributes;
            }


            $pabbly_array = [
                'Cleaning Team Name' => $cleaning_team->name,
                'Cleaning Team Members' => $memberArray,
                'Cleaning Team Status' => $cleaning_team->status == 1 ? 'Active' : 'Inactive',
            ];

            $action = 'New Cleaning Team';
            $module = 'CleaningManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
