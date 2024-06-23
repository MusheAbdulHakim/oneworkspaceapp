<?php

namespace Modules\GHL\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\GHL\Traits\UserGHL;

class FunnelsController extends Controller
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
                $funnels = $ghl->withVersion('2021-04-15')
                    ->make()->funnels()->list($this->userGHL()->locationId, [
                        'locationId' => $this->userGHL()->locationId
                    ])['funnels'];
                return view('ghl::funnels.index', compact(
                    'funnels'
                ));
            }
            return redirect()->route('settings.index')->with('error', 'Please authenticate your ghl account to continue');
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

            $ghl = $this->initGHL();
            if (!empty($ghl)) {
                $pages = $ghl->withVersion('2021-04-15')
                    ->make()
                    ->funnels()
                    ->pages($funnelId, $this->userGHL()->locationId, 20, 0);
                return view('ghl::funnels.pages', compact(
                    'pages'
                ));
            }
            return redirect()->route('settings.index')->with('error', 'Please authenticate your ghl account to continue');
        } catch (\Exception $e) {
            return back()->with('error', 'Token has expired please authenticate GoHighLevel in the settings');
        }
    }
}
