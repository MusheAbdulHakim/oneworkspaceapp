<?php

namespace Modules\GoHighLevel\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\GoHighLevel\Helper\GohighlevelHelper;

class CalendarController extends Controller
{

    public function index()
    {
        try {

            $helper = (new GohighlevelHelper());
            $user = auth()->user();
            $client = $helper->SubAccountClient($user);
            $access = $helper->subAccountAccess($user);
            if (!empty($client) && !empty($access)) {
                $calendars = $client->withVersion('2021-04-15')
                    ->make()->calendars()->list($access->locationId);

                return view('gohighlevel::calendars.index', compact(
                    'calendars'
                ));
            }
            return redirect()->route('settings.index')->with('error', 'Gohighlevel is not enabled for your account please contact the administrator');
        } catch (\MusheAbdulHakim\GoHighLevel\Exceptions\ErrorException $e) {
            return back()->with('error', 'Token has expired please authenticate GoHighLevel in the settings');
        }
    }

    public function slots(Request $request, $calendarId)
    {
        try {
            $helper = (new GohighlevelHelper());
            $user = auth()->user();
            $client = $helper->SubAccountClient($user);
            $access = $helper->subAccountAccess($user);
            if (!empty($client) && !empty($access)) {
                $startDate = now()->secondsSinceMidnight();
                $endDate = now()->addDay()->secondsUntilEndOfDay();
                $slots = $client->withVersion('2021-04-15')
                    ->make()->calendars()->slots($calendarId, $startDate, $endDate);

                return view('gohighlevel::calendars.slots', compact(
                    'slots'
                ));
            }
            return redirect()->route('settings.index')->with('error', 'Gohighlevel is not enabled for your account please contact the administrator');
        } catch (\MusheAbdulHakim\GoHighLevel\Exceptions\ErrorException $e) {
            return back()->with('error', 'Gohighlevel is not enabled for your account please contact the administrator');
        }
    }

    public function events()
    {
        try {

            $helper = (new GohighlevelHelper());
            $user = auth()->user();
            $client = $helper->SubAccountClient($user);
            $access = $helper->subAccountAccess($user);
            if (!empty($client)) {
                $startDate = now()->valueOf();
                $endDate = now()->addMonth()->valueOf();
                // $events = $ghl->ghlClient->withVersion('2021-04-15')
                //                 ->make()->calendars()
                //                 ->events()->get('1718072232319','1720664232319',$ghl->access->locationId,'',[
                //                     // 'endTime' => $endDate,
                //                     'userId' => $ghl->access->userId,
                //                 ]);
                $events = [];
                return view('gohighlevel::calendars.events', compact(
                    'events'
                ));
            }
            return redirect()->route('settings.index')->with('error', 'Gohighlevel is not enabled for your account please contact the administrator');
        } catch (\MusheAbdulHakim\GoHighLevel\Exceptions\ErrorException $e) {
            return back()->with('error', 'Token has expired please authenticate GoHighLevel in the settings');
        }
    }
}
