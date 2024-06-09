<?php

namespace Modules\Lead\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Modules\Lead\Entities\User as LeadUser;
use Modules\Lead\Entities\LeadStage;
use Modules\Lead\Entities\Pipeline;
use Modules\Lead\Entities\Lead;
use Carbon\Carbon;
use App\Models\User;

class HomeApiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
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

        $objUser          = Auth::user();
        $currentWorkspace = $request->workspace_id;
        
        try{

            $totalUsers    = LeadUser::where('type', '!=', 'client')->where('created_by', creatorId())->where('workspace_id', '=', $currentWorkspace)->count();
            $totalLeads    = Lead::where('created_by', '=', creatorId())->where('workspace_id', '=', $currentWorkspace)->count();
            $latestLeads   = Lead::where('created_by', '=', creatorId())
                                        ->where('workspace_id', '=', $currentWorkspace)
                                        ->limit(5)->latest()
                                        ->get()->map(function($lead){
                                            return [
                                                'id'            => $lead->id,
                                                'name'          => $lead->name,
                                                'status'        => isset($lead->stage) ? $lead->stage->name : '',
                                                'created_at'    => Carbon::parse($lead->created_at)->format('Y-m-d H:i:s'), 
                                            ];
                                        });
            
            $leadStageData = [];
            $lead_stage = LeadStage::where('created_by', creatorId())->where('workspace_id', '=', $currentWorkspace)->orderBy('order', 'ASC')->get();
            foreach ($lead_stage as $lead_stage_data) {
                $lead_stage = Lead::where('created_by', creatorId())->where('workspace_id', '=', $currentWorkspace)->where('stage_id', $lead_stage_data->id)->orderBy('order', 'ASC')->count();
                $leadStageData[$lead_stage_data->name] = $lead_stage;
            }           
            
            $data = [
                'totalUsers' => $totalUsers,
                'totalLeads' => $totalLeads, 
                'chartData' => $leadStageData, 
                'latestLeads' => $latestLeads
            ]; 

            return response()->json(['status' => 1,'data'  => $data]);

        } catch (\Exception $e) {
            return response()->json(['status' => 0 , 'message' => 'Something went wrong!!!']);
        } 
    }

    public function getWorkspaceUsers(Request $request)
    {
        try {
            
            $validator = \Validator::make(
                $request->all(), [
                    'workspace_id'  => 'required',
                ]
            );
    
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
    
                return response()->json(['status' => 0 , 'message' => $messages->first()], 403);
            }

            $objUser          = Auth::user();
            $currentWorkspace = $request->workspace_id;

            $users  = User::where('created_by', creatorId())
                                    ->emp()
                                    ->where('workspace_id', $currentWorkspace)
                                    ->orWhere('id', Auth::user()->id)
                                    // ->limit(10)->offset((($request->page??1)-1)*10)
                                    ->get()
                                    ->map(function($user){
                                            return [
                                                'id' => $user->id,
                                                'name' => $user->name,
                                                'email' => $user->email,
                                            ];
                                        });
            
            return response()->json([
                'status' => 1,
                'data'   => $users,
                
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 0 ,'message' => 'something went wrong!!!']);
        } 
    }
}
