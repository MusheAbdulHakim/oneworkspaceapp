<?php

namespace Modules\GHL\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\GHL\Traits\UserGHL;

class ContactsController extends Controller
{

    use UserGHL;

    public $apiVersion = '2021-07-28';

  public function index()
    {
        $ghl = $this->initGHL();
        if(!empty($ghl)){
            $contacts = $ghl->withVersion($this->apiVersion)
                    ->make()
                    ->contacts()->list($this->userGHL()->locationId);
            return view('ghl::contacts.index',compact(
                'contacts'
            ));
        }
        return redirect()->route('settings.index')->with('error','Please authenticate your ghl account to continue');
    }

    public function appointments(Request $request, $contactId){
        $ghl = $this->initGHL();
        if(!empty($ghl)){
            $appointments = $ghl->withVersion($this->apiVersion)
                                ->make()
                                ->contacts()
                                ->appointments()
                                ->contacts($contactId);
            return view('ghl::contacts.appointments',compact(
                'appointments'
            ));

        }
        return redirect()->route('settings.index')->with('error','Please authenticate your ghl account to continue');
    }

    public function notes(Request $request, $contactId){
        $ghl = $this->initGHL();
        if(!empty($ghl)){
            $slots = $ghl->withVersion($this->apiVersion)
                            ->make()->contacts()->notes()->list($contactId);

            return view('ghl::contacts.notes',compact(
                'slots'
            ));

        }
        return redirect()->route('settings.index')->with('error','Please authenticate your ghl account to continue');
    }

    public function tasks(Request $request, $contactId){
        $ghl = $this->initGHL();
        if(!empty($ghl)){

            $slots = $ghl->withVersion('2021-04-15')
                            ->make()->contacts()->tasks()->list($contactId);

            return view('ghl::contacts.tasks',compact(
                'slots'
            ));

        }
        return redirect()->route('settings.index')->with('error','Please authenticate your ghl account to continue');
    }

}
