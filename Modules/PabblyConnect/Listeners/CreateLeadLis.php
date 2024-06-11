<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Lead\Entities\LeadStage;
use Modules\Lead\Entities\Pipeline;
use Modules\Lead\Events\CreateLead;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateLeadLis
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
    public function handle(CreateLead $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $lead = $event->lead;
            $user = User::where('id', $request->user_id)->first();
            $pipeline = Pipeline::where('id', $lead->pipeline_id)->first();
            $stage = LeadStage::where('id', $lead->stage_id)->first();

            $action = 'New Lead';
            $module = 'Lead';
            $pabbly_array = array(
                "Name"    => $lead['name'],
                "Email"   => $lead['email'],
                "Subject" => $lead['subject'],
                "phone"   => $lead['phone'],
                "date"    => $lead['date'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}