<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\InsuranceManagement\Events\CreatepolicyType;
use Modules\PabblyConnect\Entities\PabblySend;

class CreatepolicyTypeLis
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
    public function handle(CreatepolicyType $event)
    {
        if (module_is_active('PabblyConnect')) {
            $PolicyType = $event->PolicyType;

            $pabbly_array = [
                'Insurance Type Title' => $PolicyType->name,
                'Insurance Type Code' => $PolicyType->code,
            ];

            $action = 'New Policy Type';
            $module = 'InsuranceManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
