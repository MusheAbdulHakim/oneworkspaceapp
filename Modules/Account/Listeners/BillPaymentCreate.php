<?php

namespace Modules\Account\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Account\Entities\AccountUtility;
use Modules\Account\Entities\BankAccount;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\Events\CreatePaymentBill;

class BillPaymentCreate
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
    public function handle(CreatePaymentBill $event)
    {
        $request = $event->request;
        $bill = $event->bill;

        // for chart of accounts data save
        $account = BankAccount::find($request->account_id);
        $get_account = ChartOfAccount::find($account->chart_account_id);
        $data = [
            'account_id' => !empty($get_account)? $get_account->id : 0 ,
            'transaction_type' => 'Debit',
            'transaction_amount' => $request->amount,
            'reference' => 'Bill Payment',
            'reference_id' => $bill->id,
            'reference_sub_id' => $request->id,
            'date' => $request->date,
        ];
        AccountUtility::addTransactionLines($data);

    }
}
