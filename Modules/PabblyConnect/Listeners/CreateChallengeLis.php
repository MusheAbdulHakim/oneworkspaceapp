<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\InnovationCenter\Entities\CreativityCategories;
use Modules\InnovationCenter\Events\CreateChallenge;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateChallengeLis
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
    public function handle(CreateChallenge $event)
    {
        if (module_is_active('PabblyConnect')) {
            $Challenges = $event->Challenges;

            $category = CreativityCategories::find($Challenges->category);

            $pabbly_array = [
                'Challenge Title' => $Challenges->name,
                'Challenge Category Title' => $category->title,
                'Challenge End Date' => $Challenges->end_date,
                'Position' => $Challenges->position,
                'Explanation' => $Challenges->explantion,
                'Notes' => $Challenges->notes
            ];

            $action = 'New Challenges';
            $module = 'InnovationCenter';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
