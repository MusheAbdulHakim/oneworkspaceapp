<?php

namespace Modules\Lead\Events;

use Illuminate\Queue\SerializesModels;

class DestroyLeadFile
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $lead;

    public function __construct($lead)
    {
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
