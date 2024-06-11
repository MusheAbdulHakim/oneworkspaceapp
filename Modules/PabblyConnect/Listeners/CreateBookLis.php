<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Internalknowledge\Events\CreateBook;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateBookLis
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
    public function handle(CreateBook $event)
    {
        if (module_is_active('PabblyConnect')) {
            $book = $event->book;

            $users = explode(',', $book->user_id);

            $usersData = User::whereIn('id', $users)->get();

            $usersInfo = [];
            foreach ($usersData as $user) {
                $usersInfo[] = [
                    'Name' => $user->name,
                    'Email' => $user->email,
                ];
            }

            $pabbly_array = [
                'Book title' => $book->title,
                'Book Description' => $book->description,
                'Users' => $usersInfo
            ];

            $action = 'New Book';
            $module = 'Internalknowledge';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
