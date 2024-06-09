<?php

namespace Modules\Hrm\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Employee;
use Modules\Hrm\Entities\OtherPayment;
use Modules\Hrm\Events\CreateOtherPayment;
use Modules\Hrm\Events\DestroyOtherPayment;
use Modules\Hrm\Events\UpdateOtherPayment;

class OtherPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function otherpaymentCreate($id)
    {
        if (Auth::user()->isAbleTo('other payment create')) {
            $employee = Employee::find($id);
            $otherpaytype = OtherPayment::$otherPaymenttype;
            return view('hrm::otherpayment.create', compact('employee', 'otherpaytype'));
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
        if (Auth::user()->isAbleTo('other payment create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'employee_id' => 'required',
                    'title' => 'required',
                    'type' => 'required',
                    'amount' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $otherpayment              = new OtherPayment();
            $otherpayment->employee_id = $request->employee_id;
            $otherpayment->title       = $request->title;
            $otherpayment->type        = $request->type;
            $otherpayment->amount      = $request->amount;
            $otherpayment->workspace   = getActiveWorkSpace();
            $otherpayment->created_by  = creatorId();
            $otherpayment->save();

            event(new CreateOtherPayment($request, $otherpayment));

            return redirect()->back()->with('success', __('OtherPayment  successfully created.'));
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
    public function edit(OtherPayment $otherpayment)
    {
        if (Auth::user()->isAbleTo('other payment edit')) {
            if ($otherpayment->created_by == creatorId() && $otherpayment->workspace == getActiveWorkSpace()) {
                $otherpaytype = OtherPayment::$otherPaymenttype;
                return view('hrm::otherpayment.edit', compact('otherpayment', 'otherpaytype'));
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
    public function update(Request $request, OtherPayment $otherpayment)
    {
        if (Auth::user()->isAbleTo('other payment edit')) {
            if ($otherpayment->created_by == creatorId() && $otherpayment->workspace == getActiveWorkSpace()) {
                $validator = \Validator::make(
                    $request->all(),
                    [

                        'title' => 'required',
                        'type' => 'required',
                        'amount' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $otherpayment->title  = $request->title;
                $otherpayment->type   = $request->type;
                $otherpayment->amount = $request->amount;
                $otherpayment->save();

                event(new UpdateOtherPayment($request, $otherpayment));

                return redirect()->back()->with('success', __('OtherPayment successfully updated.'));
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
    public function destroy(OtherPayment $otherpayment)
    {
        if (Auth::user()->isAbleTo('other payment delete')) {
            if ($otherpayment->created_by == creatorId() && $otherpayment->workspace == getActiveWorkSpace()) {
                event(new DestroyOtherPayment($otherpayment));

                $otherpayment->delete();

                return redirect()->back()->with('success', __('OtherPayment successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
