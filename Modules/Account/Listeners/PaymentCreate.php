<?php

namespace Modules\Account\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Account\Entities\AccountUtility;
use Modules\Account\Entities\BankAccount;
use Modules\Account\Events\CreatePayment;

class PaymentCreate
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
    public function handle(CreatePayment $event)
    {

        $payment= $event->payment;
        $account = BankAccount::find($payment->account_id);
        $data = [
            'account_id' => $account->chart_account_id,
            'transaction_type' => 'Debit',
            'transaction_amount' =>  $payment->amount,
            'reference' => 'Payment',
            'reference_id' => $payment->id,
            'reference_sub_id' => $payment->id,
            'date' =>  $payment->date,
        ];
        AccountUtility::addTransactionLines($data);
    }
}
