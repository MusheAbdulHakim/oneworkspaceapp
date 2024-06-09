<?php

namespace Modules\Lead\Events;

use Illuminate\Queue\SerializesModels;

class UpdateLeadStage
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $leadStage;

    public function __construct($request,$leadStage)
    {
        $this->request = $request;
        $this->leadStage = $leadStage;
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
