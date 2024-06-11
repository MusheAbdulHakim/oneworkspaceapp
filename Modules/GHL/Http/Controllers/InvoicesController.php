<?php

namespace Modules\GHL\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $ghlAccess = auth()->user()->ghl;
        if(!empty($ghlAccess)){

            $ghl = \MusheAbdulHakim\GoHighLevel\GoHighLevel::init($ghlAccess->access_token);
            $invoices = $ghl->withVersion('2021-07-28')
                            ->make()->invoices()->list($ghlAccess->locationId,'location',200,0);
            return view('ghl::invoices.index',compact(
                'invoices'
            ));
        }
        return redirect()->route('settings.index')->with('error','Please authenticate your ghl account to continue');
    }


}
