<?php

namespace Modules\Lead\Events;

use Illuminate\Queue\SerializesModels;

class DestroyLeadStage
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $leads;

    public function __construct($leads)
    {
        $this->leads = $leads;
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
