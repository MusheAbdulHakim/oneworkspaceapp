<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmbedsController extends Controller
{

    public function strategy(){
        return view('embed.strategy');
    }

    public function integrations(){
        return view('embed.app-integration');
    }

    public function onboarding(){
        return view('embed.onboarding');
    }
}
