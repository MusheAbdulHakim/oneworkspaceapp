<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyAwardType
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $awardtype;

    public function __construct($awardtype)
    {
        $this->awardtype = $awardtype;
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
