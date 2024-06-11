<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Documents\Entities\DocumentType;
use Modules\DocumentTemplate\Events\CreateDocumentTemplate;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateDocumentTemplateLis
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
    public function handle(CreateDocumentTemplate $event)
    {
        if (module_is_active('PabblyConnect')) {
            $template = $event->template;

            $template_type = DocumentType::find($template->type);

            $pabbly_array = [
                'Document Template Subject' => $template->subject,
                'Document Type' => $template_type->name,
                'Notes' => $template->notes
            ];

            $module = 'DocumentTemplate';
            $action = 'New Document Template';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
