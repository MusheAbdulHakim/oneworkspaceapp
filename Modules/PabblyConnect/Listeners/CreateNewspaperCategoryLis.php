<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Newspaper\Events\CreateNewspaperCategory;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateNewspaperCategoryLis
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
    public function handle(CreateNewspaperCategory $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $newspapercategory = $event->newspapercategory;

            $pabbly_array = [
                'News Paper Category Title' => $newspapercategory->name,
            ];

            $action = 'New Newspaper Category';
            $module = 'Newspaper';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
