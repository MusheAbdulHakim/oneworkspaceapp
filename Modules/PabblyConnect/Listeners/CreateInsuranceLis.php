<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\InsuranceManagement\Entities\Policy;
use Modules\InsuranceManagement\Entities\PolicyType;
use Modules\InsuranceManagement\Events\CreateInsurance;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateInsuranceLis
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
    public function handle(CreateInsurance $event)
    {
        if (module_is_active('PabblyConnect')) {
            $insurance = $event->insurance;

            $client = User::find($insurance->client_name);
            $agent = User::find($insurance->agent_name);
            $policy = Policy::find($insurance->policy_name);
            $policy_type = PolicyType::find($insurance->policy_type);

            $pabbly_array = [
                'Client Name' => $client->name,
                'Client Email' => $client->email,
                'Policy Type' => $policy_type->name,
                'Policy Name' => $policy->name,
                'Agent Name' => $agent->name,
                'Agent Email' => $agent->email,
                'Duration' => $insurance->duration
            ];

            $action = 'New Insurance';
            $module = 'InsuranceManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
