<?php

namespace App\Http\Controllers;

use App\Models\PinnedApp;
use Illuminate\Http\Request;

class PinnedAppsController extends Controller
{

    public function index(){

    }

    public function store(Request $request){

    }

    public function delete(Request $request){
        PinnedApp::findOrFail($request->id)->delete();

    }
}
