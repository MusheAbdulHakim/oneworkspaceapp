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

    public function marketplace(){
        return view("embed.marketplace");
    }
    
    public function marketing(){
        return view("embed.marketing");
    }
    
    public function marketingHub(){
        return view("embed.marketing-hub");
    }

    public function community(){
        return view('embed.community');
    }

    public function processAutomation(){
        return view('embed.process-automation');
    }

    public function knowledgebase(){
        return view('embed.knowledgebase');
    }

    public function customerSuccess(){
        return view('embed.customsuccess');
    }
}
