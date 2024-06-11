<?php

namespace Modules\PabblyConnect\Listeners;

use App\Events\PaymentReminderInvoice;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;

class PaymentInvoiceReminderLis
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
    public function handle(PaymentReminderInvoice $event)
    {
        if(module_is_active('PabblyConnect')){
            $invoice = $event->invoice;
            $action = 'Invoice status updated';
            $module = 'general';

            $pabbly_array = array(
                "invoice"        => $invoice['invoice'],
                "issue_date"     => $invoice['issue_date'],
                "due_date"       => $invoice['due_date'],
                "send_date"      => $invoice['send_date'],
                "name"           => $invoice['name'],
                "invoice_module" => $invoice['invoice_module'],
                "Due Amount"     => $invoice['dueAmount'],
            );
            PabblySend::SendPabblyCall($module,$pabbly_array,$action);
        }
    }
}