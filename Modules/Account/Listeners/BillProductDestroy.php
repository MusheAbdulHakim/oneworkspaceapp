<?php

namespace Modules\Account\Listeners;

use App\Events\ProductDestroyBill;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Account\Entities\BillAccount;
use Modules\Account\Entities\TransactionLines;

class BillProductDestroy
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
    public function handle(ProductDestroyBill $event)
    {

        $billProduct = $event->billProduct;
        $bill_accounts =BillAccount::where('ref_id',$billProduct->bill_id)->get();
        foreach ($bill_accounts as $bill_account)
        {
            TransactionLines::where('reference_id', $bill_account->ref_id)->where('reference_sub_id', $bill_account->id)->where('reference', 'Bill Account')->delete();

        }
        TransactionLines::where('reference_id', $billProduct->bill_id)->where('reference_sub_id', $billProduct->product_id)->where('reference', 'Bill')->delete();
    }
}
