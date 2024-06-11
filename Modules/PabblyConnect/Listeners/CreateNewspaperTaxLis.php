<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Newspaper\Events\CreateNewspaperTax;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateNewspaperTaxLis
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
    public function handle(CreateNewspaperTax $event)
    {
        if (module_is_active('PabblyConnect')) {
            $newspapertax = $event->newspapertax;

            $pabbly_array = [
                'Tax Title' => $newspapertax->name,
                'Tax Percentage' => $newspapertax->percentage,
            ];

            $action = 'New Newspaper Tax';
            $module = 'Newspaper';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
