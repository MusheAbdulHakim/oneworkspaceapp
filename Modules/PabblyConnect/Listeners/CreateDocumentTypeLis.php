<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Documents\Events\CreateDocumentsType;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateDocumentTypeLis
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
    public function handle(CreateDocumentsType $event)
    {
        if (module_is_active('PabblyConnect')) {
            $documentsType = $event->documentsType;

            $pabbly_array = [
                'Document Type Title' => $documentsType->name,
            ];

            $module = 'Documents';
            $action = 'New Document Type';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
