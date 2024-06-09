<?php

namespace Modules\Lead\Events;

use Illuminate\Queue\SerializesModels;

class DestroySource
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $source;

    public function __construct($source)
    {
        $this->source = $source;
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
