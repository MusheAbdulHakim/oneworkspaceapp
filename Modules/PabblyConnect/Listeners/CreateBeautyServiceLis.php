<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\BeautySpaManagement\Events\CreateBeautyService;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateBeautyServiceLis
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
    public function handle(CreateBeautyService $event)
    {
        if (module_is_active('Zapier')) {
            $beautyservice = $event->beautyservice;

            $pabbly_array = [
                'Service Title' => $beautyservice->name,
                'Service Price' => $beautyservice->price,
                'Service Time' => $beautyservice->time
            ];

            $action = 'New Beauty Service';
            $module = 'BeautySpaManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
