<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CourierManagement\Events\Courierpackagecategorycreate;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCourierPackageCategoryLis
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
    public function handle(Courierpackagecategorycreate $event)
    {
        if (module_is_active('PabblyConnect')) {
            $packageCategory = $event->packageCategory;

            $pabbly_array = [
                'Package Category' => $packageCategory->category
            ];

            $action = 'New Courier Package Category';
            $module = 'CourierManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
