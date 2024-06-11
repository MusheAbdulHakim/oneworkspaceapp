<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MovieShowBookingSystem\Events\CreateCertificate;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCertificateLis
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
    public function handle(CreateCertificate $event)
    {
        if (module_is_active('PabblyConnect')) {
            $certificate = $event->certificate;

            $pabbly_array = [
                'Certificate Type' => $certificate->name
            ];

            $action = 'New Certificate';
            $module = 'MovieShowBookingSystem';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
