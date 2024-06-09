<?php

namespace Modules\Account\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Account\Entities\BillPayment;
use Modules\Account\Events\DestroyBill;
use Modules\Account\Entities\TransactionLines;

class BillDestroy
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
    public function handle(DestroyBill $event)
    {
        $bill = $event->bill;
        $bill_payments = BillPayment::where('bill_id',$bill->id)->get();
        foreach($bill_payments as $bill_payment)
        {
            TransactionLines::where('reference_id', $bill->id)->where('reference_sub_id', $bill_payment->id)->where('reference', 'Bill Payment')->delete();
        }
        TransactionLines::where('reference_id', $bill->id)->delete();
    }
}
