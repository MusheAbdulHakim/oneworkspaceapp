<?php

namespace Modules\Account\Listeners;

use App\Events\UpdateInvoice;
use App\Models\InvoiceProduct;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Account\Entities\AccountUtility;

class InvoiceUpdate
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
    public function handle(UpdateInvoice $event)
    {
        $invoice = $event->invoice;
        if ($invoice->status != 0) {

            // for chart of accounts data save
            $invoice_products = InvoiceProduct::where('invoice_id', $invoice->id)->get();

            foreach ($invoice_products as $invoice_product) {
                $product = \Modules\ProductService\Entities\ProductService::find($invoice_product->product_id);
                $totalTaxPrice = 0;
                $taxes = \App\Models\Invoice::tax($invoice_product->tax);
                foreach ($taxes as $tax) {
                    $taxPrice = \App\Models\Invoice::taxRate($tax->rate, $invoice_product->price, $invoice_product->quantity, $invoice_product->discount);
                    $totalTaxPrice += $taxPrice;
                }
                $itemAmount = ($invoice_product->price * $invoice_product->quantity) - ($invoice_product->discount) + $totalTaxPrice;
                $data = [
                    'account_id' => isset($product->sale_chartaccount_id) ?? '',
                    'transaction_type' => 'Credit',
                    'transaction_amount' => $itemAmount,
                    'reference' => 'Invoice',
                    'reference_id' => $invoice->id,
                    'reference_sub_id' => isset($product->id) ?? '',
                    'date' => $invoice->issue_date,
                ];

                AccountUtility::addTransactionLines($data);

            }
        }
    }
}
