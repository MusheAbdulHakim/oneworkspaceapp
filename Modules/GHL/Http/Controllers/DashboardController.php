<?php

namespace Modules\GHL\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Modules\GHL\Traits\UserGHL;

class DashboardController extends Controller
{
    use UserGHL;

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try {
            $ghl = $this->initGHL();
            if (!empty($ghl)) {
                $locationId = $this->userGHL()->locationId;
                $contacts = $ghl->withVersion('2021-07-28')
                    ->make()
                    ->contacts()->list($locationId);
                $invoices = $ghl->withVersion('2021-07-28')
                    ->make()->invoices()
                    ->list($locationId, 'location', 100, 0);
                $funnels = $ghl->withVersion('2021-07-28')
                    ->make()->funnels()->list($locationId, [
                        'locationId' => $locationId
                    ]);

                $calendars = $ghl->withVersion('2021-04-15')
                    ->make()
                    ->calendars()
                    ->list($locationId);
                $start = now()->startOfWeek(Carbon::TUESDAY)->valueOf();
                $end = now()->endOfWeek(Carbon::MONDAY)->valueOf();
                // $events = $ghl->withVersion('2021-04-15')
                //                 ->make()->calendars()
                //                 ->events()->get($end, $start,$locationId,'',[
                //                     'userId' => $this->userGHL()->userId,
                //                     'endTime' => $end,
                //                 ]);
                return view('ghl::dashboard.dashboard', compact(
                    'contacts',
                    'invoices',
                    'calendars',
                    'funnels'
                ));
            }
            return redirect()->route('settings.index')->with('error', 'Please authenticate your ghl account to continue');
        } catch (\MusheAbdulHakim\GoHighLevel\Exceptions\ErrorException $e) {
            return back()->with('error', 'Token has expired please authenticate GoHighLevel in the settings');
        }
    }

    public function dashboard()
    {
        try {

            $ghl = $this->initGHL();
            if (!empty($ghl)) {
                $locationId = $this->userGHL()->locationId;
                $contacts = $ghl->withVersion('2021-07-28')
                    ->make()
                    ->contacts()->list($locationId);
                $invoices = $ghl->withVersion('2021-07-28')
                    ->make()->invoices()
                    ->list($locationId, 'location', 100, 0);
                $funnels = $ghl->withVersion('2021-07-28')
                    ->make()->funnels()->list($locationId, [
                        'locationId' => $locationId
                    ]);

                $calendars = $ghl->withVersion('2021-04-15')
                    ->make()
                    ->calendars()
                    ->list($locationId);
                $start = now()->startOfWeek(Carbon::TUESDAY)->valueOf();
                $end = now()->endOfWeek(Carbon::MONDAY)->valueOf();
                // $events = $ghl->withVersion('2021-04-15')
                //                 ->make()->calendars()
                //                 ->events()->get($end, $start,$locationId,'',[
                //                     'userId' => $this->userGHL()->userId,
                //                     'endTime' => $end,
                //                 ]);
                return view('ghl::dashboard.dashboard', compact(
                    'contacts',
                    'invoices',
                    'calendars',
                    'funnels'
                ));
            }
            return redirect()->route('settings.index')->with('error', 'Please authenticate your ghl account to continue');
        } catch (\MusheAbdulHakim\GoHighLevel\Exceptions\ErrorException $e) {
            return back()->with('error', 'Token has expired please authenticate GoHighLevel in the settings');
        }
    }
}
