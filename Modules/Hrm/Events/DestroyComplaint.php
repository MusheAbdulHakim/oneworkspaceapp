<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyComplaint
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $complaint;

    public function __construct($complaint)
    {
        $this->complaint = $complaint;
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
