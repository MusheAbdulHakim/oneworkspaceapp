<?php

namespace Modules\GoHighLevel\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\GoHighLevel\Helper\GohighlevelHelper;

class CampaignsController extends Controller
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
                $campaigns = $client->withVersion('2021-04-15')
                    ->make()
                    ->campaigns()->get($access->locationId);
                return view('gohighlevel::campaigns.index', compact(
                    'campaigns'
                ));
            }
            return redirect()->route('settings.index')->with('error', 'Please authenticate your ghl account to continue');
        } catch (\MusheAbdulHakim\GoHighLevel\Exceptions\ErrorException $e) {
            return back()->with('error', 'Token has expired please authenticate GoHighLevel in the settings');
        }
    }
}
