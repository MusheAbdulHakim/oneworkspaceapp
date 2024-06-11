<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Account\Entities\BankAccount;
use Modules\Account\Entities\Payment;
use Modules\Account\Entities\Vender;
use Modules\Account\Events\CreatePayment;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\ProductService\Entities\Category;

class CreatePaymentLis
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
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $payments = $event->payment;
            $payments                = new Payment();
            $payments->date           = $request->date;
            $payments->amount         = $request->amount;
            $payments->reference      = $request->reference;
            $payments->description    = $request->description;
            
            $account = BankAccount::where('id', $request->account_id)->first();
            if ($account) {
                $payments->account_bank_name = $account->bank_name;
            }
            
            $customer = Vender::find($request->vendor_id);
            if ($customer) {
                $payments->vendor_name = $customer->name;
            }
            
            $categories = Category::where('id', $request->category_id)->where('type', 2)->first();
            if ($categories) {
                $payments->category_name = $categories->name;
            }

            $payments->vendor_id = $customer->name;
            $action = 'New Payment';
            $module = 'Account';
            $pabbly_array = array(
                "id"     => $payments['id'],
                "type"   => $payments['type'],
                "method" => $payments['method'],
                "date"   => $payments['date'],
                "amount" => $payments['amount'],
                "bill"   => $payments['bill'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}