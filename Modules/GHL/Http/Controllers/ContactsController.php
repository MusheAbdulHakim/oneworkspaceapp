<?php

namespace Modules\GHL\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ContactsController extends Controller
{


  public function index()
    {
        $ghlAccess = auth()->user()->ghl;
        if(!empty($ghlAccess)){
            $ghl = \MusheAbdulHakim\GoHighLevel\GoHighLevel::init($ghlAccess->access_token);
            $contacts = $ghl->withVersion('2021-07-28')
                    ->make()
                    ->contacts()->list($ghlAccess->locationId);
            return view('ghl::contacts.index',compact(
                'contacts'
            ));
        }
        return back()->with('error','Please authenticate your ghl account to continue');
    }

}
