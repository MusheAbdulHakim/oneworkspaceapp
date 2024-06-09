<?php

namespace Modules\Lead\Events;

use Illuminate\Queue\SerializesModels;

class StatusChangeLeadTask
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $lead;
    public $task;

    public function __construct($request,$lead,$task)
    {
        $this->request = $request;
        $this->lead = $lead;
        $this->task = $task;
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
