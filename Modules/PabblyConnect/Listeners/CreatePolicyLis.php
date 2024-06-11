<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\InsuranceManagement\Events\CreatePolicy;
use Modules\PabblyConnect\Entities\PabblySend;

class CreatePolicyLis
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
    public function handle(CreatePolicy $event)
    {
        if (module_is_active('PabblyConnect')) {
            $policy = $event->policy;

            $pabbly_array = [
                'Policy Name' => $policy->name,
                'Policy Type' => $policy->type,
                'Duration' => $policy->duration,
                'Minimum Duration' => $policy->minimum_duration,
                'Maximum Duration' => $policy->maximum_duration,
                'Policy Amount' => $policy->amount,
                'Agent Commission' => $policy->agent_commission,
                'Commission Amount' => $policy->commission_amount,
            ];

            $action = 'New Policy';
            $module = 'InsuranceManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
