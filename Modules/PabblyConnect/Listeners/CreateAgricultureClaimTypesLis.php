<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\AgricultureManagement\Events\CreateAgricultureClaimType;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateAgricultureClaimTypesLis
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
    public function handle(CreateAgricultureClaimType $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $claimtype = $event->claimtype;

            $pabbly_array = [
                'Agriculture Equipment Claim Type' => $claimtype->name,
            ];

            $action = 'New Agriculture Claim Type';
            $module = 'AgricultureManagement';

            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
