<?php

namespace Modules\Lead\Events;

use Illuminate\Queue\SerializesModels;

class DestroyPipeline
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $pipeline;

    public function __construct($pipeline)
    {
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
