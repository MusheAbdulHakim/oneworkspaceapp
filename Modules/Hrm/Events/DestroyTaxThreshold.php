<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyTaxThreshold
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $taxthreshold;

    public function __construct($taxthreshold)
    {
        $this->taxthreshold = $taxthreshold;
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
