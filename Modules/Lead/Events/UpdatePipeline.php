<?php

namespace Modules\Lead\Events;

use Illuminate\Queue\SerializesModels;

class UpdatePipeline
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $pipeline;

    public function __construct($request,$pipeline)
    {
        $this->request = $request;
        $this->pipeline = $pipeline;
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
