<?php

namespace Modules\Lead\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Lead\Entities\Lead;
use Modules\Lead\Entities\LeadStage;
use Modules\Lead\Entities\Pipeline;
use Modules\Lead\Events\CreateLeadStage;
use Modules\Lead\Events\DestroyLeadStage;
use Modules\Lead\Events\LeadStageChange;
use Modules\Lead\Events\UpdateLeadStage;
use Illuminate\Support\Facades\Auth;

class LeadStageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('leadstages manage')) {
            $lead_stages = LeadStage::select('lead_stages.*', 'pipelines.name as pipeline')
                ->join('pipelines', 'pipelines.id', '=', 'lead_stages.pipeline_id')
                ->where('pipelines.created_by', '=', creatorId())
                ->where('lead_stages.created_by', '=', creatorId())->where('lead_stages.workspace_id', '=', getActiveWorkSpace())
                ->orderBy('lead_stages.pipeline_id')
                ->orderBy('lead_stages.order')
                ->get();
            $pipelines   = [];

            foreach ($lead_stages as $lead_stage) {
                if (!array_key_exists($lead_stage->pipeline_id, $pipelines)) {
                    $pipelines[$lead_stage->pipeline_id]                = [];
                    $pipelines[$lead_stage->pipeline_id]['name']        = $lead_stage['pipeline'];
                    $pipelines[$lead_stage->pipeline_id]['lead_stages'] = [];
                }
                $pipelines[$lead_stage->pipeline_id]['lead_stages'][] = $lead_stage;
            }

            return view('lead::lead_stages.index')->with('pipelines', $pipelines);
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {

        if (Auth::user()->isAbleTo('leadstages create')) {
            $pipelines = Pipeline::where('created_by', '=', creatorId())->where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');

            return view('lead::lead_stages.create')->with('pipelines', $pipelines);
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('leadstages create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:20',
                    'pipeline_id' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('lead_stages.index')->with('error', $messages->first());
            }
            $lead_stage              = new LeadStage();
            $lead_stage->name        = $request->name;
            $lead_stage->pipeline_id = $request->pipeline_id;
            $lead_stage->created_by  = creatorId();
            $lead_stage->workspace_id  = getActiveWorkSpace();
            $lead_stage->save();

            event(new CreateLeadStage($request,$lead_stage));

            return redirect()->route('lead-stages.index')->with('success', __('Lead Stage successfully created!'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
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
    public function edit(LeadStage $leadStage)
    {
        if (Auth::user()->isAbleTo('leadstages edit')) {
            if ($leadStage->created_by == creatorId() && $leadStage->workspace_id == getActiveWorkSpace()) {
                $pipelines = Pipeline::where('created_by', '=', creatorId())->get()->pluck('name', 'id');

                return view('lead::lead_stages.edit', compact('leadStage', 'pipelines'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, LeadStage $leadStage)
    {
        if (Auth::user()->isAbleTo('leadstages edit')) {

            if ($leadStage->created_by == creatorId() && $leadStage->workspace_id == getActiveWorkSpace()) {

                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:20',
                        'pipeline_id' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('lead-stages.index')->with('error', $messages->first());
                }

                $leadStage->name        = $request->name;
                $leadStage->pipeline_id = $request->pipeline_id;
                $leadStage->save();

                event(new UpdateLeadStage($request,$leadStage));

                return redirect()->route('lead-stages.index')->with('success', __('Lead Stage successfully updated!'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(LeadStage $leadStage)
    {
        if (Auth::user()->isAbleTo('leadstages delete')) {
            $leads = Lead::where('stage_id', '=', $leadStage->id)->count();
            if ($leads == 0) {
                $leadStage->delete();

                event(new DestroyLeadStage($leads));

                return redirect()->route('lead-stages.index')->with('success', __('Lead Stage successfully deleted!'));
            } else {
                return redirect()->back()->with('error', 'Please remove Lead from stage:' . $leadStage->name);
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    public function order(Request $request)
    {
        $post = $request->all();
        foreach ($post['order'] as $key => $item) {
            $lead_stage        = LeadStage::where('id', '=', $item)->first();
            $lead_stage->order = $key;
            $lead_stage->save();

            event(new LeadStageChange($post,$lead_stage));

        }
    }
}
