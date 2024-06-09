<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyTransfer
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $transfer;

    public function __construct($transfer)
    {
        $this->transfer = $transfer;
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
