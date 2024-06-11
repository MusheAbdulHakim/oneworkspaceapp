<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Documents\Entities\DocumentType;
use Modules\Documents\Events\CreateDocuments;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Taskly\Entities\Project;

class CreateDocumentLis
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
    public function handle(CreateDocuments $event)
    {
        if (module_is_active('PabblyConnect')) {
            $documents = $event->documents;

            $document_type = DocumentType::find($documents->type);
            $user = User::find($documents->user_id);
            $project = Project::find($documents->project_id);

            $pabbly_array = [
                'Document Subject' => $documents->subject,
                'Document Type' => $document_type->name,
                'User Name' => $user->name,
                'User Email' => $user->email,
                'Project Name' => $project->name,
                'Description' => $documents->notes
            ];

            $module = 'Documents';
            $action = 'New Document';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
