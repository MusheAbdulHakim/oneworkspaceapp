<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\ProductService\Entities\Tax;
use Modules\Sales\Entities\Contact;
use Modules\Sales\Entities\Opportunities;
use Modules\Sales\Entities\SalesAccount;
use Modules\Sales\Entities\ShippingProvider;
use Modules\Sales\Events\CreateQuote;

class CreateQuoteLis
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
    public function handle(CreateQuote $event)
    {
        if (module_is_active('PabblyConnect')) {
            $quote = $event->quote;
            $request = $event->request;
            $account = SalesAccount::where('id', $request->account_id)->first();
            $user = User::where('id', $request->user)->first();
            $opportunity = Opportunities::where('id', $request->opportunity)->first();
            $billing_contact = Contact::where('id', $request->billing_contact)->first();
            $shipping_contact = Contact::where('id', $request->shipping_contact)->first();
            $tax = Tax::whereIN('id', $request->tax)->get()->pluck('name')->toArray();
            $shipping_provider = ShippingProvider::where('id', $request->shipping_provider)->first();
            // $quote_status = \Modules\Sales\Entities\Quote::$status[$quote->status];

            $quote->user_name             = !empty($user) ?  $user->name : '';
            // $quote->status              = $quote_status;
            $quote->opportunity         = !empty($opportunity) ?  $opportunity->name : '';
            $quote->account             = !empty($account) ?  $account->name : '';
            $quote->billing_contact     = !empty($billing_contact) ?  $billing_contact->name : '';
            $quote->shipping_contact    = !empty($shipping_contact) ?  $shipping_contact->name : '';
            $quote->tax                 = implode(',', $tax);
            $quote->shipping_provider   = $shipping_provider->name;
            $action = 'New Quote';
            $module = 'Sales';

            $pabbly_array = array(
                "Name"                => $quote['name'],
                "User Name"           => $user->name,
                "Quote Status"        => $quote->status,
                "Quote Opportunity"   => $opportunity->name,
                "Account"             => $account->name,
                "Billing Contact"     => $billing_contact->name,
                "Shipping Provider"   => $shipping_provider->name,
                "Shipping Contact"    => $shipping_contact->name,
                "Tax"                 => $quote->tax,
                "Date quoted"         => $quote['date_quoted'],
                "Quote number"        => $quote['quote_number'],
                "billing_address"     => $quote['billing_address'],
                "billing_city"        => $quote['billing_city'],
                "billing_state"       => $quote['billing_state'],
                "billing_country"     => $quote['billing_country'],
                "billing_postalcode"  => $quote['billing_postalcode'],
                "shipping_address"    => $quote['shipping_address'],
                "shipping_city"       => $quote['shipping_city'],
                "shipping_state"      => $quote['shipping_state'],
                "shipping_country"    => $quote['shipping_country'],
                "shipping_postalcode" => $quote['shipping_postalcode'],
                "description"         => $quote['description'],
            );

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}