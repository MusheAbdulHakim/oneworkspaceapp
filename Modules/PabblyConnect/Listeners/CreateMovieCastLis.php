<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\MovieShowBookingSystem\Entities\CastType;
use Modules\MovieShowBookingSystem\Events\CreateMovieCast;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateMovieCastLis
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
    public function handle(CreateMovieCast $event)
    {
        if (module_is_active('PabblyConnect')) {
            $moviecast = $event->moviecast;

            $movie_cast = CastType::find($moviecast->cast_type);

            $pabbly_array = [
                'Movie Title' => $moviecast->movie_title,
                'Cast Name' => $moviecast->cast_name,
                'Movie Cast Type' => $movie_cast->name
            ];

            $action = 'New Movie Cast';
            $module = 'MovieShowBookingSystem';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
