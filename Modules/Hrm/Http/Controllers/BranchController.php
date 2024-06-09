<?php

namespace Modules\Hrm\Http\Controllers;

use App\Models\Setting;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Branch;
use Modules\Hrm\Entities\Department;
use Modules\Hrm\Entities\Designation;
use Modules\Hrm\Entities\Employee;
use Modules\Hrm\Events\CreateBranch;
use Modules\Hrm\Events\DestroyBranch;
use Modules\Hrm\Events\UpdateBranch;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('branch manage')) {
            $branches = Branch::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();
            return view('hrm::branch.index', compact('branches'));
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
        if (Auth::user()->isAbleTo('branch create')) {
            return view('hrm::branch.create');
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
        if (Auth::user()->isAbleTo('branch create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $branch             = new Branch();
            $branch->name       = $request->name;
            $branch->workspace  = getActiveWorkSpace();
            $branch->created_by = creatorId();
            $branch->save();

            event(new CreateBranch($request, $branch));

            return redirect()->route('branch.index')->with('success', __('Branch  successfully created.'));
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
    public function edit(Branch $branch)
    {
        if (Auth::user()->isAbleTo('branch edit')) {
            if ($branch->created_by == creatorId() &&  $branch->workspace  == getActiveWorkSpace()) {
                return view('hrm::branch.edit', compact('branch'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
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
    public function update(Request $request, Branch $branch)
    {
        if (Auth::user()->isAbleTo('branch edit')) {
            if ($branch->created_by == creatorId() &&  $branch->workspace  == getActiveWorkSpace()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $branch->name = $request->name;
                $branch->save();

                event(new UpdateBranch($request, $branch));

                return redirect()->route('branch.index')->with('success', __('Branch successfully updated.'));
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
    public function destroy(Branch $branch)
    {
        if (Auth::user()->isAbleTo('branch delete')) {
            if ($branch->created_by == creatorId() &&  $branch->workspace  == getActiveWorkSpace()) {
                $employee     = Employee::where('branch_id', $branch->id)->where('workspace', getActiveWorkSpace())->get();
                if (count($employee) == 0) {
                    Department::where('branch_id', $branch->id)->delete();
                    Designation::where('branch_id', $branch->id)->delete();

                    event(new DestroyBranch($branch));

                    $branch->delete();
                } else {
                    return redirect()->route('branch.index')->with('error', __('This branch has employees. Please remove the employee from this branch.'));
                }

                return redirect()->route('branch.index')->with('success', __('Branch successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function BranchNameEdit()
    {
        if (Auth::user()->isAbleTo('branch name edit')) {
            return view('hrm::branch.branchnameedit');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function saveBranchName(Request $request)
    {
        if (Auth::user()->isAbleTo('branch name edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'hrm_branch_name' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            } else {
                $post = $request->all();
                unset($post['_token']);

                foreach ($post as $key => $value) {
                    // Define the data to be updated or inserted
                    $data = [
                        'key' => $key,
                        'workspace' => getActiveWorkSpace(),
                        'created_by' => creatorId(),
                    ];
                    // Check if the record exists, and update or insert accordingly
                    Setting::updateOrInsert($data, ['value' => $value]);
                }
                // Settings Cache forget
                comapnySettingCacheForget();
                return redirect()->route('branch.index')->with('success', __('Branch Name successfully updated.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
