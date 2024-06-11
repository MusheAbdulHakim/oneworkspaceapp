<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\InsuranceManagement\Entities\Policy;
use Modules\InsuranceManagement\Events\CreateinsuranceInvoice;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Zapier\Entities\SendZap;

class CreateinsuranceInvoiceLis
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
    public function handle(CreateinsuranceInvoice $event)
    {
        if (module_is_active('PabblyConnect')) {
            $invoice = $event->invoice;

            $policy = Policy::find($invoice->policy_id);
            $client = User::find($invoice->client_id);

            $pabbly_array = [
                'Policy Name' => $policy->name,
                'Policy Type' => $policy->type,
                'Policy Duration' => $policy->duration,
                'Policy Amount' => $policy->amount,
                'Client Name' => $client->name,
                'Client Email' => $client->email,
                'Due Date' => $invoice->due_date,
            ];

            $action = 'New Insurance Invoice';
            $module = 'InsuranceManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
