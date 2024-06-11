<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Newspaper\Entities\NewspaperJournalistType;
use Modules\Newspaper\Entities\NewspaperType;
use Modules\Newspaper\Events\CreateNewspaperJournalistInfo;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateNewspaperJournalistInfoLis
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
    public function handle(CreateNewspaperJournalistInfo $event)
    {
        if (module_is_active('PabblyConnect')) {
            $information = $event->information;

            $type = NewspaperJournalistType::find($information->type);
            $user = User::find($information->user_id);

            $pabbly_array = [
                'Information Title' => $information->name,
                'Jounalist Type' => $type->name,
                'User Name' => $user->name,
                'User Email' => $user->email,
                'Information Date' => $information->date,
                'Information' => $information->info,
            ];

            $action = 'New Journalist Information';
            $module = 'Newspaper';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
