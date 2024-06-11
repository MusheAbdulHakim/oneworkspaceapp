<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\InsuranceManagement\Entities\Policy;
use Modules\InsuranceManagement\Entities\PolicyType;
use Modules\InsuranceManagement\Events\SendInsuraceMail;
use Modules\PabblyConnect\Entities\PabblySend;

class AcceptInsuraceLis
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
    public function handle(SendInsuraceMail $event)
    {
        if (module_is_active('PabblyConnect')) {
            $insurance = $event->insurance;
            $uArr = $event->uArr;

            $client = User::find($insurance->client_name);
            $agent = User::find($insurance->agent_name);
            $policy_type = PolicyType::find($insurance->policy_type);
            $policy = Policy::find($insurance->policy_name);

            $pabbly_array = [
                'Client Name' => $client->name,
                'Client Email' => $client->email,
                'Agent Name' => $agent->name,
                'Agent Email' => $agent->email,
                'Policy Name' => $policy->name,
                'Policy Type' => $policy_type->name,
                'Duration' => $insurance->duration,
                'Expiry Date' => $insurance->expiry_date,
            ];

            $action = 'New Insurance Accept';
            $module = 'InsuranceManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
