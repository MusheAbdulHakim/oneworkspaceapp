<?php

namespace Modules\Account\Listeners;

use App\Events\DestroyInvoice;
use App\Models\InvoicePayment;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Account\Entities\TransactionLines;

class InvoiceDestroy
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
    public function handle(DestroyInvoice $event)
    {
        $invoice = $event->invoice;
        $invoice_payments = InvoicePayment::where('invoice_id',$invoice->id)->get();
        foreach($invoice_payments as $invoice_payment)
        {
            TransactionLines::where('reference_id', $invoice->id)->where('reference_sub_id', $invoice_payment->id)->where('reference', 'Invoice Payment')->delete();
        }
        TransactionLines::where('reference_id', $invoice->id)->delete();
    }
}
