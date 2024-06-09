<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyBranch
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $branch;

    public function __construct($branch)
    {
        $this->branch = $branch;
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
