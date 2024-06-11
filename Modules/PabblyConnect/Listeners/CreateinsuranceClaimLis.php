<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\InsuranceManagement\Entities\Insurance;
use Modules\InsuranceManagement\Entities\Policy;
use Modules\InsuranceManagement\Events\CreateinsuranceClaim;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateinsuranceClaimLis
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
    public function handle(CreateinsuranceClaim $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $claim = $event->claim;

            $insurance_data = json_decode($request->insurance_data);

            $client = User::find($insurance_data->client_name);
            $agent = User::find($insurance_data->agent_name);
            $policy = Policy::find($insurance_data->policy_name);
            $insurance = Insurance::find($insurance_data->id);

            $pabbly_array = [
                'Policy Title' => $policy->name,
                'Policy Type' => $insurance_data->policy_type,
                'Client Name' => $client->name,
                'Client Email' => $client->email,
                'Agent Name' => $agent->name,
                'Agent Email' => $agent->email,
                'Duration' => $insurance_data->duration,
                'Insurance Claim Reason' => $claim->claim_reason,
            ];

            $action = 'New Insurance Claim';
            $module = 'InsuranceManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
