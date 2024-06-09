<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyTaxBracket
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $taxbracket;

    public function __construct($taxbracket)
    {
        $this->taxbracket = $taxbracket;
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
