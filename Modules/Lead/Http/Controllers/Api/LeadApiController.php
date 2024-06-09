<?php

namespace Modules\Lead\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Lead\Entities\Pipeline;
use Modules\Lead\Entities\LeadUtility;
use Modules\Lead\Entities\LeadStage;
use Modules\Lead\Entities\DealStage;
use Modules\Lead\Entities\Lead;
use Illuminate\Support\Facades\Auth;
use Modules\Lead\Entities\UserLead;
use Modules\Lead\Entities\LeadActivityLog;
use Modules\Lead\Entities\LeadCall;
use Modules\Lead\Entities\LeadDiscussion;
use Modules\Lead\Entities\LeadEmail;
use Modules\Lead\Entities\LeadFile;
use Modules\Lead\Entities\LeadTask;

class LeadApiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function leadboard(Request $request)
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

            $pipeline = Pipeline::where('created_by', '=', creatorId())
                                    ->where('workspace_id', '=', $currentWorkspace)
                                    ->where('id', '=', $pipelineId)
                                    ->first();

            $leadStages = $pipeline->leadStages->map(function($stage){
                                                        return (object) [
                                                            'id' => $stage->id,
                                                            'name' => $stage->name,
                                                            'order' => $stage->order,
                                                        ];
                                                    });;

            $data = [];
            foreach ($leadStages as $key => $stage)
            {
                $lead = Lead::where('created_by', '=', creatorId())
                                ->where('workspace_id', $currentWorkspace)
                                ->where('pipeline_id', $pipelineId)
                                ->where('stage_id', $stage->id)
                                ->get();

                $stage->leads =  $lead->map(function($lead)use($key ,$leadStages){

                    return [
                        'id'                => $lead->id,
                        'name'              => $lead->name,
                        'order'             => $lead->order,
                        'previous_stage'    => isset($leadStages[$key-1]) ? $leadStages[$key-1]->id : 0,
                        'current_stage'     => $leadStages[$key]->id,
                        'next_stage'        => isset($leadStages[$key+1]) ? $leadStages[$key+1]->id : 0,
						'follow_up_date'    => $lead->follow_up_date,
						'total_tasks'       => $lead->tasks->count() .'/'. $lead->tasks->where('status',0)->count()  ,
						'total_products'    => !empty($lead->products) ? count(explode(',', $lead->products)) : 0,
						'total_sources'    => !empty($lead->sources) ? count(explode(',', $lead->sources)) : 0,
                        'labels'            => $lead->labels()?$lead->labels()->map(function($label){
                                                    return [
                                                        'id'    => $label->id,
                                                        'name'  => $label->name,
                                                        'color' => $label->color,
                                                    ]   ;
                                                }) : [],
                        'users'             => $lead->users->map(function($user){
                                                    return [
                                                        'id'        => $user->id,
                                                        'name'      => $user->name,
                                                        'avatar'    => check_file($user->avatar) ? get_file($user->avatar) : get_file('uploads/users-avatar/avatar.png'),
                                                    ];
                                                }),
                    ];
                });
            }

            return response()->json(['status' => 1, 'data' => $leadStages] , 200);

        } catch (\Exception $e) {
            return response()->json(['status'=> 0 ,'message' => 'something went wrong!!!']);
        }
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
        $validator = \Validator::make(
            $request->all(), [
                'workspace_id'  => 'required|exists:work_spaces,id',
                'pipeline_id'   => 'required|exists:pipelines,id',
                'phone'         => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                'subject'       => 'required',
                'name'          => 'required',
                'email'         => 'required',
                'follow_up_date'=> 'required|date_format:Y-m-d',
                'lead_id'       => 'exists:leads,id',
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

            // Default Field Value
            $pipeline = Pipeline::where('created_by', '=', creatorId())
                                    ->where('workspace_id', $currentWorkspace)
                                    ->where('id', $pipelineId)
                                    ->first();

            if (!empty($pipeline)) {
                $stage = LeadStage::where('pipeline_id', '=', $pipelineId)
                                        ->where('workspace_id', $currentWorkspace)
                                        ->first();
            } else {
                return response()->json(['status' => 0, 'message' => 'Please Create Pipeline.'] , 403);
            }

            if (empty($stage)) {
                return response()->json(['status' => 0, 'message' => 'Please Create Stage for This Pipeline'] , 403);
            } else {

                if(empty($request->lead_id))
                {
                    $lead                 = new Lead();
                    $lead->name           = $request->name;
                    $lead->email          = $request->email;
                    $lead->subject        = $request->subject;
                    $lead->user_id        = $request->user_id;
                    $lead->pipeline_id    = $pipelineId;
                    $lead->stage_id       = $stage->id;
                    $lead->phone          = $request->phone;
                    $lead->created_by     = creatorId();
                    $lead->workspace_id   = $currentWorkspace;
                    $lead->date           = date('Y-m-d');
                    $lead->follow_up_date = $request->follow_up_date;
                    $lead->save();

                    if (Auth::user()->hasRole('company')) {
                        $usrLeads = [
                            $objUser->id,
                            $request->user_id,
                        ];
                    } else {
                        $usrLeads = [
                            $creatorId,
                            $request->user_id,
                        ];
                    }

                    foreach ($usrLeads as $usrLead) {
                        UserLead::create(
                            [
                                'user_id' => $usrLead,
                                'lead_id' => $lead->id,
                            ]
                        );
                    }

                    return response()->json(['status' => 1 , 'message' => 'Lead created Successfully.'] , 200);
                }else{

                    $lead                 = Lead::where('created_by', '=', creatorId())
                                                    ->where('workspace_id', $currentWorkspace)
                                                    ->where('id',$request->lead_id )
                                                    ->first();

                    if($lead !== null){

                        $lead->name           = $request->name;
                        $lead->email          = $request->email;
                        $lead->subject        = $request->subject;
                        $lead->user_id        = $request->user_id;
                        $lead->pipeline_id    = $pipelineId;
                        $lead->stage_id       = $stage->id;
                        $lead->phone          = $request->phone;
                        $lead->follow_up_date = $request->follow_up_date;
                        $lead->save();

                    }else{

                        return response()->json(['status'=> 0 ,'message' => 'Lead not found!!!']);
                    }

                    return response()->json(['status' => 1 , 'message' => 'Lead updated Successfully.'] , 200);
                }
            }

            return response()->json(['status' => 1, 'data' => $pipeline] , 200);

        } catch (\Exception $e) {
            return response()->json(['status'=> 0 ,'message' => 'something went wrong!!!']);
        }
    }

    public function leadDetails(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                'workspace_id'  => 'required|exists:work_spaces,id',
                'pipeline_id'   => 'required|exists:pipelines,id',
                'lead_id'       => 'required|exists:leads,id',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return response()->json(['status' => 0, 'message' => $messages->first()] , 403);
        }

        $objUser          = Auth::user();
        $pipelineId       = $request->pipeline_id;
        $currentWorkspace = $request->workspace_id;
        $leadId           = $request->lead_id;


        try{

            $lead = Lead::where('created_by', '=', creatorId())->where('workspace_id', $currentWorkspace)->where('pipeline_id', $pipelineId)->where('id', $leadId)->first();

            $data = [
                'id'                => $lead->id,
                'name'              => $lead->name,
                'email'             => $lead->email,
                'subject'           => $lead->subject,
                'pipeline_id'       => $lead->pipeline_id,
                'stage_id'          => $lead->stage_id,
                'order'             => $lead->order,
                'phone'             => $lead->phone,
                'follow_up_date'    => $lead->follow_up_date,
                'tasks_list'        => $lead->tasks->map(function($task){
                                            return [
                                                'id'        => $task->id,
                                                'name'      => $task->name,
                                                'date'      => $task->date,
                                                'time'      => $task->time,
                                                'priority'  => LeadTask::$priorities[$task->priority],
                                                'status'    => LeadTask::$status[$task->status],
                                            ];
                                        }),

                'lead_activity'     => $lead->activities->map(function($activity){
                                                return [
                                                    'id'        => $activity->id,
                                                    // 'log_type'  => $activity->log_type,
                                                    'remark'    => strip_tags($activity->getLeadRemark()),
                                                    'time'      => $activity->created_at->diffForHumans(),
                                            ];
                                        }),
            ];

            return response()->json(['status' => 1, 'data' => $data] , 200);

        } catch (\Exception $e) {
            return response()->json(['status'=> 0 ,'message' => 'something went wrong!!!']);
        }
    }

    public function leadStageUpdate(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                'workspace_id'  => 'required|exists:work_spaces,id',
                'pipeline_id'   => 'required|exists:pipelines,id',
                'lead_id'       => 'required|exists:leads,id',
                'new_status'    => 'required|exists:lead_stages,id',
                // 'old_status'    => 'required',
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
            $leadId           = $request->lead_id;

            $lead = Lead::where('created_by', '=', creatorId())->where('workspace_id', $currentWorkspace)->where('pipeline_id', $pipelineId)->where('id', $leadId)->first();

            if ($request->new_status != $lead->stage_id) {

                $new_status   = LeadStage::where('workspace_id',$currentWorkspace)->where('created_by',creatorId())->where('id',$request->new_status)->first();
                // $old_status   = LeadStage::where('workspace_id',$currentWorkspace)->where('created_by',creatorId())->where('id',$lead->stage_id)->first();
                $lead->stage_id = $request->new_status;
                $lead->save();
            }
            return response()->json(['status' => 1 ,'message' => 'Task stage update successfully.']);

        } catch (\Exception $e) {
            return response()->json(['status' => 0 ,'message' => 'something went wrong!!!']);
        }
    }


    public function destroy(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                'workspace_id'  => 'required|exists:work_spaces,id',
                'pipeline_id'   => 'required|exists:pipelines,id',
                'lead_id'       => 'required|exists:leads,id',
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
            $leadId           = $request->lead_id;

            $lead = Lead::where('created_by', '=', creatorId())->where('workspace_id', $currentWorkspace)->where('pipeline_id', $pipelineId)->where('id', $leadId)->first();

            LeadDiscussion::where('lead_id', '=', $lead->id)->delete();
            UserLead::where('lead_id', '=', $lead->id)->delete();
            LeadActivityLog::where('lead_id', '=', $lead->id)->delete();
            $leadfiles = LeadFile::where('lead_id', '=', $lead->id)->get();

            foreach ($leadfiles as $leadfile) {

                delete_file($leadfile->file_path);
                $leadfile->delete();
            }

            $lead->delete();

            return response()->json(['status' => 1 , 'message' => 'Lead Delete Successfully.'] , 200);

        } catch (\Exception $e) {
            return response()->json(['status'=> 0 ,'message' => 'something went wrong!!!']);
        }
    }
}
