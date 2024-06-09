<?php

namespace Modules\Hrm\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Warning;
use Modules\Hrm\Events\CreateWarning;
use Modules\Hrm\Events\DestroyWarning;
use Modules\Hrm\Events\UpdateWarning;

class WarningController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('warning manage')) {
            if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
                $warnings = Warning::where('warning_by', '=', \Auth::user()->id)->where('workspace', getActiveWorkSpace())->get();
            } else {
                $warnings = Warning::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();
            }
            return view('hrm::warning.index', compact('warnings'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('warning create')) {
            $employees   = User::where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->emp()->get()->pluck('name', 'id');

            return view('hrm::warning.create', compact('employees'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('warning create')) {
            if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'warning_by' => 'required',
                    ]
                );
            }

            $validator = \Validator::make(
                $request->all(),
                [
                    'warning_to' => 'required',
                    'subject' => 'required',
                    'warning_date' => 'required|after:yesterday',
                    'description' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $warning = new Warning();

            if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
                $warning->warning_by = \Auth::user()->id;
            } else {
                $warning->warning_by = $request->warning_by;
            }
            $warning->warning_to   = $request->warning_to;
            $warning->subject      = $request->subject;
            $warning->warning_date = $request->warning_date;
            $warning->description  = $request->description;
            $warning->workspace    = getActiveWorkSpace();
            $warning->created_by   = creatorId();
            $warning->save();

            event(new CreateWarning($request, $warning));
            $company_settings = getCompanyAllSetting();
            if (!empty($company_settings['Employee Warning']) && $company_settings['Employee Warning']  == true) {

                $User        = User::where('id', $warning->warning_to)->where('workspace_id', '=',  getActiveWorkSpace())->first();
                $uArr = [
                    'employee_warning_name' => $User->name,
                    'warning_subject' => $request->subject,
                    'warning_description' => $request->description,


                ];
                try {

                    $resp = EmailTemplate::sendEmailTemplate('Employee Warning', [$User->email], $uArr);
                } catch (\Exception $e) {
                    $resp['error'] = $e->getMessage();
                }
                return redirect()->route('warning.index')->with('success', __('Warning  successfully created.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }

            return redirect()->route('warning.index')->with('success', __('Warning  successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return redirect()->back();
        return view('hrm::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Warning $warning)
    {
        if (Auth::user()->isAbleTo('warning edit')) {
            if ($warning->created_by == creatorId() && $warning->workspace == getActiveWorkSpace()) {
                $employees   = User::where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->emp()->get()->pluck('name', 'id');

                return view('hrm::warning.edit', compact('warning', 'employees'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Warning $warning)
    {
        if (Auth::user()->isAbleTo('warning edit')) {
            if ($warning->created_by == creatorId() && $warning->workspace == getActiveWorkSpace()) {
                if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
                    $validator = \Validator::make(
                        $request->all(),
                        [
                            'warning_by' => 'required',
                        ]
                    );
                }

                $validator = \Validator::make(
                    $request->all(),
                    [
                        'warning_to' => 'required',
                        'subject' => 'required',
                        'warning_date' => 'required|date',
                        'description' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
                    $warning->warning_by = \Auth::user()->id;
                } else {
                    $warning->warning_by = $request->warning_by;
                }
                $warning->warning_to   = $request->warning_to;
                $warning->subject      = $request->subject;
                $warning->warning_date = $request->warning_date;
                $warning->description  = $request->description;
                $warning->save();

                event(new UpdateWarning($request, $warning));

                return redirect()->route('warning.index')->with('success', __('Warning successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Warning $warning)
    {
        if (Auth::user()->isAbleTo('warning delete')) {
            if ($warning->created_by == creatorId() && $warning->workspace == getActiveWorkSpace()) {

                event(new DestroyWarning($warning));

                $warning->delete();

                return redirect()->route('warning.index')->with('success', __('Warning successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
