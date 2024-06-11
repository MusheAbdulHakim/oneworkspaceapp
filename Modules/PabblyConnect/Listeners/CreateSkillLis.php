<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\GymManagement\Events\CreateSkill;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateSkillLis
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
    public function handle(CreateSkill $event)
    {
        if (module_is_active('PabblyConnect')) {
            $skill = $event->skill;

            $pabbly_array = [
                'Skill' => $skill->name
            ];

            $action = 'New Skill';
            $module = 'GymManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
