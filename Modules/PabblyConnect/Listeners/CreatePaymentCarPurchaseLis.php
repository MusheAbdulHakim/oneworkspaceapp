<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CarDealership\Events\CreatePaymentCarPurchase;
use Modules\PabblyConnect\Entities\PabblySend;

class CreatePaymentCarPurchaseLis
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
    public function handle(CreatePaymentCarPurchase $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $car_purchase = $event->car_purchase;

            $pabbly_array = [
                'Payment Date' => $request->date,
                'Payment Amount' => $request->amount,
                'Reference' => $request->reference,
                'Description' => $request->description,
                'Purchase Date' => $car_purchase->purchase_date,
                'Due Date' => $car_purchase->due_date,
                'Send Date' => $car_purchase->send_date,
            ];

            $action = 'New Car Purchase Payment';
            $module = 'CarDealership';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
