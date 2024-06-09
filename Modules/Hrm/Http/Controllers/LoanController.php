<?php

namespace Modules\Hrm\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Employee;
use Modules\Hrm\Entities\Loan;
use Modules\Hrm\Entities\LoanOption;
use Modules\Hrm\Events\CreateLoan;
use Modules\Hrm\Events\DestroyLoan;
use Modules\Hrm\Events\UpdateLoan;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function loanCreate($id)
    {
        if (Auth::user()->isAbleTo('loan create')) {
            $employee = Employee::find($id);
            $loan_options = LoanOption::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');

            $loan = Loan::$Loantypes;
            return view('hrm::loan.create', compact('employee', 'loan_options', 'loan'));
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
        if (Auth::user()->isAbleTo('loan create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'employee_id' => 'required',
                    'loan_option' => 'required',
                    'title' => 'required',
                    'type' => 'required',
                    'amount' => 'required|numeric|min:0',
                    'start_date' => 'required',
                    'end_date' => 'required|after_or_equal:start_date',
                    'reason' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $loan              = new Loan();
            $loan->employee_id = $request->employee_id;
            $loan->loan_option = $request->loan_option;
            $loan->title       = $request->title;
            $loan->amount      = $request->amount;
            $loan->type        = $request->type;
            $loan->start_date  = $request->start_date;
            $loan->end_date    = $request->end_date;
            $loan->reason      = $request->reason;
            $loan->workspace   = getActiveWorkSpace();
            $loan->created_by  = creatorId();
            $loan->save();

            event(new CreateLoan($request, $loan));

            return redirect()->back()->with('success', __('Loan  successfully created.'));
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
    public function edit(Loan $loan)
    {
        if (\Auth::user()->isAbleTo('loan edit')) {
            if ($loan->created_by == creatorId() && $loan->workspace == getActiveWorkSpace()) {
                $loan_options = LoanOption::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                $loans = loan::$Loantypes;
                return view('hrm::loan.edit', compact('loan', 'loan_options', 'loans'));
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
    public function update(Request $request, Loan $loan)
    {
        if (\Auth::user()->isAbleTo('loan edit')) {
            if ($loan->created_by == creatorId() && $loan->workspace == getActiveWorkSpace()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'loan_option' => 'required',
                        'title' => 'required',
                        'type' => 'required',
                        'amount' => 'required|numeric|min:0',
                        'start_date' => 'required',
                        'end_date' => 'required|after_or_equal:start_date',
                        'reason' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
                $loan->loan_option = $request->loan_option;
                $loan->title       = $request->title;
                $loan->type        = $request->type;
                $loan->amount      = $request->amount;
                $loan->start_date  = $request->start_date;
                $loan->end_date    = $request->end_date;
                $loan->reason      = $request->reason;
                $loan->save();

                event(new UpdateLoan($request, $loan));

                return redirect()->back()->with('success', __('Loan successfully updated.'));
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
    public function destroy(Loan $loan)
    {
        if (Auth::user()->isAbleTo('loan delete')) {
            if ($loan->created_by == creatorId() && $loan->workspace == getActiveWorkSpace()) {
                event(new DestroyLoan($loan));

                $loan->delete();

                return redirect()->back()->with('success', __('Loan successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
