<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\WordpressWoocommerce\Events\CreateWoocommerceTax;

class CreateWoocommerceTaxLis
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
    public function handle(CreateWoocommerceTax $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $Tax = $event->Tax;

            $pabbly_array = [
                "Name" => $request['name'],
                "Type" => $Tax->type,
                "Country" => $request['country'],
                "State" => $request['state'],
                "City" => $request['city'],
                "Rate" => $request['rate']
            ];

            $action = 'New Tax';
            $module = 'WordpressWoocommerce';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
