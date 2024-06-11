<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\GymManagement\Events\CreateMembershipPlan;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateMembershipPlanLis
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
    public function handle(CreateMembershipPlan $event)
    {
        if (module_is_active('PabblyConnect')) {
            $membershipplan = $event->membershipplan;

            $pabbly_array = [
                'Membership Plan Title' => $membershipplan->name,
                'Membership Fee' => $membershipplan->fee,
                'Membership Duration' => $membershipplan->duration
            ];

            $action = 'New Membership Plan';
            $module = 'GymManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
