<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Lead\Entities\Deal;
use Modules\Lead\Entities\DealStage;
use Modules\Lead\Events\DealMoved;
use Modules\PabblyConnect\Entities\PabblySend;

class DealMovedLis
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
    public function handle(DealMoved $event)
    {
        if (module_is_active('PabblyConnect')) {
            $deal = $event->deal;
            
            $newStage = DealStage::find($deal->stage_id);

            $deal_stages           = new Deal;
            $deal_stages->name     = $deal->name;
            $deal_stages->stage_name = $newStage->name;
            $deal_stages->order    = $deal->order;
            
            $action = 'Deal Moved';
            $module = 'Lead';

            $pabbly_array = array(
                "name"   => $deal['name'],
                "price"  => $deal['price'],
                "status" => $deal['status'],
                "phone"  => $deal['phone'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}

