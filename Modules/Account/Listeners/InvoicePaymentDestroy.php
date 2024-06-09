<?php

namespace Modules\Account\Listeners;

use App\Events\PaymentDestroyInvoice;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Account\Entities\TransactionLines;

class InvoicePaymentDestroy
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
    public function handle(PaymentDestroyInvoice $event)
    {
        $invoice  = $event->invoice;
        $invoicePayment = $event->payment;

        TransactionLines::where('reference_id',$invoice->id)->where('reference_sub_id',$invoicePayment->id)->where('reference', 'Invoice Payment')->delete();
    }
}
