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
use Modules\Sales\Entities\SalesOrder;
use Modules\Sales\Entities\ShippingProvider;
use Modules\Sales\Events\CreateSalesInvoice;

class CreateSalesInvoiceLis
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
    public function handle(CreateSalesInvoice $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $invoice = $event->invoice;
            
            $user              = User::where('id', $request->user)->first();
            $salesorder        = SalesOrder::where('id', $request->salesorder)->first();
            $quote             = Quote::where('id', $request->quote)->first();
            $opportunity       = Opportunities::where('id', $request->opportunity)->first();
            $account           = SalesAccount::where('id', $request->account_id)->first();
            $billing_contact   = Contact::where('id', $request->billing_contact)->first();
            $shipping_contact  = Contact::where('id', $request->shipping_contact)->first();
            $opportunity       = Opportunities::where('id', $request->opportunity)->first();
            $tax               = Tax::whereIN('id', $request->tax)->get()->pluck('name')->toArray();
            $shipping_provider = ShippingProvider::where('id', $request->shipping_provider)->first();

            $invoice->user_name             = !empty($user) ?  $user->name : '';
            $invoice->salesorder          = !empty($salesorder) ? $salesorder->name : '';
            $invoice->quote               = !empty($quote) ? $quote->name : '';
            $invoice->opportunity         = !empty($opportunity) ?  $opportunity->name : '';
            $invoice->account             = !empty($account) ?  $account->name : '';
            $invoice->billing_contact     = !empty($billing_contact) ?  $billing_contact->name : '';
            $invoice->shipping_contact    = !empty($shipping_contact) ?  $shipping_contact->name : '';
            $invoice->tax                 = implode(',', $tax);
            $invoice->shipping_provider   = $shipping_provider->name;
            $action = 'New Sales Invoice';
            $module = 'Sales';

            $pabbly_array = array(
                "Name"                => $invoice['name'],
                "User Name"           => $user->name,
                "Sales Order"         => $salesorder->name,
                "Account"             => $invoice->account,
                "Quote"               => $invoice->quote,
                "Opportunity"         => $opportunity->name,
                "Billing Contact"     => $billing_contact->name,
                "Shipping Provider"   => $shipping_provider->name,
                "Shipping Contact"    => $shipping_contact->name,
                "Tax"                 => $invoice->tax,
                "Date Quoted"         => $invoice['date_quoted'],
                "Quote Number"        => $invoice['quote_number'],
                "Billing Address"     => $invoice['billing_address'],
                "Billing City"        => $invoice['billing_city'],
                "Billing State"       => $invoice['billing_state'],
                "Billing Country"     => $invoice['billing_country'],
                "Billing Postalcode"  => $invoice['billing_postalcode'],
                "Shipping Address"    => $invoice['shipping_address'],
                "Shipping City"       => $invoice['shipping_city'],
                "Shipping State"      => $invoice['shipping_state'],
                "Shipping Country"    => $invoice['shipping_country'],
                "Shipping Postalcode" => $invoice['shipping_postalcode'],
                "Description"         => $invoice['description'],
            );

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}