<?php

namespace Modules\Account\Listeners;

use Modules\Account\Entities\AccountUtility;
use Modules\Account\Entities\BankAccount;
use Modules\Account\Entities\Transfer;

class InvoiceBalanceTransfer
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
    public function handle($event)
    {

        if($event->type == 'invoice' && module_is_active('Account')){
            $invoice = $event->data;
            $payment = $event->payment;
            $account = BankAccount::where(['created_by'=>$invoice->created_by,'workspace'=>$invoice->workspace])->select('id')->first();

            $customerInvoices = ['taskly','account','cmms','cardealership','musicinstitute','rent'];

            if(in_array($invoice->invoice_module,$customerInvoices) ){
                AccountUtility::updateUserBalance('customer', $invoice->customer_id, $payment->amount, 'credit');
            }
            $account_id = $payment->account_id == 0 ? $account->id : $payment->account_id;

            Transfer::bankAccountBalance($account_id, $payment->amount, 'credit');
        }

    }
}
