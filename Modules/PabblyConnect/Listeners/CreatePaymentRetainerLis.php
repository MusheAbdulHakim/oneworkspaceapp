<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Retainer\Events\CreatePaymentRetainer;

class CreatePaymentRetainerLis
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
    public function handle(CreatePaymentRetainer $event)
    {
        if (module_is_active('PabblyConnect')) {
            $retainer = $event->retainer;
            $action = 'New Retainer Payment';
            $module = 'Retainer';
            $pabbly_array = array(
                "issue_date"      => $retainer['issue_date'],
                "send_date"       => $retainer['send_date'],
                "Payment Date"    => $event->request->date,
                "discount_apply"  => $retainer['discount_apply'],
                "retainer_module" => $retainer['retainer_module'],
                "Amount"          => $event->request->amount,
                "reference"       => $event->request->reference,
                "description"     => $event->request->description,
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}