<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyWarning
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $warning;

    public function __construct($warning)
    {
        $this->warning = $warning;
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
