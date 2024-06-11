<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\Invoice;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;

class CompanyPaymentLis
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
        if(module_is_active('PabblyConnect'))
        {
            $type = $event->type;
            $payment = $event->payment;
            $data = $event->data;

            if($type == 'invoice')
            {
                if(!empty($payment) && !empty($data))
                {
                    $action = 'Invoice Status Updated';
                    $module = 'general';

                    $pabbly_array = array(
                        "invoice"        => Invoice::invoiceNumberFormat($data['invoice_id']),
                        "issue_date"     => $data['issue_date'],
                        "due_date"       => $data['due_date'],
                        "send_date"      => $data['send_date'],
                        "invoice_module" => $data['invoice_module'],
                    );
                    PabblySend::SendPabblyCall($module ,$pabbly_array,$action,$data->workspace);
                }
            }
            elseif($type == 'salesinvoice')
            {
                if(!empty($payment) && !empty($data))
                {
                    $action = 'New Sales Invoice Payment';
                    $module = 'Sales';

                    $pabbly_array = array(
                        "invoice_id"   => $payment['invoice_id'],
                        "date"         => $payment['date'],
                        "amount"       => $payment['amount'],
                        "payment_type" => $payment['payment_type'],
                        "receipt"      => $payment['receipt'],
                    );
                    PabblySend::SendPabblyCall($module ,$pabbly_array,$action,$data->workspace);
                }
            }
            elseif($type == 'retainer')
            {
                if(!empty($payment) && !empty($data))
                {
                    $action = 'New Retainer Payment';
                    $module = 'Retainer';

                    $pabbly_array = array(
                        "retainer_id"  => $payment['retainer_id'],
                        "date"         => $payment['date'],
                        "amount"       => $payment['amount'],
                        "currency"     => $payment['currency'],
                        "payment_type" => $payment['payment_type'],
                        "receipt"      => $payment['receipt'],
                    );
                    PabblySend::SendPabblyCall($module ,$pabbly_array,$action,$data->workspace);
                }
            }
        }
    }
}