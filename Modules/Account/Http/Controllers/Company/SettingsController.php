<?php
// This file use for handle company setting page

namespace Modules\Account\Http\Controllers\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($settings)
    {
        return view('account::company.settings.index',compact('settings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function bankAccount($settings)
    {

        $accounts = DB::table('bank_accounts')
        ->select('bank_accounts.*', 'chart_of_accounts.name as chart_account_name')
        ->leftJoin('chart_of_accounts', 'bank_accounts.chart_account_id', '=', 'chart_of_accounts.id')
        ->where('bank_accounts.workspace', getActiveWorkSpace())
        ->get();

        return view('account::company.settings.bank_account',compact('settings','accounts'));

    }
}
