<?php
// This file use for handle company setting page

namespace Modules\PabblyConnect\Http\Controllers\Company;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\PabblyConnect\Entities\Pabbly;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($settings)
    {
        $pabbly_module = Pabbly::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->get();

        return view('pabblyconnect::company.settings.index',compact('pabbly_module'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }
}
