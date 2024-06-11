<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Hrm\Entities\AwardType;
use Modules\Hrm\Events\CreateAward;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateAwardLis
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
    public function handle(CreateAward $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $award = $event->award;
            $employee = User::where('id', $request->employee_id)->first();
            $awardtype = AwardType::find($request->award_type);
            $award->employee_name = $employee->name;
            if (!empty($awardtype)) {
                $award->award_type = $awardtype->name;
            }
            unset($award->user_id);
            $action = 'New Award';
            $module = 'Hrm';

            $pabbly_array = array(
                "Emaployee Name" => $employee->name,
                "award_type"     => $award['award_type'],
                "date"           => $award['date'],
                "gift"           => $award['gift'],
                "description"    => $award['description'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}