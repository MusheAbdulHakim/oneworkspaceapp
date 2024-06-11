<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\InnovationCenter\Events\CreateCategory;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Zapier\Entities\SendZap;

class CreateChallengeCategoryLis
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
            $CreativityCategories = $event->CreativityCategories;

            $pabbly_array = [
                'Creative Categories' => $CreativityCategories->title,
            ];

            $action = 'New Challenge Category';
            $module = 'InnovationCenter';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
