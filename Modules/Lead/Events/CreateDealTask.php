<?php

namespace Modules\Lead\Events;

use Illuminate\Queue\SerializesModels;

class CreateDealTask
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $dealTask;
    public $deal;

    public function __construct($request,$dealTask,$deal)
    {
        $this->request = $request;
        $this->dealTask = $dealTask;
        $this->deal = $deal;
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
