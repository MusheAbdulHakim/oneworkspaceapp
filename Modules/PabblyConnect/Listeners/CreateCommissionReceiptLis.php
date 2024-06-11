<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Commission\Entities\CommissionModule;
use Modules\Commission\Entities\CommissionPlan;
use Modules\Commission\Events\CreateCommissionReceipt;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Zapier\Entities\SendZap;

class CreateCommissionReceiptLis
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
    public function handle(CreateCommissionReceipt $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $commissionReceipt = $event->commissionReceipt;

            $modules = CommissionModule::find($commissionReceipt->commission_str);
            $commission_plan = CommissionPlan::find($commissionReceipt->commissionplan_id);
            $agent = User::find($commissionReceipt->agent);

            $pabbly_array = array(
                "Commission Structure" => $modules->module,
                "Commission Sub Structure Title" => $modules->submodule,
                "Commission Plan Title" => $commission_plan->name,
                "Agent Name" => $agent->name,
                "Amount" => $commissionReceipt->amount,
                "Commission Date" => $commissionReceipt->commission_date,
            );

            $action = 'New Commission Receipt';
            $module = 'Commission';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
