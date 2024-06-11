<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Newspaper\Events\CreateNewspaperDistributions;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateNewspaperDistributionsLis
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
    public function handle(CreateNewspaperDistributions $event)
    {
        if (module_is_active('PabblyConnect')) {
            $distribution = $event->distribution;

            $pabbly_array = [
                'Title' => $distribution->name,
                'Address' => $distribution->address
            ];

            $action = 'New Newspaper Distribution Center';
            $module = 'Newspaper';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
