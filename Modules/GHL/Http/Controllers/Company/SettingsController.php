<?php
// This file use for handle company setting page

namespace Modules\GHL\Http\Controllers\Company;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($settings)
    {
        $userGhl = auth()->user()->ghl;
        return view('ghl::company.settings.index',compact('settings','userGhl'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }
}
