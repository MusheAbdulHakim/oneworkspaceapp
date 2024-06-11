<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Hrm\Entities\Designation;
use Modules\Hrm\Entities\Employee;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Rotas\Events\CreateRota;

class CreateRotaLis
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
    public function handle(CreateRota $event)
    {
        $rotas = $event->rotas;

        if (module_is_active('PabblyConnect')) {
            $employee    = Employee::where('id', $rotas->user_id)->first();
            $designation = Designation::where('id', $employee->designation_id)->first();

            $action = 'New Rota';
            $module = 'Rotas';

            $pabbly_array = array(
                "rotas_date"         => $rotas['rotas_date'],
                "start_time"         => $rotas['start_time'],
                "end_time"           => $rotas['end_time'],
                "break_time"         => $rotas['break_time'],
                "time_diff_in_minut" => $rotas['time_diff_in_minut'],
                "note"               => $rotas['note'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}