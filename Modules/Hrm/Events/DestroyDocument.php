<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class DestroyDocument
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $document;

    public function __construct($document)
    {
        $this->document = $document;
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
