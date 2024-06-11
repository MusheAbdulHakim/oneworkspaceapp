<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\InsuranceManagement\Entities\Insurance;
use Modules\InsuranceManagement\Entities\Policy;
use Modules\InsuranceManagement\Events\AcceptinsuranceClaim;
use Modules\PabblyConnect\Entities\PabblySend;

class AcceptinsuranceClaimLis
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
    public function handle(AcceptinsuranceClaim $event)
    {
        if (module_is_active('PabblyConnect')) {
            $insurance_id = $event->id;
            $claim = $event->claim;

            $insurance = Insurance::find($insurance_id);
            $client = User::find($insurance->client_name);
            $agent = User::find($insurance->agent_name);
            $policy = Policy::find($insurance->policy_name);

            $pabbly_array = [
                'Policy Name' => $policy->name,
                'Policy Type' => $insurance->policy_type,
                'Client Name' => $client->name,
                'Client Email' => $client->email,
                'Agent Name' => $agent->name,
                'Agent Email' => $agent->email,
                'Insurance Claim Reason' => $claim->claim_reason,
                'Action Date' => $claim->action_date,
                'Amount' => $claim->amount,
                'Insurance Claim Status' => 'Accepted'
            ];

            $action = 'Accept Insurance Claim';
            $module = 'InsuranceManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
