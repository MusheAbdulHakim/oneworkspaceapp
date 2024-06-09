<?php

namespace Modules\Lead\Events;

use Illuminate\Queue\SerializesModels;

class DestroyLabel
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $label;

    public function __construct($label)
    {
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
