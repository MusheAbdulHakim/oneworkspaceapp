<?php

namespace Modules\Lead\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Lead\Entities\Deal;
use Modules\Lead\Entities\DealStage;
use Modules\Lead\Entities\Pipeline;
use Modules\Lead\Events\CreateDealStage;
use Modules\Lead\Events\DealStageChange;
use Modules\Lead\Events\DestroyDealStage;
use Modules\Lead\Events\UpdateDealStage;
use Illuminate\Support\Facades\Auth;

class DealStageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('dealstages manage')) {
            $stages    = DealStage::select('deal_stages.*', 'pipelines.name as pipeline')
                ->join('pipelines', 'pipelines.id', '=', 'deal_stages.pipeline_id')
                ->where('pipelines.created_by', '=', creatorId())
                ->where('deal_stages.created_by', '=', creatorId())
                ->orderBy('deal_stages.pipeline_id')->where('deal_stages.workspace_id', getActiveWorkSpace())
                ->orderBy('deal_stages.order')
                ->get();
            $pipelines = [];

            foreach ($stages as $stage) {
                if (!array_key_exists($stage->pipeline_id, $pipelines)) {
                    $pipelines[$stage->pipeline_id]           = [];
                    $pipelines[$stage->pipeline_id]['name']   = $stage['pipeline'];
                    $pipelines[$stage->pipeline_id]['stages'] = [];
                }
                $pipelines[$stage->pipeline_id]['stages'][] = $stage;
            }

            return view('lead::deal_stages.index')->with('pipelines', $pipelines);
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
        if (Auth::user()->isAbleTo('dealstages create')) {
            $pipelines = Pipeline::where('created_by', '=', creatorId())->where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');

            return view('lead::deal_stages.create')->with('pipelines', $pipelines);
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
        if (Auth::user()->isAbleTo('dealstages create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:20',
                    'pipeline_id' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('stages.index')->with('error', $messages->first());
            }
            $stage              = new DealStage();
            $stage->name        = $request->name;
            $stage->pipeline_id = $request->pipeline_id;
            $stage->created_by  = creatorId();
            $stage->workspace_id = getActiveWorkSpace();
            $stage->save();

            event(new CreateDealStage($request,$stage));

            return redirect()->route('deal-stages.index')->with('success', __('Deal Stage successfully created!'));
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
    public function edit(DealStage $dealStage)
    {

        if (Auth::user()->isAbleTo('dealstages edit')) {
            if ($dealStage->created_by == creatorId() && $dealStage->workspace_id == getActiveWorkSpace()) {
                $pipelines = Pipeline::where('created_by', '=', creatorId())->where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');

                return view('lead::deal_stages.edit', compact('dealStage', 'pipelines'));
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
    public function update(Request $request, DealStage $dealStage)
    {
        if (Auth::user()->isAbleTo('dealstages edit')) {

            if ($dealStage->created_by == creatorId() && $dealStage->workspace_id == getActiveWorkSpace()) {

                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:20',
                        'pipeline_id' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('deal-stages.index')->with('error', $messages->first());
                }

                $dealStage->name        = $request->name;
                $dealStage->pipeline_id = $request->pipeline_id;
                $dealStage->save();

                event(new UpdateDealStage($request,$dealStage));

                return redirect()->route('deal-stages.index')->with('success', __('Deal Stage successfully updated!'));
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
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('dealstages delete')) {
            $stage = DealStage::find($id);
            if ($stage->created_by == creatorId() && $stage->workspace_id == getActiveWorkSpace()) {
                $deals = Deal::where('stage_id', '=', $stage->id)->where('created_by', '=', $stage->created_by)->count();

                if ($deals == 0) {
                    $stage->delete();

                    event(new DestroyDealStage($stage));

                    return redirect()->route('deal-stages.index')->with('success', __('Deal Stage successfully deleted!'));
                } else {
                    return redirect()->route('deal-stages.index')->with('error', __('There are some deals on stage, please remove it first!'));
                }
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    public function order(Request $request)
    {
        $post = $request->all();
        foreach ($post['order'] as $key => $item) {
            $stage        = DealStage::where('id', '=', $item)->first();
            $stage->order = $key;
            $stage->save();

            event(new DealStageChange($post,$stage));

        }
    }

    public function json(Request $request)
    {
        $stage = new DealStage();
        if ($request->pipeline_id) {
            $stage = $stage->where('pipeline_id', '=', $request->pipeline_id);
            $stage = $stage->get()->pluck('name', 'id');
        } else {
            $stage = [];
        }

        return response()->json($stage);
    }
}
