<?php

namespace Modules\Hrm\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Loan;
use Modules\Hrm\Entities\LoanOption;
use Modules\Hrm\Events\CreateLoanOption;
use Modules\Hrm\Events\DestroyLoanOption;
use Modules\Hrm\Events\UpdateLoanOption;

class LoanOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('loanoption manage')) {
            $loanoptions = LoanOption::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();

            return view('hrm::loanoption.index', compact('loanoptions'));
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
        if (Auth::user()->isAbleTo('loanoption create')) {
            return view('hrm::loanoption.create');
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
        if (\Auth::user()->isAbleTo('loanoption create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:20',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $loanoption             = new LoanOption();
            $loanoption->name       = $request->name;
            $loanoption->workspace  = getActiveWorkSpace();
            $loanoption->created_by = creatorId();
            $loanoption->save();

            event(new CreateLoanOption($request, $loanoption));

            return redirect()->route('loanoption.index')->with('success', __('Loan Option  successfully created.'));
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
    public function edit(LoanOption $loanoption)
    {
        if (Auth::user()->isAbleTo('loanoption edit')) {
            if ($loanoption->created_by == creatorId() && $loanoption->workspace == getActiveWorkSpace()) {
                return view('hrm::loanoption.edit', compact('loanoption'));
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
    public function update(Request $request, LoanOption $loanoption)
    {
        if (Auth::user()->isAbleTo('loanoption edit')) {
            if ($loanoption->created_by == creatorId() && $loanoption->workspace == getActiveWorkSpace()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:20',

                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
                $loanoption->name = $request->name;
                $loanoption->save();

                event(new UpdateLoanOption($request, $loanoption));

                return redirect()->route('loanoption.index')->with('success', __('Loan Option successfully updated.'));
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
    public function destroy(LoanOption $loanoption)
    {
        if (Auth::user()->isAbleTo('loanoption delete')) {
            if ($loanoption->created_by == creatorId() && $loanoption->workspace == getActiveWorkSpace()) {
                $loan     = Loan::where('loan_option', $loanoption->id)->where('workspace', getActiveWorkSpace())->get();
                if (count($loan) == 0) {
                    event(new DestroyLoanOption($loanoption));

                    $loanoption->delete();
                } else {
                    return redirect()->route('loanoption.index')->with('error', __('This Loan Option has Loan. Please remove the Loan from this Loan option.'));
                }

                return redirect()->route('loanoption.index')->with('success', __('Loan Option successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
