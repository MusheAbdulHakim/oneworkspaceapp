<?php

namespace Modules\Lead\Events;

use Illuminate\Queue\SerializesModels;

class CreateLeadStage
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $lead_stage;

    public function __construct($request,$lead_stage)
    {
        $this->request = $request;
        $this->lead_stage = $lead_stage;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
