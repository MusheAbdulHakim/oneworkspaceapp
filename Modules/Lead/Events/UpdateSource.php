<?php

namespace Modules\Lead\Events;

use Illuminate\Queue\SerializesModels;

class UpdateSource
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $source;

    public function __construct($request,$source)
    {
        $this->request = $request;
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
