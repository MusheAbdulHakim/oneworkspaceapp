<?php

namespace Modules\Hrm\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\DeductionOption;
use Modules\Hrm\Entities\SaturationDeduction;
use Modules\Hrm\Events\CreateDeductionOption;
use Modules\Hrm\Events\DestroyDeductionOption;
use Modules\Hrm\Events\UpdateDeductionOption;

class DeductionOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('deductionoption manage')) {
            $deductionoptions = DeductionOption::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();

            return view('hrm::deductionoption.index', compact('deductionoptions'));
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
        if (Auth::user()->isAbleTo('deductionoption create')) {
            return view('hrm::deductionoption.create');
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
        if (Auth::user()->isAbleTo('deductionoption create')) {

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

            $deductionoption             = new DeductionOption();
            $deductionoption->name       = $request->name;
            $deductionoption->workspace  = getActiveWorkSpace();
            $deductionoption->created_by = creatorId();
            $deductionoption->save();

            event(new CreateDeductionOption($request, $deductionoption));

            return redirect()->route('deductionoption.index')->with('success', __('Deduction Option  successfully created.'));
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
    public function edit(DeductionOption $deductionoption)
    {
        if (Auth::user()->isAbleTo('deductionoption edit')) {
            if ($deductionoption->created_by == creatorId() && $deductionoption->workspace == getActiveWorkSpace()) {
                return view('hrm::deductionoption.edit', compact('deductionoption'));
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
    public function update(Request $request, DeductionOption $deductionoption)
    {
        if (Auth::user()->isAbleTo('deductionoption edit')) {
            if ($deductionoption->created_by == creatorId() && $deductionoption->workspace == getActiveWorkSpace()) {
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
                $deductionoption->name = $request->name;
                $deductionoption->save();

                event(new UpdateDeductionOption($request, $deductionoption));

                return redirect()->route('deductionoption.index')->with('success', __('Deduction Option successfully updated.'));
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
    public function destroy(DeductionOption $deductionoption)
    {
        if (Auth::user()->isAbleTo('deductionoption delete')) {
            if ($deductionoption->created_by == creatorId() && $deductionoption->workspace == getActiveWorkSpace()) {
                $saturationdeduction  = SaturationDeduction::where('deduction_option', $deductionoption->id)->where('workspace', getActiveWorkSpace())->get();
                if (count($saturationdeduction) == 0) {
                    event(new DestroyDeductionOption($deductionoption));

                    $deductionoption->delete();
                } else {
                    return redirect()->route('deductionoption.index')->with('error', __('This Deduction Option has Saturation Deduction. Please remove the Saturation Deduction from this Deduction option.'));
                }

                return redirect()->route('deductionoption.index')->with('success', __('Deduction Option successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
