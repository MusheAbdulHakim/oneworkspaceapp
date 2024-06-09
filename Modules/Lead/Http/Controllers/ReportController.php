<?php

namespace Modules\Lead\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Lead\Entities\ClientDeal;
use Modules\Lead\Entities\Deal;
use Modules\Lead\Entities\Lead;
use Modules\Lead\Entities\Pipeline;
use Modules\Lead\Entities\Source;
use Modules\Lead\Entities\UserDeal;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function leadReport(Request $request)
    {
        if (Auth::user()->isAbleTo('lead report')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $allLeads           = Lead::where('created_by', '=', $creatorId)->where('workspace_id', $getActiveWorkSpace)->get();
            $user      = Auth::user();

            $leads = Lead::orderBy('id');
            $leads->where('created_by', $creatorId)->where('workspace_id', $getActiveWorkSpace);

            $user_week_lead = Lead::orderBy('created_at')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get()->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d');
            });
            $carbaoDay = Carbon::now()->startOfWeek(); //spesific day

            $weeks = [];
            for ($i = 0; $i < 7; $i++) {
                $weeks[$carbaoDay->startOfWeek()->addDay($i)->format('Y-m-d')] = 0; //push the current day and plus the mount of $i
            }
            foreach ($user_week_lead as $name => $leads) {
                $weeks[$name] = $leads->count();
            }

            $devicearray          = [];
            $devicearray['label'] = [];
            $devicearray['data']  = [];
            foreach ($weeks as $name => $leads) {
                $devicearray['label'][] = Carbon::parse($name)->format('l');
                $devicearray['data'][] = $leads;
            }

            $leads = $allLeads;
            $lead_source = Source::where('created_by', Auth::user()->id)->where('workspace_id', $getActiveWorkSpace)->get();

            $leadsourceName = [];
            $leadsourceeData = [];
            foreach ($lead_source as $lead_source_data) {
                $lead_source = lead::where('created_by', Auth::user()->id)->where('sources', $lead_source_data->id)->count();
                $leadsourceName[] = $lead_source_data->name;
                $leadsourceeData[] = $lead_source;
            }

            // monthly report

            $labels = [];
            $data   = [];


            if (!empty($request->start_month) && !empty($request->end_month)) {
                $start = strtotime($request->start_month);
                $end   = strtotime($request->end_month);
            } else {
                $start = strtotime(date('Y-01'));
                $end   = strtotime(date('Y-12'));
            }

            $leads = Lead::orderBy('id');
            $leads->where('date', '>=', date('Y-m-01', $start))->where('date', '<=', date('Y-m-t', $end));
            $leads->where('created_by', $creatorId);
            $leads = $leads->get();

            $currentdate = $start;

            while ($currentdate <= $end) {
                $month = date('m', $currentdate);

                $year  = date('Y');

                if (!empty($request->start_month)) {
                    $leadFilter = Lead::where('created_by', $creatorId)->where('workspace_id', $getActiveWorkSpace)->whereMonth('date', $request->start_month)->whereYear('date', $year)->get();
                } else {
                    $leadFilter = Lead::where('created_by', $creatorId)->where('workspace_id', $getActiveWorkSpace)->whereMonth('date', $month)->whereYear('date', $year)->get();
                }

                $data[]      = count($leadFilter);
                $labels[]    = date('M Y', $currentdate);
                $currentdate = strtotime('+1 month', $currentdate);

                if (!empty($request->start_month)) {
                    $cdate = '01-' . $request->start_month . '-' . $year;
                    $mstart = strtotime($cdate);
                    $labelss[]    = date('M Y', $mstart);

                    return response()->json(['data' => $data, 'name' => $labelss]);
                }
            }

            if (empty($request->start_month) && !empty($request->all())) {
                return response()->json(['data' => $data, 'name' => $labels]);
            }

            $filter['startDateRange'] = date('M-Y', $start);
            $filter['endDateRange']   = date('M-Y', $end);

            //staff report
            $leads = $allLeads;

            if ($request->type == "staff_repport") {
                $form_date = date('Y-m-d H:i:s', strtotime($request->From_Date));
                $to_date = date('Y-m-d H:i:s', strtotime($request->To_Date));
                if (!empty($request->From_Date) && !empty($request->To_Date)) {

                    $lead_user = User::where('created_by', '=', $creatorId)->where('type', '!=', 'client')->where('workspace_id', $getActiveWorkSpace)->get();
                    $leaduserName = [];
                    $leadusereData = [];
                    foreach ($lead_user as $lead_user_data) {
                        $lead_user = Lead::where('created_by', $creatorId)->where('user_id', $lead_user_data->id)->whereBetween('created_at', [$form_date, $to_date])->count();
                        $leaduserName[] = $lead_user_data->name;
                        $leadusereData[] = $lead_user;
                    }
                    return response()->json(['data' => $leadusereData, 'name' => $leaduserName]);
                }
            } else {
                $lead_user = User::where('created_by', '=', $creatorId)->where('type', '!=', 'client')->where('workspace_id', $getActiveWorkSpace)->get();
                $leaduserName = [];
                $leadusereData = [];
                foreach ($lead_user as $lead_user_data) {
                    $lead_user = Lead::where('created_by', Auth::user()->id)->where('user_id', $lead_user_data->id)->count();
                    $leaduserName[] = $lead_user_data->name;
                    $leadusereData[] = $lead_user;
                }
            }

            $leads = $allLeads;

            $lead_pipeline = Pipeline::where('created_by', Auth::user()->id)->where('workspace_id', $getActiveWorkSpace)->get();
            $leadpipelineName = [];
            $leadpipelineeData = [];
            foreach ($lead_pipeline as $lead_pipeline_data) {
                $lead_pipeline = lead::where('created_by', Auth::user()->id)->where('pipeline_id', $lead_pipeline_data->id)->count();
                $leadpipelineName[] = $lead_pipeline_data->name;
                $leadpipelineeData[] = $lead_pipeline;
            }

            return view('lead::report.lead', compact('devicearray', 'leadsourceName', 'leadsourceeData', 'labels', 'data', 'filter', 'leads', 'leaduserName', 'leadusereData', 'user', 'leadpipelineName', 'leadpipelineeData'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function dealReport(Request $request)
    {
        if (Auth::user()->isAbleTo('deal report')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $allDeals           = Deal::where('created_by', '=', $creatorId)->where('workspace_id', $getActiveWorkSpace)->get();
            $user      = Auth::user();
            $deals = Deal::orderBy('id');
            $deals->where('created_by', $creatorId);

            $user_week_deal = Deal::orderBy('created_at')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get()->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d');
            });

            $carbaoDay = Carbon::now()->startOfWeek(); //spesific day

            $weeks = [];
            for ($i = 0; $i < 7; $i++) {
                $weeks[$carbaoDay->startOfWeek()->addDay($i)->format('Y-m-d')] = 0; //push the current day and plus the mount of $i
            }
            foreach ($user_week_deal as $name => $deals) {
                $weeks[$name] = $deals->count();
            }

            $devicearray          = [];
            $devicearray['label'] = [];
            $devicearray['data']  = [];
            foreach ($weeks as $name => $deals) {
                $devicearray['label'][] = Carbon::parse($name)->format('l');
                $devicearray['data'][] = $deals;
            }
            //source
            $deals = $allDeals;

            $deals_source = Source::where('created_by', $creatorId)->where('workspace_id', $getActiveWorkSpace)->get();

            $dealsourceName = [];
            $dealsourceeData = [];
            foreach ($deals_source as $deals_source_data) {
                $deals_source = Deal::where('created_by', Auth::user()->id)->where('sources', $deals_source_data->id)->count();
                $dealsourceName[] = $deals_source_data->name;
                $dealsourceeData[] = $deals_source;
            }

            //staff
            if ($request->type == "deal_staff_repport") {
                $from_date = date('Y-m-d H:i:s', strtotime($request->From_Date));
                $to_date = date('Y-m-d H:i:s', strtotime($request->To_Date));

                if (!empty($request->From_Date) && !empty($request->To_Date)) {
                    $user_deal = User::where('created_by', $creatorId)->where('workspace_id', $getActiveWorkSpace)->emp()->get();
                    $dealUserData = [];
                    $dealUserName = [];
                    foreach ($user_deal as $user_deal_data) {

                        $user_deals = UserDeal::where('user_id', $user_deal_data->id)->whereBetween('created_at', [$from_date, $to_date])->count();
                        $dealUserName[] = $user_deal_data->name;

                        $dealUserData[] = $user_deals;
                    }
                    return response()->json(['data' => $dealUserData, 'name' => $dealUserName]);
                }
            } else {
                $user_deal = User::where('created_by', $creatorId)->where('workspace_id', $getActiveWorkSpace)->emp()->get();
                $dealUserData = [];
                $dealUserName = [];
                foreach ($user_deal as $user_deal_data) {
                    $user_deals = UserDeal::where('user_id', $user_deal_data->id)->count();

                    $dealUserName[] = $user_deal_data->name;
                    $dealUserData[] = $user_deals;
                }
            }

            //pipelines
            $deals = $allDeals;

            $deal_pipeline = Pipeline::where('created_by', $creatorId)->where('workspace_id', $getActiveWorkSpace)->get();

            $dealpipelineName = [];
            $dealpipelineeData = [];
            foreach ($deal_pipeline as $deal_pipeline_data) {
                $deal_pipeline = Deal::where('created_by', Auth::user()->id)->where('pipeline_id', $deal_pipeline_data->id)->count();
                $dealpipelineName[] = $deal_pipeline_data->name;
                $dealpipelineeData[] = $deal_pipeline;
            }

            //end

            if ($request->type == "client_repport") {

                $from_date1 = date('Y-m-d H:i:s', strtotime($request->from_date));
                $to_date1 = date('Y-m-d H:i:s', strtotime($request->to_date));
                if (!empty($request->from_date) && !empty($request->to_date)) {
                    $client_deal = User::where('created_by', '=', $creatorId)->where('type', '=', 'client')->where('workspace_id', $getActiveWorkSpace)->get();
                    $dealClientData = [];
                    $dealClientName = [];
                    foreach ($client_deal as $client_deal_data) {

                        $deals_client = ClientDeal::where('client_id', $client_deal_data->id)->whereBetween('created_at', [$from_date1, $to_date1])->count();
                        $dealClientName[] = $client_deal_data->name;
                        $dealClientData[] = $deals_client;
                    }
                    return response()->json(['data' => $dealClientData, 'name' =>  $dealClientName]);
                }
            } else {
                $client_deal = User::where('created_by', '=', $creatorId)->where('type', '=', 'client')->where('workspace_id', $getActiveWorkSpace)->get();
                $dealClientName = [];
                $dealClientData = [];
                foreach ($client_deal as $client_deal_data) {
                    $deals_client = ClientDeal::where('client_id', $client_deal_data->id)->count();
                    $dealClientName[] = $client_deal_data->name;
                    $dealClientData[] = $deals_client;
                }
            }
            /*month*/

            $labels = [];
            $data   = [];


            if (!empty($request->start_month) && !empty($request->end_month)) {
                $start = strtotime($request->start_month);
                $end   = strtotime($request->end_month);
            } else {
                $start = strtotime(date('Y-01'));
                $end   = strtotime(date('Y-12'));
            }

            $deals = Deal::orderBy('id');
            $deals->where('created_at', '>=', date('Y-m-01', $start))->where('created_at', '<=', date('Y-m-t', $end));
            $deals->where('created_by', $creatorId)->where('workspace_id', $getActiveWorkSpace);
            $deals = $deals->get();

            $currentdate = $start;
            while ($currentdate <= $end) {
                $month = date('m', $currentdate);

                $year  = date('Y');

                if (!empty($request->start_month)) {
                    $dealFilter = Deal::where('created_by', $creatorId)->whereMonth('created_at', $request->start_month)->whereYear('created_at', $year)->get();
                } else {
                    $dealFilter = Deal::where('created_by', $creatorId)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
                }

                $data[]      = count($dealFilter);
                $labels[]    = date('M Y', $currentdate);
                $currentdate = strtotime('+1 month', $currentdate);

                if (!empty($request->start_month)) {
                    $cdate = '01-' . $request->start_month . '-' . $year;
                    $mstart = strtotime($cdate);
                    $labelss[]    = date('M Y', $mstart);

                    return response()->json(['data' => $data, 'name' => $labelss]);
                }
            }

            if (empty($request->start_month) && !empty($request->all())) {
                return response()->json(['data' => $data, 'name' => $labels]);
            }

            $filter['startDateRange'] = date('M-Y', $start);
            $filter['endDateRange']   = date('M-Y', $end);

            return view('lead::report.deal', compact('devicearray', 'dealsourceName', 'dealsourceeData', 'dealUserData', 'dealUserName', 'dealpipelineName', 'dealpipelineeData', 'data', 'labels', 'dealClientName', 'dealClientData'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }



    public function index()
    {
        return view('lead::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('lead::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('lead::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('lead::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
