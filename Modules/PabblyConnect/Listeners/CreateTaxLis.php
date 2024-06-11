<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CarDealership\Events\CreateTax;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateTaxLis
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
    public function handle(CreateTax $event)
    {
        if (module_is_active('PabblyConnect')) {
            $tax = $event->tax;

            $pabbly_array = [
                'Tax Title' => $tax->name,
                'Tax Rate' => $tax->rate
            ];

            $action = 'New Tax';
            $module = 'CarDealership';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
