<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MovieShowBookingSystem\Events\CreateMovieCrew;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateMovieCrewLis
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
    public function handle(CreateMovieCrew $event)
    {
        if (module_is_active('PabblyConnect')) {
            $moviecrew = $event->moviecrew;

            $pabbly_array = [
                'Movie Crew' => $moviecrew->name
            ];

            $action = 'New Movie Crew';
            $module = 'MovieShowBookingSystem';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
