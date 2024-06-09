<?php

namespace Modules\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class CreateDocumentType
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $document_type;

    public function __construct($request, $document_type)
    {
        $this->request = $request;
        $this->document_type = $document_type;
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
