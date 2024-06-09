<?php

namespace Modules\Lead\Events;

use Illuminate\Queue\SerializesModels;

class StatusChangeDealTask
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $deal;
    public $task;

    public function __construct($request,$deal,$task)
    {
        $this->request = $request;
        $this->deal = $deal;
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
