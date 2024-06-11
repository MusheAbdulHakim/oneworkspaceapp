<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Newspaper\Entities\NewspaperTax;
use Modules\Newspaper\Entities\NewspaperVarient;
use Modules\Newspaper\Events\CreateNewspaper;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateNewspaperLis
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
    public function handle(CreateNewspaper $event)
    {
        if (module_is_active('PabblyConnect')) {
            $newspaper = $event->newspaper;

            $varient = NewspaperVarient::find($newspaper->varient);
            $tax = NewspaperTax::find($newspaper->taxes);

            $pabbly_array = [
                'News Paper Title' => $newspaper->name,
                'News Paper Date' => $newspaper->date,
                'News Paper Varient' => $varient->name,
                'News Papaer Tax' => $tax->name,
                'News Paper Quantity' => $newspaper->quantity,
                'News Paper Price' => $newspaper->price,
                'News Paper Sale Price' => $newspaper->seles_price
            ];

            $action = 'New Newspaper';
            $module = 'Newspaper';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
