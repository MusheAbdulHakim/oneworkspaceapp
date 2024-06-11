<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\DocumentTemplate\Events\DuplicateDocumentTemplate;
use Modules\PabblyConnect\Entities\PabblySend;

class DuplicateDocumentTemplateLis
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(DuplicateDocumentTemplate $event)
    {
        if (module_is_active('PabblyConnect')) {
            $duplicate = $event->duplicate['document'];

            $pabbly_array = [
                'Document Template Subject' => $duplicate->subject,
                'Notes' => $duplicate->notes,
                'Status' => $duplicate->status,
                'Description' => $duplicate->description,
            ];

            $module = 'DocumentTemplate';
            $action = 'Duplicate Document Template';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
