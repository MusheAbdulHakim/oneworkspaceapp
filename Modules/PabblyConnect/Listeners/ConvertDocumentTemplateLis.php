<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\DocumentTemplate\Events\ConvertDocumentTemplate;
use Modules\PabblyConnect\Entities\PabblySend;

class ConvertDocumentTemplateLis
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
    public function handle(ConvertDocumentTemplate $event)
    {
        if (module_is_active('PabblyConnect')) {
            $convert = $event->convert['document'];

            $pabbly_array = [
                'Document Template Subject' => $convert->subject,
                'Notes' => $convert->notes,
                'Status' => $convert->status,
            ];

            $module = 'DocumentTemplate';
            $action = 'Convert Document Template';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
