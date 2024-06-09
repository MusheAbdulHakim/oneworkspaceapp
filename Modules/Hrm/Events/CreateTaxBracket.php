<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class CreateTaxBracket
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $request;
    public $taxbracket;

    public function __construct($request, $taxbracket)
    {
        $this->request = $request;
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
