<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\InnovationCenter\Entities\CreativityStage;
use Modules\InnovationCenter\Entities\CreativityStatus;
use Modules\InnovationCenter\Events\CreateCreativity;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCreativityLis
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
    public function handle(CreateCreativity $event)
    {
        if (module_is_active('PabblyConnect')) {
            $creativity = $event->creativity;

            $status = CreativityStatus::find($creativity->status);
            $stage = CreativityStage::find($creativity->stage);

            $users = explode(',', $creativity->user_id);

            $usersData = User::whereIn('id', $users)->get();

            $usersInfo = [];
            foreach ($usersData as $user) {
                $usersInfo[] = [
                    'Name' => $user->name,
                    'Email' => $user->email,
                ];
            }

            $pabbly_array = [
                'Creativity Title' => $creativity->creativity_name,
                'Creativity Status' => $status->name,
                'Creativity Stage' => $stage->name,
                'Type' => $creativity->type,
                'Visibility Type' => $creativity->visibility_type,
                'Description' => strip_tags($creativity->dsescription),
                'Organisational Effects' => strip_tags($creativity->organisational_effects),
                'Goal Description' => strip_tags($creativity->goal_description),
                'Notes' => strip_tags($creativity->notes),
                'Users' => $usersInfo,
            ];

            $action = 'New Creativity';
            $module = 'InnovationCenter';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
