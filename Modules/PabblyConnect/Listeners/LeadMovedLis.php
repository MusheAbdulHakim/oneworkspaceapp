<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Lead\Entities\Lead;
use Modules\Lead\Entities\LeadStage;
use Modules\Lead\Events\LeadMoved;
use Modules\PabblyConnect\Entities\PabblySend;

class LeadMovedLis
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
    public function handle(LeadMoved $event)
    {
        if (module_is_active('PabblyConnect')) {
            $lead = $event->lead;
            
            $newStage = LeadStage::find($lead->stage_id);

            $lead_stages           = new Lead;
            $lead_stages->name     = $lead->name;
            $lead_stages->stage_name = $newStage->name;
            $lead_stages->order    = $lead->order;
            
            $action = 'Lead Moved';
            $module = 'Lead';
            
            $pabbly_array = array(
                "Name"                => $lead['name'],
                "New Lead Stage Name" => $newStage->name,
                "Email"               => $lead['email'],
                "Subject"             => $lead['subject'],
                "phone"               => $lead['phone'],
                "date"                => $lead['date'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}