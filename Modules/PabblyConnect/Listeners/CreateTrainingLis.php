<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Training\Events\CreateTraining;

class CreateTrainingLis
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
    public function handle(CreateTraining $event)
    {
        if (module_is_active('PabblyConnect')) {
            
            $training = $event->training;
            
            $action = 'New Training';
            $module = 'Training';
            
            $pabbly_array = array(
                "Title"         => $module,
                "start_date"    => $training['start_date'],
                "end_date"      => $training['end_date'],
                "training_cost" => $training['training_cost'],
                "description"   => $training['description'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}