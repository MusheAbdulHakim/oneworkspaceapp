<?php

namespace Modules\Account\Listeners;

use App\Events\CreatePaymentInvoice;
use App\Models\InvoicePayment;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Account\Entities\AccountUtility;
use Modules\Account\Entities\BankAccount;
use Modules\Account\Entities\ChartOfAccount;

class InvoicePaymentCreate
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
     * @param object $event
     * @return void
     */
    public function handle(CreatePaymentInvoice $event)
    {
        $request = $event->request;
        $invoice = $event->invoice;

        // for chart of accounts data save
        $account = BankAccount::find($request->account_id);
        $get_account = ChartOfAccount::find($account->chart_account_id);
        if(!empty($get_account))
        {
            $data = [
                'account_id' => !empty($get_account)? $get_account->id : 0 ,
                'transaction_type' => 'Debit',
                'transaction_amount' => $request->amount,
                'reference' => 'Invoice Payment',
                'reference_id' => $invoice->id,
                'reference_sub_id' => $request->id,
                'date' => $request->date,
            ];
            AccountUtility::addTransactionLines($data);
        }

    }
}
