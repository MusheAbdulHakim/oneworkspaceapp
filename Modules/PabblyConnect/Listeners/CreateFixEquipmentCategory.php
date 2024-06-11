<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\FixEquipment\Events\CreateCategory;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateFixEquipmentCategory
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
        if(module_is_active('PabblyConnect')){
            $request = $event->request;
            $category = $event->category;

            $pabbly_array = [
                'Category Title' => $category->title,
                'Category Type' => $category->category_type
            ];

            $action = 'New Category';
            $module = 'FixEquipment';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
