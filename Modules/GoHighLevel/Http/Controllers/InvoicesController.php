<?php

namespace Modules\GoHighLevel\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\GoHighLevel\Helper\GohighlevelHelper;

class InvoicesController extends Controller
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
            if (!empty($access) && !empty($client)) {

                $invoices = $client->withVersion('2021-07-28')
                    ->make()->invoices()->list($access->locationId, 'location', 200, 0);
                return view('gohighlevel::invoices.index', compact(
                    'invoices'
                ));
            }
            return redirect()->route('settings.index')->with('error', 'Please authenticate your ghl account to continue');
        } catch (\MusheAbdulHakim\GoHighLevel\Exceptions\ErrorException $e) {
            return back()->with('error', 'Token has expired please authenticate GoHighLevel in the settings');
        }
    }
}
