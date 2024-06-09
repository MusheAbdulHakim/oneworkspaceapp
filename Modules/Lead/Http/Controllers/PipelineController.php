<?php

namespace Modules\Lead\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Lead\Entities\ClientDeal;
use Modules\Lead\Entities\Deal;
use Modules\Lead\Entities\DealDiscussion;
use Modules\Lead\Entities\DealFile;
use Modules\Lead\Entities\DealTask;
use Modules\Lead\Entities\LeadUtility;
use Modules\Lead\Entities\Pipeline;
use Modules\Lead\Entities\UserDeal;
use Modules\Lead\Events\CreatePipeline;
use Modules\Lead\Events\DestroyPipeline;
use Modules\Lead\Events\UpdatePipeline;
use Modules\Taskly\Entities\ActivityLog;
use Illuminate\Support\Facades\Auth;

class PipelineController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(Auth::user()->isAbleTo('pipeline manage'))
        {
            $pipelines = Pipeline::where('created_by', '=', creatorId())->where('workspace_id', '=', getActiveWorkSpace())->get();
            return view('lead::pipelines.index',compact('pipelines'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('lead::pipelines.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if(Auth::user()->isAbleTo('pipeline create'))
        {

            $validator = \Validator::make(
                $request->all(), [
                    'name' => 'required|max:20',
                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('pipelines.index')->with('error', $messages->first());
            }

            $pipeline             = new Pipeline();
            $pipeline->name       = $request->name;
            $pipeline->created_by = creatorId();
            $pipeline->workspace_id = getActiveWorkSpace();
            $pipeline->save();

            event(new CreatePipeline($request,$pipeline));

            return redirect()->route('pipelines.index')->with('success', __('Pipeline successfully created!'));
        }
        else
        {
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
    public function edit(Pipeline $pipeline)
    {
        if(Auth::user()->isAbleTo('pipeline edit'))
        {
            if($pipeline->created_by == creatorId() && $pipeline->workspace_id == getActiveWorkSpace())
            {
                return view('lead::pipelines.edit', compact('pipeline'));
            }
            else
            {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request,  Pipeline $pipeline)
    {
        if(Auth::user()->isAbleTo('pipeline edit'))
        {

            if($pipeline->created_by == creatorId() && $pipeline->workspace_id == getActiveWorkSpace())
            {

                $validator = \Validator::make(
                    $request->all(), [
                        'name' => 'required|max:20',
                    ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('pipelines.index')->with('error', $messages->first());
                }

                $pipeline->name = $request->name;
                $pipeline->save();

                event(new UpdatePipeline($request,$pipeline));

                return redirect()->route('pipelines.index')->with('success', __('Pipeline successfully updated!'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Pipeline $pipeline)
    {
        if(Auth::user()->isAbleTo('pipeline delete'))
        {

            if(count($pipeline->dealStages) == 0)
            {
                foreach($pipeline->dealStages as $dealStage)
                {
                    $deals = Deal::where('pipeline_id', '=', $pipeline->id)->where('stage_id', '=', $dealStage->id)->get();
                    foreach($deals as $deal)
                    {
                        DealDiscussion::where('deal_id', '=', $deal->id)->delete();
                        DealFile::where('deal_id', '=', $deal->id)->delete();
                        ClientDeal::where('deal_id', '=', $deal->id)->delete();
                        UserDeal::where('deal_id', '=', $deal->id)->delete();
                        DealTask::where('deal_id', '=', $deal->id)->delete();
                        ActivityLog::where('deal_id', '=', $deal->id)->delete();

                        $deal->delete();
                    }

                    $dealStage->delete();
                }

                $pipeline->delete();

                event(new DestroyPipeline($pipeline));

                return redirect()->route('pipelines.index')->with('success', __('Pipeline successfully deleted!'));
            }
            else
            {
                return redirect()->route('pipelines.index')->with('error', __('There are some Stages and Deals on Pipeline, please remove it first!'));
            }

        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
