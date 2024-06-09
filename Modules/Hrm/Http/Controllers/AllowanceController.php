<?php

namespace Modules\Hrm\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Allowance;
use Modules\Hrm\Entities\AllowanceOption;
use Modules\Hrm\Entities\Employee;
use Modules\Hrm\Events\CreateAllowance;
use Modules\Hrm\Events\DestroyAllowance;
use Modules\Hrm\Events\UpdateAllowance;

class AllowanceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function allowanceCreate($id)
    {
        if (Auth::user()->isAbleTo('allowance create')) {
            $allowance_options = AllowanceOption::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');

            $employee          = Employee::find($id);
            $Allowancetypes = Allowance::$Allowancetype;

            return view('hrm::allowance.create', compact('employee', 'allowance_options', 'Allowancetypes'));
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
        return view('hrm::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('allowance create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'employee_id' => 'required',
                    'allowance_option' => 'required',
                    'title' => 'required',
                    'amount' => 'required|numeric|min:0',
                    'type' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $allowance                   = new Allowance();
            $allowance->employee_id      = $request->employee_id;
            $allowance->allowance_option = $request->allowance_option;
            $allowance->title            = $request->title;
            $allowance->amount           = $request->amount;
            $allowance->type             = $request->type;
            $allowance->workspace        = getActiveWorkSpace();
            $allowance->created_by       = creatorId();
            $allowance->save();

            event(new CreateAllowance($request, $allowance));

            return redirect()->back()->with('success', __('Allowance  successfully created.'));
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
        return redirect()->back()->with('error', __('Permission denied.'));
        return view('hrm::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Allowance $allowance)
    {
        if (Auth::user()->isAbleTo('allowance edit')) {
            if ($allowance->created_by == creatorId() && $allowance->workspace == getActiveWorkSpace()) {
                $allowance_options = AllowanceOption::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                $Allowancetypes = Allowance::$Allowancetype;
                return view('hrm::allowance.edit', compact('allowance', 'allowance_options', 'Allowancetypes'));
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
    public function update(Request $request, Allowance $allowance)
    {
        if (Auth::user()->isAbleTo('allowance edit')) {
            if ($allowance->created_by == creatorId() && $allowance->workspace == getActiveWorkSpace()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'allowance_option' => 'required',
                        'title' => 'required',
                        'amount' => 'required|numeric|min:0',
                        'type' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $allowance->allowance_option = $request->allowance_option;
                $allowance->title            = $request->title;
                $allowance->type             = $request->type;
                $allowance->amount           = $request->amount;
                $allowance->save();

                event(new UpdateAllowance($request, $allowance));

                return redirect()->back()->with('success', __('Allowance successfully updated.'));
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
    public function destroy(Allowance $allowance)
    {
        if (Auth::user()->isAbleTo('allowance delete')) {
            if ($allowance->created_by == creatorId() && $allowance->workspace == getActiveWorkSpace()) {
                event(new DestroyAllowance($allowance));

                $allowance->delete();

                return redirect()->back()->with('success', __('Allowance successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
