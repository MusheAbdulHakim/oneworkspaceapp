<?php

namespace Modules\Lead\Events;

use Illuminate\Queue\SerializesModels;

class UpdateLabel
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $label;

    public function __construct($request,$label)
    {
        $this->request = $request;
        $this->label = $label;
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
