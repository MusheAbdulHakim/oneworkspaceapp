<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\Spreadsheet\Events\CreateSpreadsheet;

class CreateSpreadsheetLis
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
    public function handle(CreateSpreadsheet $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $spreadsheets = $event->spreadsheets;

            $user = User::find($spreadsheets->user_id);

            $pabbly_array = array(
                "Folder Name" => $spreadsheets->folder_name,
                "Type" => $spreadsheets->type,
                "User Name" => $user->name,
            );

            $action = 'New Spreadsheet';
            $module = 'Spreadsheet';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
