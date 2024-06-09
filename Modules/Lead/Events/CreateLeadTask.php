<?php

namespace Modules\Lead\Events;

use Illuminate\Queue\SerializesModels;

class CreateLeadTask
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $leadTask;
    public $lead;

    public function __construct($request,$leadTask,$lead)
    {
        $this->request = $request;
        $this->leadTask = $leadTask;
        $this->lead = $lead;
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
