<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyAward
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $award;

    public function __construct($award)
    {
        $this->award = $award;
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
