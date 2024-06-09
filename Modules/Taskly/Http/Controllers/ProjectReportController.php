<?php

namespace Modules\Taskly\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\User;
use Modules\Taskly\Entities\Project;
use Modules\Taskly\Entities\Stage;
use Modules\Taskly\Entities\Task;
use Modules\Taskly\Entities\Milestone;

class ProjectReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $objUser = Auth::user();

        if (Auth::user()->hasRole('client')) {
            $projects = Project::select('projects.*')->join('client_projects', 'projects.id', '=', 'client_projects.project_id')->projectonly()->where('client_projects.client_id', '=',  Auth::user()->id)->where('projects.workspace', '=', getActiveWorkSpace())->get();
        } else {
            $projects = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->projectonly()->where('user_projects.user_id', '=',  Auth::user()->id)->where('projects.workspace', '=', getActiveWorkSpace())->get();
        }
        $stages = Stage::where('workspace_id', '=', getActiveWorkSpace())->orderBy('order')->get();
        $users = User::where('workspace_id', '=', getActiveWorkSpace())->get();

        if (Auth::user()->hasRole('client')) {
            $projects = Project::select('projects.*')->join('client_projects', 'projects.id', '=', 'client_projects.project_id')->projectonly()->where('client_projects.client_id', '=', $objUser->id)->where('projects.workspace', '=',getActiveWorkSpace());
       } else {
            $projects = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->projectonly()->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=',getActiveWorkSpace());
       }

        if ($request->all_users) {
           unset($projects);
            $projects = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->projectonly()->where('user_projects.user_id', '=', $request->all_users)->where('projects.workspace', '=',getActiveWorkSpace());
       }

       if ($request->status) {
           $projects->where('status', '=', $request->status);
       }

       if ($request->start_date) {

            $projects->where('start_date', '=', $request->start_date);

       }

        if ($request->end_date) {

            $projects->where('end_date', '=', $request->end_date);

       }

       $client_keyword =Auth::user()->hasRole( 'client') ? 'client.' : '';
       $projects = $projects->get();
       $data = [];
       foreach($projects as $project) {
           $tmp = [];

           $tmp['id'] = $project->id;
           $tmp['name'] = $project->name;
           $tmp['start_date'] = $project->start_date;
            $tmp['end_date'] = $project->end_date;

            $tmp['members'] = '<div class="user-group mx-2">';

                    foreach($project->users as $user){
                       $path = asset(get_file($user->avatar));
                       $avatar = $user->avatar ? 'src="'.$path.'"':'avatar="'.$user->name.'"';

                       if($user->pivot->is_active){
                                          $tmp['members'] .=
                                         '
                                               <img alt="image"   data-toggle="tooltip" data-placement="top" title="" data-bs-original-title=" '.$user->name.'" '.$avatar.'/>

                                           ';


                                           }
                                       }
                                     $tmp['members'] .=   '</div>';
                   $percentage = $project->project_progress();

          $tmp['Progress'] =
               '<div class="progress_wrapper">
                                      <div class="progress">
                                         <div class="progress-bar" role="progressbar"
                                          style="width:'.$percentage["percentage"].'"
                                            aria-valuenow="55" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                      </div>
                                      <div class="progress_labels">
                                         <div class="total_progress">

                                            <strong>'.$percentage["percentage"].'</strong>
                                         </div>

                                      </div>
                                   </div>';

           if ($project->status == 'Finished') {
               $tmp['status'] = '<span class="badge rounded-pill p-2 px-3  bg-success">' .'Finished' . '</span>';
           }
           elseif($project->status == 'Ongoing') {
               $tmp['status'] = '<span class="badge rounded-pill p-2 px-3  bg-secondary">' . 'Ongoing' . '</span>';
           }
           else {
               $tmp['status'] = '<span class="badge rounded-pill p-2 px-3  bg-warning">' . 'OnHold'. '</span>';
           }


            $tmp['action'] = '
            <a  class="action-btn bg-warning  btn btn-sm d-inline-flex align-items-center" data-toggle="tooltip"  title="' . __('view') . '" data-size="lg" data-title="' . __('view') . '" href="' . route(
                $client_keyword.'project_report.show', [
                    $project->id,
                ]
            ) . '"><i class="ti ti-eye"></i></a>';


           $data[] = ($tmp);

       }
        //    dd($data);
        return view('taskly::project_report.index', compact('data','stages','users' ,'projects'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('taskly::create');
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
    public function show(Request $request,$id)
    {
        $objUser = Auth::user();

        if (Auth::user()->hasRole('client')) {
            $project = Project::select('projects.*')->join('client_projects', 'projects.id', '=', 'client_projects.project_id')->projectonly()->where('client_projects.client_id', '=', $objUser->id)->where('projects.workspace', '=',getActiveWorkSpace())->where('projects.id', '=', $id)->first();
        } else {
            $project = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->projectonly()->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=',getActiveWorkSpace())->where('projects.id', '=', $id)->first();
        }
        if ($project) {
            $chartData = $this->getProjectChart(
                [
                    'workspace_id' =>getActiveWorkSpace(),
                    'project_id' => $id,
                    'duration' => 'week',
                ]
            );

            $daysleft = round((((strtotime($project->end_date) - strtotime(date('Y-m-d'))) / 24) / 60) / 60);

            $project_status_task = stage::join("tasks", "tasks.status", "=", "stages.id")->where("workspace_id", "=",getActiveWorkSpace())->where('tasks.project_id', '=', $id)->groupBy('name')->selectRaw('count(tasks.id) as count, name')->pluck('count', 'name');

             $totaltask = Task::where('project_id',$id)->count();

             $arrProcessPer_status_task = [];
            $arrProcess_Label_status_tasks = [];
                foreach ($project_status_task as $lables => $percentage_stage) {
                     $arrProcess_Label_status_tasks[] = $lables;
                    if ($totaltask == 0) {
                        $arrProcessPer_status_task[] = 0.00;
                    } else {
                        $arrProcessPer_status_task[] = round(($percentage_stage * 100) / $totaltask, 2);
                    }
                }

           $project_priority_task = Task::where('project_id',$id)->groupBy('priority')->selectRaw('count(id) as count, priority')->pluck('count', 'priority');

            $arrProcessPer_priority = [];
            $arrProcess_Label_priority = [];
                foreach ($project_priority_task as $lable => $process) {
                     $arrProcess_Label_priority[] = $lable;
                    if ($totaltask == 0) {
                        $arrProcessPer_priority[] = 0.00;
                    } else {
                        $arrProcessPer_priority[] = round(($process * 100) / $totaltask, 2);
                    }
                }

                 $arrProcessClass = [
                    'text-success',
                    'text-primary',
                    'text-danger',
                ];

                  $chartData = app('Modules\Taskly\Http\Controllers\ProjectController')->getProjectChart([
                    'workspace_id' => getActiveWorkSpace(),
                    'duration' => 'week',
                ]);


            $stages = Stage::where('workspace_id', '=', getActiveWorkSpace())->orderBy('order')->get();
            $users = User::where('workspace_id', '=', getActiveWorkSpace())->get();

            $milestones = Milestone::where('project_id' ,$id)->get();


            //Logged Hours
            $logged_hour_chart = 0;
            $total_hour = 0;
            $logged_hour = 0;


                $tasks = Task::where('project_id',$id)->get();

                // $data = [];

            //Estimated Hours

            $esti_logged_hour_chart = 0;
            $esti_total_hour = 0;
            $esti_logged_hour = 0;
            $hourdiff = 0;

            foreach ($tasks as $task) {
                $start_date = $task->start_date;
                $end_date = $task->due_date;
                $hourdiff = round((strtotime($end_date) - strtotime($start_date))/3600, 1);
                $esti_logged_hour += $hourdiff ;
                $esti_logged_hour_chart = number_format($esti_logged_hour, 2, '.', '');

            }


            $userObj = Auth::user();

            $tasks = Task::select(
                [
                    'tasks.*',
                    'stages.name as stage',
                    'stages.complete',
                ]
            )->where('project_id',$id)->join("stages", "stages.id", "=", "tasks.status");


        if ($request->assign_to) {
            $tasks->whereRaw("find_in_set('" . $request->assign_to . "',assign_to)");
        }

        if ($request->priority) {
            $tasks->where('priority', '=', $request->priority);
        }

         if ($request->milestone_id) {
            $tasks->where('milestone_id', '=', $request->milestone_id);
        }
        if ($request->status) {
            $tasks->where('tasks.status', '=', $request->status);
        }

         if ($request->start_date) {
            $tasks->where('start_date', '=', $request->status);
        }

        if ($request->due_date) {
            $tasks->where('due_date', '=', $request->due_date);
        }


        $tasks = $tasks->get();


        $hour_format_number = 0;
        $total_hour = 0;
        $logged_hour = 0;



        $tasksData = [];
        $data = [];
        foreach ($tasks as $task) {
            $tmp = [];
            $tmp['title'] = '<a href="' . route(
                'projects.task.board', [
                    // $currentWorkspace->slug,
                    $task->project_id,
                ]
            ) . '" class="text-body">' . $task->title . '</a>';

            $tmp['milestone'] = ($milestone = $task->milestone()) ? $milestone->title : '';
             $start_date = '<span class="text-body">' . date('Y-m-d', strtotime($task->start_date)) . '</span> ';

            $due_date = '<span class="text-' . ($task->due_date < date('Y-m-d') ? 'danger' : 'success') . '">' . date('Y-m-d', strtotime($task->due_date)) . '</span> ';
            $tmp['start_date'] = $start_date;
            $tmp['due_date'] = $due_date;

            if (Auth::user()->hasRole('company') || Auth::user()->hasRole('client')) {
                $tmp['user_name'] = "";
                foreach ($task->users() as $user) {
                    if (isset($user) && $user) {
                        $tmp['user_name'] .= '<span class="badge bg-secondary p-2 px-3 rounded">' . $user->name . '</span> ';
                    }
                }
            }



            if ($task->priority == "High") {
                $tmp['priority'] = '<span class="priority_badge badge bg-danger p-2 px-3 rounded">' . __('High') . '</span>';
            } elseif ($task->priority == "Medium") {
                $tmp['priority'] = '<span class="priority_badge badge bg-info p-2 px-3 rounded">' . __('Medium') . '</span>';
            } else {
                $tmp['priority'] = '<span class="priority_badge badge bg-success p-2 px-3 rounded">' . __('Low') . '</span>';
            }

             if ($task->complete == 1) {
                $tmp['status'] = '<span class="status_badge badge bg-success p-2 px-3 rounded">' . __($task->stage) . '</span>';
            } else {
                $tmp['status'] = '<span class="status_badge badge bg-primary p-2 px-3 rounded">' . __($task->stage) . '</span>';
            }


            $tasksData[] = ($tmp);

        }
        return view('taskly::project_report.show', compact('tasksData', 'project', 'chartData', 'daysleft','arrProcessPer_priority','arrProcess_Label_priority','arrProcessClass','stages','users','milestones','arrProcess_Label_status_tasks','arrProcessPer_status_task','logged_hour_chart','esti_logged_hour_chart'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('taskly::edit');
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


    public function ajax_data(Request $request)
    {
      $objUser = Auth::user();
        if (Auth::user()->hasRole('client')) {
             $projects = Project::select('projects.*')->join('client_projects', 'projects.id', '=', 'client_projects.project_id')->projectonly()->where('client_projects.client_id', '=', $objUser->id)->where('projects.workspace', '=',getActiveWorkSpace());
        } else {
             $projects = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->projectonly()->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=',getActiveWorkSpace());
        }

         if ($request->all_users) {
            unset($projects);
             $projects = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->projectonly()->where('user_projects.user_id', '=', $request->all_users)->where('projects.workspace', '=',getActiveWorkSpace());
        }

        if ($request->status) {
            $projects->where('status', '=', $request->status);
        }

        if ($request->start_date) {

             $projects->where('start_date', '=', $request->start_date);

        }

         if ($request->end_date) {

             $projects->where('end_date', '=', $request->end_date);

        }

        $client_keyword =Auth::user()->hasRole( 'client') ? 'client.' : '';
        $projects = $projects->get();
        $data = [];
        foreach($projects as $project) {
            $tmp = [];

            $tmp['id'] = $project->id;
            $tmp['name'] = $project->name;
            $tmp['start_date'] = $project->start_date;
             $tmp['end_date'] = $project->end_date;

             $tmp['members'] = '<div class="user-group mx-2">';

                     foreach($project->users as $user){
                        $path = asset(get_file($user->avatar));
                        $avatar = $user->avatar ? 'src="'.$path.'"':'avatar="'.$user->name.'"';

                        if($user->pivot->is_active){
                                           $tmp['members'] .=
                                          '
                                                <img alt="image"   data-toggle="tooltip" data-placement="top" title="" data-bs-original-title=" '.$user->name.'" '.$avatar.'/>

                                            ';


                                            }
                                        }
                                      $tmp['members'] .=   '</div>';
                    $percentage = $project->project_progress();

           $tmp['Progress'] =
                '<div class="progress_wrapper">
                                       <div class="progress">
                                          <div class="progress-bar" role="progressbar"
                                           style="width:'.$percentage["percentage"].'"
                                             aria-valuenow="55" aria-valuemin="0" aria-valuemax="100">
                                             </div>
                                       </div>
                                       <div class="progress_labels">
                                          <div class="total_progress">

                                             <strong>'.$percentage["percentage"].'</strong>
                                          </div>

                                       </div>
                                    </div>';

            if ($project->status == 'Finished') {
                $tmp['status'] = '<span class="badge rounded-pill p-2 px-3  bg-success">' .'Finished' . '</span>';
            }
            elseif($project->status == 'Ongoing') {
                $tmp['status'] = '<span class="badge rounded-pill p-2 px-3  bg-secondary">' . 'Ongoing' . '</span>';
            }
            else {
                $tmp['status'] = '<span class="badge rounded-pill p-2 px-3  bg-warning">' . 'OnHold'. '</span>';
            }

            if (Auth::user()->hasRole('company')) {

                $tmp['action'] = '
                <div class="action-btn bg-warning">
                <a class="btn btn-sm d-inline-flex align-items-center text-white" data-toggle="tooltip"  title="' . __('view') . '" data-size="lg" data-title="' . __('view') . '" href="' . route(
                    $client_keyword.'project_report.show', [
                        $project->id,
                    ]
                ) . '"><i class="ti ti-eye"></i></a></div>


                        <div class="action-btn bg-info">
                <a href="#" class="btn btn-sm d-inline-flex align-items-center text-white"  data-toggle="tooltip"   title="' . __('Edit') . '" data-ajax-popup="true" data-size="lg" data-title="' . __('Edit') . '" data-url="' . route(
                    'projects.edit', [
                        $project->id,
                    ]
                ) . '"><i class="ti ti-pencil"></i></a></div>';
            }else
            {

                 $tmp['action'] = '
                <a  class="action-btn bg-warning  btn btn-sm d-inline-flex align-items-center" data-toggle="tooltip"  title="' . __('view') . '" data-size="lg" data-title="' . __('view') . '" href="' . route(
                    $client_keyword.'project_report.show', [
                        $project->id,
                    ]
                ) . '"><i class="ti ti-eye"></i></a>';

            }

            $data[] = array_values($tmp);

        }

        return response()->json(['data' => $data], 200);
    }


    public function getProjectChart($arrParam)
    {
        $arrDuration = [];
        if ($arrParam['duration'] && $arrParam['duration'] == 'week') {
            $previous_week = Project::getFirstSeventhWeekDay(-1);
            foreach ($previous_week['datePeriod'] as $dateObject) {
                $arrDuration[$dateObject->format('Y-m-d')] = $dateObject->format('D');
            }
        }

        $arrTask = [
            'label' => [],
            'color' => [],
        ];
        $stages = Stage::where('workspace_id', '=', $arrParam['workspace_id'])->orderBy('order');

        foreach ($arrDuration as $date => $label) {
            $objProject = Task::select('status', DB::raw('count(*) as total'))->whereDate('updated_at', '=', $date)->groupBy('status');

            if (isset($arrParam['project_id'])) {
                $objProject->where('project_id', '=', $arrParam['project_id']);
            }
            if (isset($arrParam['workspace_id'])) {
                $objProject->whereIn(
                    'project_id', function ($query) use ($arrParam) {
                        $query->select('id')->from('projects')->where('workspace', '=', $arrParam['workspace_id']);
                    }
                );
            }
            $data = $objProject->pluck('total', 'status')->all();

            foreach ($stages->pluck('name', 'id')->toArray() as $id => $stage) {
                $arrTask[$id][] = isset($data[$id]) ? $data[$id] : 0;
            }
            $arrTask['label'][] = __($label);
        }
        $arrTask['stages'] = $stages->pluck('name', 'color')->toArray();
        $arrTask['color'] = $stages->pluck('color')->toArray();
        return $arrTask;
    }


    public function ajax_tasks_report(Request $request ,$id)
    {
        $userObj = Auth::user();

            $tasks = Task::select(
                [
                    'tasks.*',
                    'stages.name as stage',
                    'stages.complete',
                ]
            )->where('project_id',$id)->join("stages", "stages.id", "=", "tasks.status");


        if ($request->assign_to) {
            $tasks->whereRaw("find_in_set('" . $request->assign_to . "',assign_to)");
        }

        if ($request->priority) {
            $tasks->where('priority', '=', $request->priority);
        }

         if ($request->milestone_id) {
            $tasks->where('milestone_id', '=', $request->milestone_id);
        }
        if ($request->status) {
            $tasks->where('tasks.status', '=', $request->status);
        }

         if ($request->start_date) {
            $tasks->where('start_date', '=', $request->status);
        }

        if ($request->due_date) {
            $tasks->where('due_date', '=', $request->due_date);
        }


        $tasks = $tasks->get();


        $hour_format_number = 0;
        $total_hour = 0;
        $logged_hour = 0;




        $data = [];
        foreach ($tasks as $task) {
            $tmp = [];
            $tmp['title'] = '<a href="' . route(
                'projects.task.board', [
                    // $currentWorkspace->slug,
                    $task->project_id,
                ]
            ) . '" class="text-body">' . $task->title . '</a>';

            $tmp['milestone'] = ($milestone = $task->milestone()) ? $milestone->title : '';
             $start_date = '<span class="text-body">' . date('Y-m-d', strtotime($task->start_date)) . '</span> ';

            $due_date = '<span class="text-' . ($task->due_date < date('Y-m-d') ? 'danger' : 'success') . '">' . date('Y-m-d', strtotime($task->due_date)) . '</span> ';
            $tmp['start_date'] = $start_date;
            $tmp['due_date'] = $due_date;

            if (Auth::user()->hasRole('company') || Auth::user()->hasRole('client')) {
                $tmp['user_name'] = "";
                foreach ($task->users() as $user) {
                    if (isset($user) && $user) {
                        $tmp['user_name'] .= '<span class="badge bg-secondary p-2 px-3 rounded">' . $user->name . '</span> ';
                    }
                }
            }



            if ($task->priority == "High") {
                $tmp['priority'] = '<span class="priority_badge badge bg-danger p-2 px-3 rounded">' . __('High') . '</span>';
            } elseif ($task->priority == "Medium") {
                $tmp['priority'] = '<span class="priority_badge badge bg-info p-2 px-3 rounded">' . __('Medium') . '</span>';
            } else {
                $tmp['priority'] = '<span class="priority_badge badge bg-success p-2 px-3 rounded">' . __('Low') . '</span>';
            }

             if ($task->complete == 1) {
                $tmp['status'] = '<span class="status_badge badge bg-success p-2 px-3 rounded">' . __($task->stage) . '</span>';
            } else {
                $tmp['status'] = '<span class="status_badge badge bg-primary p-2 px-3 rounded">' . __($task->stage) . '</span>';
            }


            $data[] = array_values($tmp);

        }

        return response()->json(['data' => $data], 200);

    }
}
