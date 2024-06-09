<?php

namespace Modules\Hrm\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Leave;
use Modules\Hrm\Entities\LeaveType;
use Modules\Hrm\Events\CreateLeaveType;
use Modules\Hrm\Events\DestroyLeaveType;
use Modules\Hrm\Events\UpdateLeaveType;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('leavetype manage')) {
            $leavetypes = LeaveType::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();
            return view('hrm::leavetype.index', compact('leavetypes'));
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
        if (Auth::user()->isAbleTo('leavetype create')) {
            return view('hrm::leavetype.create');
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
        if (Auth::user()->isAbleTo('leavetype create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'days' => 'required|gt:0',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $leavetype             = new LeaveType();
            $leavetype->title      = $request->title;
            $leavetype->days       = $request->days;
            $leavetype->workspace   = getActiveWorkSpace();
            $leavetype->created_by = creatorId();
            $leavetype->save();

            event(new CreateLeaveType($request, $leavetype));

            return redirect()->route('leavetype.index')->with('success', __('Leave Type  successfully created.'));
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
    public function edit(LeaveType $leavetype)
    {
        if (Auth::user()->isAbleTo('leavetype edit')) {
            if ($leavetype->created_by == creatorId() && $leavetype->workspace == getActiveWorkSpace()) {

                return view('hrm::leavetype.edit', compact('leavetype'));
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
    public function update(Request $request, LeaveType $leavetype)
    {
        if (Auth::user()->isAbleTo('leavetype edit')) {
            if ($leavetype->created_by == creatorId() && $leavetype->workspace == getActiveWorkSpace()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'title' => 'required',
                        'days' => 'required|gt:0',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $leavetype->title = $request->title;
                $leavetype->days  = $request->days;
                $leavetype->save();

                event(new UpdateLeaveType($request, $leavetype));

                return redirect()->route('leavetype.index')->with('success', __('Leave Type successfully updated.'));
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
    public function destroy(LeaveType $leavetype)
    {
        if (Auth::user()->isAbleTo('leavetype delete')) {
            if ($leavetype->created_by == creatorId() && $leavetype->workspace == getActiveWorkSpace()) {
                $leave     = Leave::where('leave_type_id', $leavetype->id)->where('workspace', getActiveWorkSpace())->get();
                if (count($leave) == 0) {
                    event(new DestroyLeaveType($leavetype));

                    $leavetype->delete();
                } else {
                    return redirect()->route('leavetype.index')->with('error', __('This leavetype has leave. Please remove the leave from this leavetype.'));
                }
                return redirect()->route('leavetype.index')->with('success', __('Leave Type successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
