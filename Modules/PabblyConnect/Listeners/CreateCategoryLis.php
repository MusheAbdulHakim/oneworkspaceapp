<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CarDealership\Events\CreateCategory;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateCategoryLis
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
    public function handle(CreateCategory $event)
    {
        if (module_is_active('PabblyConnect')) {
            $category = $event->category;

            $pabbly_array = [
                'Car Category Title' => $category->name,
                'Color' => $category->color
            ];

            $action = 'New Car Category';
            $module = 'CarDealership';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
