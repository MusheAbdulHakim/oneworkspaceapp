<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Commission\Events\CreateCommissionPlan;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Zapier\Entities\SendZap;

class CreateCommissionPlanLis
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
    public function handle(CreateCommissionPlan $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $commissionPlan = $event->commissionPlan;

            $userIds = explode(',', $commissionPlan->user_id);

            $users = User::WhereIn('id', $userIds)->get();
            $user_name = $users->pluck('name')->toArray();
            $user_names = implode(', ', $user_name);

            $pabbly_array = array(
                "Name" => $commissionPlan->name,
                "Start Date" => $commissionPlan->start_date,
                "End Date" => $commissionPlan->end_date,
                "User Names" => $user_names
            );

            $action = 'New Commission Plan';
            $module = 'Commission';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
