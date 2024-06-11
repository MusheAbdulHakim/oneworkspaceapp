<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Newspaper\Events\CreateNewspaperAgent;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateNewspaperAgentLis
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
    public function handle(CreateNewspaperAgent $event)
    {
        if (module_is_active('PabblyConnect')) {
            $user = $event->user;
            $agentdetail = $event->agentdetail;
            $request = $event->request;

            $pabbly_array = [
                'Agenet Name' => $user->name,
                'Agent Email' => $user->email,
                'Agent Mobile Number' => $user->mobile_no,
                'Billing Name' => $agentdetail->billing_name,
                'Billing Phone' => $agentdetail->billing_phone,
                'Billing Address' => $agentdetail->billing_address,
                'Billing City' => $agentdetail->billing_city,
                'Billing State' => $agentdetail->billing_state,
                'Billing Country' => $agentdetail->billing_country,
                'Billing Zip' => $agentdetail->billing_zip,
                'Shipping Name' => $agentdetail->shipping_name,
                'Shipping Phone' => $agentdetail->shipping_phone,
                'Shipping Address' => $agentdetail->shipping_address,
                'Shipping City' => $agentdetail->shipping_city,
                'Shipping State' => $agentdetail->shipping_state,
                'Shipping Country' => $agentdetail->shipping_country,
                'Shipping Zip' => $agentdetail->shipping_zip,
            ];

            $action = 'New Agent';
            $module = 'Newspaper';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
