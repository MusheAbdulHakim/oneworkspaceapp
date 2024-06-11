<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\InsuranceManagement\Entities\Insurance;
use Modules\InsuranceManagement\Entities\Policy;
use Modules\InsuranceManagement\Events\RejectinsuranceClaim;
use Modules\PabblyConnect\Entities\PabblySend;

class RejectinsuranceClaimLis
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
    public function handle(RejectinsuranceClaim $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $id = $event->id;
            $claim = $event->claim;

            $insurance = Insurance::find($id);
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
                'Insurance Reject Reason' => $claim->reject_reason,
                'Action Date' => $claim->action_date,
                'Insurance Claim Status' => 'Reject'
            ];

            $action = 'Reject Insurance Claim';
            $module = 'InsuranceManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
