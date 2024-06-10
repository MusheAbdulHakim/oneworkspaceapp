<?php

namespace Modules\GHL\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CalendarController extends Controller
{
    public function index()
    {
        $ghlAccess = auth()->user()->ghl;
        $ghl = \MusheAbdulHakim\GoHighLevel\GoHighLevel::init($ghlAccess->access_token);
        $calendars = $ghl->withVersion('2021-04-15')
                        ->make()->calendars()->list($ghlAccess->locationId);
        return view('ghl::calendars.index',compact(
            'calendars'    
        ));
    }
}
