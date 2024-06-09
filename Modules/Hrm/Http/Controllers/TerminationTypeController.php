<?php

namespace Modules\Hrm\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Termination;
use Modules\Hrm\Entities\TerminationType;
use Modules\Hrm\Events\CreateTerminationType;
use Modules\Hrm\Events\DestroyTerminationType;
use Modules\Hrm\Events\UpdateTerminationType;

class TerminationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('terminationtype manage')) {
            $terminationtypes = TerminationType::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();

            return view('hrm::terminationtype.index', compact('terminationtypes'));
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
        if (Auth::user()->isAbleTo('terminationtype create')) {
            return view('hrm::terminationtype.create');
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
        if (Auth::user()->isAbleTo('terminationtype create')) {
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

            $terminationtype             = new TerminationType();
            $terminationtype->name       = $request->name;
            $terminationtype->workspace  = getActiveWorkSpace();
            $terminationtype->created_by = creatorId();
            $terminationtype->save();

            event(new CreateTerminationType($request, $terminationtype));

            return redirect()->route('terminationtype.index')->with('success', __('Termination Type  successfully created.'));
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
    public function edit(TerminationType $terminationtype)
    {
        if (Auth::user()->isAbleTo('terminationtype edit')) {
            if ($terminationtype->created_by == creatorId() && $terminationtype->workspace == getActiveWorkSpace()) {

                return view('hrm::terminationtype.edit', compact('terminationtype'));
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
    public function update(Request $request, TerminationType $terminationtype)
    {
        if (Auth::user()->isAbleTo('terminationtype edit')) {
            if ($terminationtype->created_by == creatorId() && $terminationtype->workspace == getActiveWorkSpace()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:20',

                    ]
                );

                $terminationtype->name = $request->name;
                $terminationtype->save();

                event(new UpdateTerminationType($request, $terminationtype));

                return redirect()->route('terminationtype.index')->with('success', __('Termination Type successfully updated.'));
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
    public function destroy(TerminationType $terminationtype)
    {
        if (\Auth::user()->isAbleTo('terminationtype delete')) {
            if ($terminationtype->created_by == creatorId() && $terminationtype->workspace == getActiveWorkSpace()) {
                $termination     = Termination::where('termination_type', $terminationtype->id)->where('workspace', getActiveWorkSpace())->get();
                if (count($termination) == 0) {
                    event(new DestroyTerminationType($terminationtype));

                    $terminationtype->delete();
                } else {
                    return redirect()->route('terminationtype.index')->with('error', __('This TerminationType has Termination. Please remove the Termination from this TerminationType.'));
                }

                return redirect()->route('terminationtype.index')->with('success', __('Termination Type successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
