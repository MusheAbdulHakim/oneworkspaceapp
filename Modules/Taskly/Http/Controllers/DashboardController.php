<?php

namespace Modules\Taskly\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Taskly\Entities\ClientProject;
use Modules\Taskly\Entities\Stage;
use Modules\Taskly\Entities\Task;
use Modules\Taskly\Entities\UserProject;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function __construct()
    {
        if(module_is_active('GoogleAuthentication'))
        {
            $this->middleware('2fa');
        }
    }

    public function index()
    {
        if(Auth::user()->isAbleTo('taskly dashboard manage'))
        {

            $userObj          = Auth::user();
            $currentWorkspace = getActiveWorkSpace();

            if(Auth::user()->hasRole('client'))
            {
                $doneStage    = Stage::where('workspace_id', '=', $currentWorkspace)->where('complete', '=', '1')->first();

                $totalProject   = ClientProject::join("projects", "projects.id", "=", "client_projects.project_id")->where("client_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace)->where('projects.type', 'project')->count();
                $totalBugs      = ClientProject::join("bug_reports", "bug_reports.project_id", "=", "client_projects.project_id")->join("projects", "projects.id", "=", "client_projects.project_id")->where('projects.workspace', '=', $currentWorkspace)->where('projects.type', 'project')->count();
                $totalTask      = ClientProject::join("tasks", "tasks.project_id", "=", "client_projects.project_id")->join("projects", "projects.id", "=", "client_projects.project_id")->where('projects.workspace', '=', $currentWorkspace)->where('projects.type', 'project')->where("client_id", "=", $userObj->id)->count();
                if(!empty($doneStage))
                {
                    $completeTask   = ClientProject::join("tasks", "tasks.project_id", "=", "client_projects.project_id")->join("projects", "projects.id", "=", "client_projects.project_id")->where('projects.workspace', '=', $currentWorkspace)->where('projects.type', 'project')->where("client_id", "=", $userObj->id)->where('tasks.status', '=', $doneStage->id)->count();
                }
                else
                {
                    $completeTask = 0;
                }

                    $tasks          = Task::select(
                        [
                            'tasks.*',
                            'stages.name as status',
                            'stages.complete',
                        ]
                    )->join("client_projects", "tasks.project_id", "=", "client_projects.project_id")->join("projects", "projects.id", "=", "client_projects.project_id")->join("stages", "stages.id", "=", "tasks.status")->where('projects.workspace', '=', $currentWorkspace)->where("client_id", "=", $userObj->id)->orderBy('tasks.id', 'desc')->where('projects.type', 'project')->limit(5)->with('project')->get();
                    $totalMembers   = 0 ;
                    $projectProcess = ClientProject::join("projects", "projects.id", "=", "client_projects.project_id")->where('projects.workspace', '=', $currentWorkspace)->where('projects.type', 'project')->where("client_id", "=", $userObj->id)->groupBy('projects.status')->selectRaw('count(projects.id) as count, projects.status')->pluck('count', 'projects.status');

                    $arrProcessPer   = [];
                    $arrProcessLabel = [];
                    if(count($projectProcess) > 0)
                    {
                        foreach($projectProcess as $lable => $process)
                        {
                            $arrProcessLabel[] = $lable;
                            if($totalProject == 0)
                            {
                                $arrProcessPer[] = 0.00;
                            }
                            else
                            {
                                $arrProcessPer[] = round(($process * 100) / $totalProject, 2);
                            }
                        }
                    }
                    else
                    {
                            $arrProcessPer[0]   = 100;
                            $arrProcessLabel[0] = '';
                    }
                    $arrProcessClass = [
                        'text-success',
                        'text-primary',
                        'text-danger',
                    ];
                    $chartData       = app('Modules\Taskly\Http\Controllers\ProjectController')->getProjectChart(
                        [
                            'workspace_id' => $currentWorkspace,
                            'duration' => 'week',
                        ]
                    );
                    return view('taskly::index', compact('currentWorkspace', 'totalProject', 'totalBugs', 'totalTask', 'totalMembers', 'arrProcessLabel', 'arrProcessPer', 'arrProcessClass', 'completeTask', 'tasks', 'chartData'));

            }
            $totalProject = UserProject::join("projects", "projects.id", "=", "user_projects.project_id")->where("user_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace)->where('projects.type', 'project')->count();
            $doneStage    = Stage::where('workspace_id', '=', $currentWorkspace)->where('complete', '=', '1')->first();

            $totalBugs    = UserProject::join("bug_reports", "bug_reports.project_id", "=", "user_projects.project_id")->join("projects", "projects.id", "=", "user_projects.project_id")->where("user_id", "=", $userObj->id)->where('projects.type', 'project')->where('projects.workspace', '=', $currentWorkspace)->count();
            $totalTask    = UserProject::join("tasks", "tasks.project_id", "=", "user_projects.project_id")->join("projects", "projects.id", "=", "user_projects.project_id")->where("user_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace)->where('projects.type', 'project')->count();
            if(!empty($doneStage))
            {
                $completeTask = UserProject::join("tasks", "tasks.project_id", "=", "user_projects.project_id")->join("projects", "projects.id", "=", "user_projects.project_id")->where("user_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace)->where('tasks.status', '=', $doneStage->id)->where('projects.type', 'project')->count();
            }
            else
            {
                $completeTask = 0;
            }
            $tasks        = Task::select(
                [
                    'tasks.*',
                    'stages.name as status',
                    'stages.complete',
                    ]
                    )->join("user_projects", "tasks.project_id", "=", "user_projects.project_id")->join("projects", "projects.id", "=", "user_projects.project_id")->join("stages", "stages.id", "=", "tasks.status")->where("user_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace)->orderBy('tasks.id', 'desc')->where('projects.type', 'project')->limit(5)->with('project')->get();

            $projectProcess  = UserProject::join("projects", "projects.id", "=", "user_projects.project_id")->where("user_id", "=", $userObj->id)->where('projects.workspace', '=', $currentWorkspace)->where('projects.type', 'project')->groupBy('projects.status')->selectRaw('count(projects.id) as count, projects.status')->pluck('count', 'projects.status');
                $arrProcessLabel = [];
                $arrProcessPer   = [];
                $arrProcessLabel = [];

                if(count($projectProcess) > 0)
                {
                    foreach($projectProcess as $lable => $process)
                    {
                        $arrProcessLabel[] = $lable;
                        if($totalProject == 0)
                        {
                            $arrProcessPer[] = 0.00;
                        }
                        else
                        {
                            $arrProcessPer[] = round(($process * 100) / $totalProject, 2);
                        }
                    }

                }
                else
                {

                    $arrProcessPer[0]   = 100;
                    $arrProcessLabel[0] = '';
                }
                $arrProcessClass = [
                    'text-success',
                    'text-primary',
                    'text-danger',
                ];
                $chartData = app('Modules\Taskly\Http\Controllers\ProjectController')->getProjectChart(
                    [
                        'workspace_id' => $currentWorkspace,
                        'duration' => 'week',
                    ]
                );
                $totalMembers = 0;
            return view('taskly::index', compact('currentWorkspace', 'totalProject', 'totalBugs', 'totalTask', 'totalMembers', 'arrProcessLabel', 'arrProcessPer', 'arrProcessClass', 'completeTask', 'tasks', 'chartData'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }
}
