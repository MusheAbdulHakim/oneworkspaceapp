<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MovieShowBookingSystem\Entities\MovieShow;
use Modules\MovieShowBookingSystem\Events\CreateMovieEvent;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateMovieEventLis
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
    public function handle(CreateMovieEvent $event)
    {
        if (module_is_active('PabblyConnect')) {
            $movieevent = $event->movieevent;

            $movieShow = MovieShow::find($movieevent->movie_show_id);

            $pabbly_array = [
                'Movie Title' => $movieShow->movie_name,
                'Movie Show Start Time' => $movieevent->show_start_time,
                'Movie Show End Time' => $movieevent->show_start_time,
                'Movie Show Start Date' => $movieevent->start_date,
                'Movie Show End Date' => $movieevent->end_date,
                'Movie Vanue' => $movieevent->venue
            ];

            $action = 'New Movie Event';
            $module = 'MovieShowBookingSystem';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
