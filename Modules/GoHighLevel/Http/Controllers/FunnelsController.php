<?php

namespace Modules\GoHighLevel\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\GoHighLevel\Helper\GohighlevelHelper;

class FunnelsController extends Controller
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
            if (!empty($client) && !empty($access)) {
                $funnels = $client->withVersion('2021-04-15')
                    ->make()->funnel()->list($access->locationId);
                return view('gohighlevel::funnels.index', compact(
                    'funnels'
                ));
            }
            return redirect()->route('settings.index')->with('error', 'Gohighlevel is not enabled for your account please contact the administrator');
        } catch (\Exception $e) {
            return back()->with('error', 'Token has expired please authenticate GoHighLevel in the settings');
        }
    }

    /*
     * Display a listing of the resource.
     * @return Renderable
     */
    public function pages(Request $request, $funnelId)
    {
        try {

            $helper = (new GohighlevelHelper());
            $user = auth()->user();
            $client = $helper->SubAccountClient($user);
            $access = $helper->subAccountAccess($user);
            if (!empty($client) && !empty($access)) {
                $pages = $client->withVersion('2021-04-15')
                    ->make()
                    ->funnel()
                    ->pages($funnelId, $access->locationId, 20, 0);
                return view('gohighlevel::funnels.pages', compact(
                    'pages'
                ));
            }
            return redirect()->route('settings.index')->with('error', 'Gohighlevel is not enabled for your account please contact the administrator');
        } catch (\Exception $e) {
            return back()->with('error', 'Token has expired please authenticate GoHighLevel in the settings');
        }
    }
}
