<?php

namespace Modules\GHL\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Modules\GHL\Traits\UserGHL;

class CalendarController extends Controller
{
    use UserGHL;

    public function index()
    {
        $ghl = $this->initGHL();
        if(!empty($ghl)){
            $calendars = $ghl->withVersion('2021-04-15')
                            ->make()->calendars()->list($this->userGHL()->locationId);

            return view('ghl::calendars.index',compact(
                'calendars'
            ));
        }
        return back()->with('error','Please authenticate your ghl account to continue');

    }

    public function slots(Request $request, $calendarId){
        $ghl = $this->initGHL();
        if(!empty($ghl)){
            $startDate = Carbon::now()->secondsSinceMidnight();
            $endDate = now()->addDay()->secondsUntilEndOfDay();
            $slots = $ghl->withVersion('2021-04-15')
                            ->make()->calendars()->slots($calendarId, $startDate, $endDate);

            return view('ghl::calendars.slots',compact(
                'slots'
            ));

        }
        return back()->with('error','Please authenticate your ghl account to continue');
    }


}
