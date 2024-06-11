<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\FreightManagementSystem\Entities\FreightContainer;
use Modules\FreightManagementSystem\Events\CreateFreightShippingInvoice;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFreightShippingInvoiceLis
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
    public function handle(CreateFreightShippingInvoice $event)
    {
        if (module_is_active('PabblyConnect')) {
            $shipping = $event->shipping;
            $invoice = $event->invoice;

            $container = FreightContainer::find($shipping->container);

            $pabbly_array = [
                'Customer Name' => $shipping->customer_name,
                'Customer Email' => $shipping->customer_email,
                'Direction' => $shipping->direction,
                'Transport' => $shipping->transport,
                'Loading Port' => $shipping->loading_port,
                'Discharge Port' => $shipping->discharge_port,
                'Vessel' => $shipping->vessel,
                'Date' => $shipping->date,
                'Barcode' => $shipping->barcode,
                'Tracking Number' => $shipping->tracking_no,
                'Container' => $container->name,
                'Quantity' => $shipping->quantity,
                'Volume' => $shipping->volume,
                'Invoice Date' => $invoice->invoice_date,
                'Due Date' => $invoice->due_date,
                'Amount' => $invoice->amount
            ];

            $action = 'New Freight Invoice';
            $module = 'FreightManagementSystem';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
