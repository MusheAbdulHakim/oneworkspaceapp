<?php
// This file use for handle company setting page

namespace Modules\GoHighLevel\Http\Controllers\Company;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($settings)
    {
        return view('gohighlevel::company.settings.index',compact('settings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }
}
