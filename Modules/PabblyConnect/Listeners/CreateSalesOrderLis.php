<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\ProductService\Entities\Tax;
use Modules\Sales\Entities\Contact;
use Modules\Sales\Entities\Opportunities;
use Modules\Sales\Entities\Quote;
use Modules\Sales\Entities\SalesAccount;
use Modules\Sales\Entities\ShippingProvider;
use Modules\Sales\Events\CreateSalesOrder;

class CreateSalesOrderLis
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
    public function handle(CreateSalesOrder $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request    = $event->request;
            $salesorder = $event->salesorder;
            
            $user               = User::where('id', $request->user)->first();
            $quote              = Quote::where('id', $request->quote)->first();
            $opportunity        = Opportunities::where('id', $request->opportunity)->first();
            $account            = SalesAccount::where('id', $request->account_id)->first();
            $billing_contact    = Contact::where('id', $request->billing_contact)->first();
            $shipping_contact   = Contact::where('id', $request->shipping_contact)->first();
            $opportunity        = Opportunities::where('id', $request->opportunity)->first();
            $tax                = Tax::whereIN('id', $request->tax)->get()->pluck('name')->toArray();
            $shipping_provider  = ShippingProvider::where('id', $request->shipping_provider)->first();

            $salesorder->user_name             = !empty($user) ?  $user->name : '';
            $salesorder->quote               = !empty($quote) ? $quote->name : '';
            $salesorder->opportunity         = !empty($opportunity) ?  $opportunity->name : '';
            // $salesorder->status              = \Modules\Sales\Entities\Salesorder::$status[$salesorder->status];
            $salesorder->account             = !empty($account) ?  $account->name : '';
            $salesorder->billing_contact     = !empty($billing_contact) ?  $billing_contact->name : '';
            $salesorder->shipping_contact    = !empty($shipping_contact) ?  $shipping_contact->name : '';
            $salesorder->tax                 = implode(',', $tax);
            $salesorder->shipping_provider   = $shipping_provider->name;
            $action = 'New Sales Order';
            $module = 'Sales';

            $pabbly_array = array(
                "Name"                => $salesorder['name'],
                "User Name"           => $user->name,
                "Status"              => $salesorder->status,
                "Account"             => $account->name,
                "Quote Opportunity"   => $opportunity->name,
                "Billing Contact"     => $billing_contact->name,
                "Shipping Provider"   => $shipping_provider->name,
                "Shipping Contact"    => $shipping_contact->name,
                "Tax"                 => $salesorder->tax,
                "Date quoted"         => $salesorder['date_quoted'],
                "Quote number"        => $salesorder['quote_number'],
                "billing_address"     => $salesorder['billing_address'],
                "billing_city"        => $salesorder['billing_city'],
                "billing_state"       => $salesorder['billing_state'],
                "billing_country"     => $salesorder['billing_country'],
                "billing_postalcode"  => $salesorder['billing_postalcode'],
                "shipping_address"    => $salesorder['shipping_address'],
                "shipping_city"       => $salesorder['shipping_city'],
                "shipping_state"      => $salesorder['shipping_state'],
                "shipping_country"    => $salesorder['shipping_country'],
                "shipping_postalcode" => $salesorder['shipping_postalcode'],
                "description"         => $salesorder['description'],
            );

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}