<?php

namespace Modules\Lead\Events;

use Illuminate\Queue\SerializesModels;

class UpdateLead
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $lead;

    public function __construct($request,$lead)
    {
        $this->request = $request;
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
