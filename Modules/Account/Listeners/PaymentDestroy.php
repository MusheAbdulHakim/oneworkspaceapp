<?php

namespace Modules\Account\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Account\Entities\TransactionLines;
use Modules\Account\Events\DestroyPayment;

class PaymentDestroy
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
    public function handle(DestroyPayment $event)
    {
        $payment= $event->payment;
        TransactionLines::where('reference','Payment')->where('reference_id',$payment->id)->where('reference_sub_id',$payment->id)->delete();

    }
}
