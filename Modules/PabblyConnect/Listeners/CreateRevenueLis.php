<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Account\Entities\BankAccount;
use Modules\Account\Entities\Customer;
use Modules\Account\Events\CreateRevenue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\ProductService\Entities\Category;

class CreateRevenueLis
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
    public function handle(CreateRevenue $event)
    {

        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $revenue = $event->revenue;

            $account = BankAccount::where('id', $request->account_id)->first();
            if ($account) {
                $revenue->account_bank_name = $account->bank_name;
            }
            $customer = Customer::find($request->customer_id);
            $categories = Category::where('id', $request->category_id)->where('type', 1)->first();
            $revenue->customer_name = $customer->name;
            $revenue->category_name = $categories->name;
            unset($revenue->add_receipt, $revenue->user_id, $revenue->payment_method);
            $action = 'New Revenue';
            $module = 'Account';

            $pabbly_array = array(
                "amount"      => $revenue['amount'],
                "date"        => $revenue['date'],
                "user"        => $customer->name,
                "category"    => $categories->name,
                "reference"   => $revenue['reference'],
                "type"        => $revenue['type'],
                "description" => $revenue['description'],
                "workspace"   => $revenue['workspace'],
                "user_type"   => $revenue['user_type'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}