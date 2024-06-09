<?php

namespace Modules\Account\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Account\Events\PaymentDestroyBill;
use Modules\Account\Entities\TransactionLines;

class BillPaymentDestroy
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
    public function handle(PaymentDestroyBill $event)
    {
        $bill  = $event->bill;
        $billPayment = $event->payment;

        TransactionLines::where('reference_id',$bill->id)->where('reference_sub_id',$billPayment->id)->where('reference', 'Bill Payment')->delete();
    }
}
