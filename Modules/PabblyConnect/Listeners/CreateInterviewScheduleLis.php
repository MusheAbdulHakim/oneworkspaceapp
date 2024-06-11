<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Recruitment\Entities\JobApplication;
use Modules\Recruitment\Events\CreateInterviewSchedule;

class CreateInterviewScheduleLis
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
    public function handle(CreateInterviewSchedule $event)
    {
        if (module_is_active('PabblyConnect')) {
            $schedule = $event->schedule;

            if ($schedule->employee) {

                $user = User::where('id', $schedule->employee)->first();
                // $schedule->employee = $user->name;
            }
            if ($schedule->candidate) {
                $candidates = JobApplication::where('id', $schedule->candidate)->first();
            }
            unset($schedule->user_id);
            $action = 'Interview Schedule';
            $module = 'Recruitment';
            $pabbly_array = array(
                "name"     => $candidates->name,
                "employee" => $user->name,
                "date"     => $schedule['date'],
                "time"     => $schedule['time'],
                "comment"  => $schedule['comment'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}