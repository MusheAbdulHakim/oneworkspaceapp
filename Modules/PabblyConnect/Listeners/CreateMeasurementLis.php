<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\GymManagement\Entities\GymMember;
use Modules\GymManagement\Events\CreateMeasurement;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateMeasurementLis
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
    public function handle(CreateMeasurement $event)
    {
        if (module_is_active('PabblyConnect')) {
            $measurement = $event->measurement;

            $member = GymMember::find($measurement->member_id);

            $pabbly_array = [
                'Member Name' => $member->name,
                'Member Email' => $member->email,
                'Member Gender' => $measurement->gender,
                'Measurement Date' => $measurement->date,
                'Member Age' => $measurement->age,
                'Member Weight' => $measurement->weight,
                'Member height' => $measurement->height,
                'Member Neck' => $measurement->neck,
                'Member Chest' => $measurement->chest,
                'Member Calf' => $measurement->calf,
                'Member Waist' => $measurement->waist,
                'Member BMI' => $measurement->bmi,
                'Member BMR' => $measurement->bmr,
            ];

            $action = 'New Measurement';
            $module = 'GymManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
