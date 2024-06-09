<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyTaxRebate
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $taxrebate;

    public function __construct($taxrebate)
    {
        $this->taxrebate = $taxrebate;
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
