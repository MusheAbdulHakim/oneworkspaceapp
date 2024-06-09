<?php

namespace Modules\Lead\Events;

use Illuminate\Queue\SerializesModels;

class DealCallUpdate
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $deal;

    public function __construct($request,$deal)
    {
        $this->request = $request;
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
