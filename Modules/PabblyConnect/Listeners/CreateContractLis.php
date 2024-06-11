<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Contract\Entities\ContractType;
use Modules\Contract\Events\CreateContract;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Taskly\Entities\Project;

class CreateContractLis
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
    public function handle(CreateContract $event)
    {
        if (module_is_active('PabblyConnect')) {
            $contract = $event->contract;
            $user = User::where('id', $contract->user_id)->first();
            $contract_type = ContractType::where('id', $contract->type)->first();
            $project = Project::where('id', $contract->project_id)->first();
            
            $action = 'New Contract';
            $module = 'Contract';

            $pabbly_array = array(
                "Subject"      => $contract['subject'],
                "User Name"    => $user->name,
                "Project Name" => $project->name,
                "value"        => $contract['value'],
                "start_date"   => $contract['start_date'],
                "end_date"     => $contract['end_date'],
                "notes"        => $contract['notes'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}