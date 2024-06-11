<?php

namespace Modules\GHL\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\GHL\Traits\UserGHL;

class CampaignsController extends Controller
{

    use UserGHL;

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $ghl = $this->initGHL();
        if(!empty($ghl)){
            $campaigns = $ghl->withVersion('2021-04-15')
                    ->make()
                    ->campaigns()->get($this->userGHL()->locationId);
            return view('ghl::campaigns.index',compact(
                'campaigns'
            ));
        }
        return redirect()->route('settings.index')->with('error','Please authenticate your ghl account to continue');
    }


}
