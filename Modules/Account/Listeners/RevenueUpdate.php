<?php

namespace Modules\Account\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Account\Entities\AccountUtility;
use Modules\Account\Entities\BankAccount;
use Modules\Account\Events\UpdateRevenue;

class RevenueUpdate
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
    public function handle(UpdateRevenue $event)
    {
        $revenue = $event->revenue;
        $account = BankAccount::find($revenue->account_id);
        $data = [
            'account_id' => $account->chart_account_id,
            'transaction_type' => 'Debit',
            'transaction_amount' =>  $revenue->amount,
            'reference' => 'Revenue',
            'reference_id' => $revenue->id,
            'reference_sub_id' => $revenue->id,
            'date' =>  $revenue->date,
        ];

        AccountUtility::addTransactionLines($data);
    }
}
