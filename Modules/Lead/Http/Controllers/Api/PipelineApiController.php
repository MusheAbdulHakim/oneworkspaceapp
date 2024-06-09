<?php

namespace Modules\Lead\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Lead\Entities\Pipeline;
use Modules\Lead\Entities\LeadUtility;
use Modules\Lead\Entities\LeadStage;
use Modules\Lead\Entities\DealStage;
use Illuminate\Support\Facades\Auth;

class PipelineApiController extends Controller
{
  
    public function index(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                'workspace_id'  => 'required',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return response()->json(['status' => 0, 'message' => $messages->first()] , 403);
        }

        try{    

            $objUser          = Auth::user();
            $currentWorkspace = $request->workspace_id;

            $pipelines = Pipeline::where('created_by', '=', creatorId())
                                    ->where('workspace_id', '=', $currentWorkspace)
                                    ->get()->map(function($pipeline){
                                        return [
                                            'id'        => $pipeline->id,
                                            'name'      => $pipeline->name,
                                            'stages'    => $pipeline->leadStages->map(function($stage) use($pipeline)  {
                                                    return [
                                                        'id'            => $stage->id,
                                                        'name'          => $stage->name,
                                                        'order'         => $stage->order,
                                                    ];
                                            }),
                                        ];
                                    });


            return response()->json(['status' => 1,'data'  => $pipelines]);

        } catch (\Exception $e) {
            return response()->json(['status' => 0 , 'message' => 'Something went wrong!!!']);
        } 
    }

    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                'name'          => 'required|max:20',
                'workspace_id'  => 'required|exists:work_spaces,id',
                'pipeline_id'   => 'exists:pipelines,id',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return response()->json(['status' => 0, 'message' => $messages->first()] , 403);
        }

        try{

            $objUser          = Auth::user();
            $currentWorkspace = $request->workspace_id;

            $lead_stages = [
                "Draft",
                "Sent",
                "Open",
                "Revised",
                "Declined",
                "Accepted",
            ];

            $deal_stages = [
                'Initial Contact',
                'Qualification',
                'Meeting',
                'Proposal',
                'Close',
            ];

            if(empty($request->pipeline_id)){

                $pipeline               = new Pipeline();
                $pipeline->name         = $request->name;
                $pipeline->created_by   = creatorId();
                $pipeline->workspace_id = $currentWorkspace;
                $pipeline->save();
    
                foreach($lead_stages as $lead_stage)
                {
                    $check = LeadStage::where('name',$lead_stage)
                                                ->where('workspace_id',$currentWorkspace)
                                                ->where('created_by',creatorId())
                                                ->where('pipeline_id',$pipeline->id)
                                                ->exists();
                   
                    if(!$check){
                        $leadstage = new LeadStage();
                        $leadstage->name            = $lead_stage;
                        $leadstage->pipeline_id     = $pipeline->id;
                        $leadstage->order           = 0;
                        $leadstage->workspace_id    = $currentWorkspace;
                        $leadstage->created_by      = creatorId();
                        $leadstage->save();
                    }
                }

                foreach($deal_stages as $deal_stage)
                {
                    $check = DealStage::where('name',$deal_stage)
                                                ->where('workspace_id',$currentWorkspace)
                                                ->where('created_by',creatorId())
                                                ->where('pipeline_id',$pipeline->id)
                                                ->exists();

                    if(!$check){
                        $dealstage = new DealStage();
                        $dealstage->name            = $deal_stage;
                        $dealstage->pipeline_id     = $pipeline->id;
                        $dealstage->order           = 0;
                        $dealstage->workspace_id    = $currentWorkspace;
                        $dealstage->created_by      = creatorId();
                        $dealstage->save();
                    }
                }
                
                return response()->json(['status' => 1 , 'message' => 'Pipeline Created Successfully.'] , 200);

            }else{

                $pipeline               = Pipeline::where('created_by', '=', creatorId())
                                                    ->where('workspace_id', '=', $currentWorkspace)
                                                    ->where('id', '=', $request->pipeline_id)
                                                    ->first();
                if($pipeline !== null){

                    $pipeline->name         = $request->name;
                    $pipeline->save();

                }else{

                    return response()->json(['status'=> 0 ,'message' => 'Pipeline not found!!!']);
                }


                return response()->json(['status' => 1, 'message' => 'Pipeline Updated Successfully.'] , 200);
            }

        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'message' => 'Something went wrong!!!']);
        } 
    }

    public function destroy(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                'workspace_id'  => 'required',
                'pipeline_id'   => 'exists:pipelines,id',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return response()->json(['status' => 0, 'message' => $messages->first()] , 403);
        }

        $tables = [
            'pipelines',
            'deals',
            'leads',
            'deal_stages',
            'deal_discussions',
            'deal_files',
            'deal_tasks',
            'user_deals',
            'client_deals',
            'deal_activity_logs',
        ];

        try{

            DB::table($table)->where($column, $id)->delete();

        } catch (\Exception $e) {
            return response()->json(['status'=> 0 ,'message' => 'something went wrong!!!']);
        } 
    }
}
