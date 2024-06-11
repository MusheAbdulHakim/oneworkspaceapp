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
        return redirect()->route('settings.index')->with('error','Please authenticate your ghl account to continue');

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
        return redirect()->route('settings.index')->with('error','Please authenticate your ghl account to continue');
    }

    public function events(){
        $ghl = $this->initGHL();
        if(!empty($ghl)){
            $startDate = Carbon::now()->valueOf();
            $endDate = Carbon::now()->addMonth()->valueOf();
            // $events = $ghl->withVersion('2021-04-15')
            //                 ->make()->calendars()
            //                 ->events()->get('1718072232319','1720664232319',$this->userGHL()->locationId,'',[
            //                     // 'endTime' => $endDate,
            //                     'userId' => $this->userGHL()->userId,
            //                 ]);
            $events = [];
            return view('ghl::calendars.events',compact(
                'events'
            ));

        }
        return redirect()->route('settings.index')->with('error','Please authenticate your ghl account to continue');
    }

}
