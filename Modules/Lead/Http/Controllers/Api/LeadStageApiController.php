<?php

namespace Modules\Lead\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Lead\Entities\LeadUtility;
use Modules\Lead\Entities\LeadStage;
use Modules\Lead\Entities\Pipeline;

class LeadStageApiController extends Controller
{
   
    public function index(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                'workspace_id'  => 'required',
                'pipeline_id'  => 'required|exists:pipelines,id',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return response()->json(['status' => 0, 'message' => $messages->first()] , 403);
        }

        try{

            $objUser          = Auth::user();
            $pipelineId       = $request->pipeline_id;
            $currentWorkspace = $request->workspace_id;

            $pipeline =  Pipeline::where('workspace_id', $currentWorkspace)
                                        ->where('created_by', creatorId())
                                        ->where('id', $pipelineId)
                                        ->first();

            if($pipeline == null){
                return response()->json(['status' => 0, 'message' => 'Pipeline not found!!!'] , 403);
            }
                                        
            $lead_stages = LeadStage::select('lead_stages.*')
                                        ->join('pipelines', 'pipelines.id', '=', 'lead_stages.pipeline_id')
                                        ->where('lead_stages.workspace_id', $currentWorkspace)
                                        ->where('lead_stages.created_by', creatorId())
                                        ->where('lead_stages.pipeline_id', $pipelineId)
                                        ->orderBy('lead_stages.order')
                                        ->get();
                        
            if ($lead_stages->isEmpty()) {
                // No lead stages found for the specified pipeline
                return response()->json(['pipeline_name' => $pipeline->name, 'lead_stages' => []]);
            }

            $data = [
                'pipeline_name' => $pipeline->name,
                'lead_stages'   => $lead_stages->map(function ($leadStage) {
                                        return [
                                            'id'            => $leadStage->id,
                                            'name'          => $leadStage->name,
                                            'order'         => $leadStage->order,
                                        ];
                                    }),
            ];

            return response()->json(['status' => 1, 'data' => $data] , 200);

        } catch (\Exception $e) {
            return response()->json(['status'=> 0 ,'message' => 'something went wrong!!!']);
        } 
    }

    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                'name'              => 'required',
                'workspace_id'      => 'required|exists:work_spaces,id',
                'pipeline_id'       => 'required|exists:pipelines,id',
                'lead_stage_id'     => 'exists:lead_stages,id',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return response()->json(['status' => 0, 'message' => $messages->first()] , 403);
        }

        try{

            $objUser          = Auth::user();
            $pipelineId       = $request->pipeline_id;
            $currentWorkspace = $request->workspace_id;
            $leadStageId      = $request->lead_stage_id;

            if(empty($request->lead_stage_id))
            {
                $lead_stage                 = new LeadStage();
                $lead_stage->name           = $request->name;
                $lead_stage->pipeline_id    = $request->pipeline_id;
                $lead_stage->created_by     = creatorId();
                $lead_stage->workspace_id   = $currentWorkspace;
                $lead_stage->save();

                return response()->json(['status' => 1 , 'message' => 'Lead Stage Created Successfully.'] , 200);

            }else{

                $leadStage  =  LeadStage::where('workspace_id',$currentWorkspace)
                                            ->where('created_by',creatorId())
                                            ->where('pipeline_id',$pipelineId)
                                            ->where('id',$leadStageId)
                                            ->first();

               if($leadStage !== null){

                   $leadStage->name        = $request->name;
                   $leadStage->pipeline_id = $request->pipeline_id;
                   $leadStage->save();
   
                   return response()->json(['status' => 1 , 'message' => 'Lead Stage Updated Successfully.'] , 200);

               }else{

                   return response()->json(['status'=> 0 ,'message' => 'Lead Stage not found!!!']);
               }
            }

        } catch (\Exception $e) {
            return response()->json(['status'=> 0 ,'message' => 'something went wrong!!!']);
        } 
    }

    public function destroy(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                'workspace_id'      => 'required|exists:work_spaces,id',
                'pipeline_id'       => 'required|exists:pipelines,id',
                'lead_stage_id'     => 'required|exists:lead_stages,id',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return response()->json(['status' => 0, 'message' => $messages->first()] , 403);
        }

        try{
            $leadStage  = LeadStage::where('workspace_id',$currentWorkspace)
                                        ->where('created_by',creatorId())
                                        ->where('pipeline_id',$pipelineId)
                                        ->where('id',$leadStageId)
                                        ->first();

            if($leadStage !== null){

                $leads      = Lead::where('stage_id', '=', $leadStage->id)->count();

                if ($leads == 0) {

                    $leadStage->delete();
                    return response()->json(['status' => 1 , 'message' => 'Lead Stage successfully deleted.'] , 200);

                } else {
                    return redirect()->back()->with('error', 'Please remove Lead from stage:' . $leadStage->name);
                }

            }else{

                return response()->json(['status'=> 0 ,'message' => 'Lead Stage not found!!!']);
            }


        } catch (\Exception $e) {
            return response()->json(['status'=> 0 ,'message' => 'something went wrong!!!']);
        } 
    }
}
