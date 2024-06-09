<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyAnnouncement
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $announcement;

    public function __construct($announcement)
    {
        $this->announcement = $announcement;
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
