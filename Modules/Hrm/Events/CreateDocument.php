<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class CreateDocument
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $document;

    public function __construct($request, $document)
    {
        $this->request = $request;
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
