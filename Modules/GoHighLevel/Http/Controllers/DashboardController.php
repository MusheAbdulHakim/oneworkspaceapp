<?php

namespace Modules\GoHighLevel\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Modules\GoHighLevel\Helper\GohighlevelHelper;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try {
            $helper = (new GohighlevelHelper());
            $user = auth()->user();
            $client = $helper->SubAccountClient($user);
            $access = $helper->subAccountAccess($user);
            if (!empty($client)) {
                $locationId = $access->locationId;
                $contacts = $client->withVersion('2021-07-28')
                    ->make()
                    ->contacts()->list($locationId);
                $invoices = $client->withVersion('2021-07-28')
                    ->make()->invoices()
                    ->list($locationId, 'location', 100, 0);
                $funnels = $client->withVersion('2021-07-28')
                    ->make()->funnel()->list($locationId, [
                        'locationId' => $locationId
                    ]);

                $calendars = $client->withVersion('2021-04-15')
                    ->make()
                    ->calendars()
                    ->list($locationId);
                $start = now()->startOfWeek(Carbon::TUESDAY)->valueOf();
                $end = now()->endOfWeek(Carbon::MONDAY)->valueOf();
                return view('gohighlevel::dashboard.dashboard', compact(
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
            $helper = (new GohighlevelHelper());
            $user = auth()->user();
            $client = $helper->SubAccountClient($user);
            $access = $helper->subAccountAccess($user);
            if (!empty($client) && !empty($access)) {
                $locationId = $access->locationId;
                $contacts = $client->withVersion('2021-07-28')
                    ->make()
                    ->contacts()->list($locationId);
                $invoices = $client->withVersion('2021-07-28')
                    ->make()->invoices()
                    ->list($locationId, 'location', 100, 0);
                $funnels = $client->withVersion('2021-07-28')
                    ->make()->funnel()->list($locationId, [
                        'locationId' => $locationId
                    ]);

                $calendars = $client->withVersion('2021-04-15')
                    ->make()
                    ->calendars()
                    ->list($locationId);
                $start = now()->startOfWeek(Carbon::TUESDAY)->valueOf();
                $end = now()->endOfWeek(Carbon::MONDAY)->valueOf();
                return view('gohighlevel::dashboard.dashboard', compact(
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
