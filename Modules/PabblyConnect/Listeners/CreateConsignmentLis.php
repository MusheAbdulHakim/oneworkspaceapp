<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ConsignmentManagement\Events\CreateConsignment;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateConsignmentLis
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
    public function handle(CreateConsignment $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $consignment = $event->consignment;

            $items = $request->input('items', []);

            $pabbly_array = [
                'Consignment Title' => $consignment->title,
                'Consignment Commission' => $consignment->commission,
                'Consignment Date' => $consignment->date,
                'Consignment Item' => $items,
                'Consignment Sub Total' => $consignment->subtotal,
                'Consignment Total Amount' => $consignment->totalamount,
            ];

            $action = 'New Consignment';
            $module = 'ConsignmentManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
