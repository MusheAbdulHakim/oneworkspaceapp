<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class CreateTaxThreshold
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $taxthreshold;

    public function __construct($request, $taxthreshold)
    {
        $this->request = $request;
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
