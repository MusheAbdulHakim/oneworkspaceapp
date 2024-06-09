<?php

namespace Modules\Hrm\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\AllowanceTax;
use Modules\Hrm\Events\CreateAllowanceTax;
use Modules\Hrm\Events\DestroyAllowanceTax;
use Modules\Hrm\Events\UpdateAllowanceTax;

class AllowanceTaxController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('allowance tax manage')) {
            $allowancetaxs = AllowanceTax::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();

            return view('hrm::allowancetax.index', compact('allowancetaxs'));
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
        if (Auth::user()->isAbleTo('allowance tax create')) {
            return view('hrm::allowancetax.create');
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
        if (Auth::user()->isAbleTo('allowance tax create')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'description' => 'required',
                    'amount' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $allowancetax              = new AllowanceTax();
            $allowancetax->description = $request->description;
            $allowancetax->amount      = $request->amount;
            $allowancetax->workspace   = getActiveWorkSpace();
            $allowancetax->created_by  = creatorId();
            $allowancetax->save();

            event(new CreateAllowanceTax($request, $allowancetax));

            return redirect()->route('allowancetax.index')->with('success', __('Allowance Tax successfully created.'));
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
    public function edit(AllowanceTax $allowancetax)
    {
        if (Auth::user()->isAbleTo('allowance tax edit')) {
            if ($allowancetax->created_by == creatorId() && $allowancetax->workspace == getActiveWorkSpace()) {
                return view('hrm::allowancetax.edit', compact('allowancetax'));
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
    public function update(Request $request, AllowanceTax $allowancetax)
    {
        if (Auth::user()->isAbleTo('allowance tax edit')) {
            if ($allowancetax->created_by == creatorId() && $allowancetax->workspace == getActiveWorkSpace()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'description' => 'required',
                        'amount' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
                $allowancetax->description = $request->description;
                $allowancetax->amount      = $request->amount;
                $allowancetax->save();

                event(new UpdateAllowanceTax($request, $allowancetax));

                return redirect()->route('allowancetax.index')->with('success', __('Allowance Tax successfully updated.'));
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
    public function destroy(AllowanceTax $allowancetax)
    {
        if (Auth::user()->isAbleTo('allowance tax delete')) {
            if ($allowancetax->created_by == creatorId() && $allowancetax->workspace == getActiveWorkSpace()) {

                event(new DestroyAllowanceTax($allowancetax));

                $allowancetax->delete();

                return redirect()->route('allowancetax.index')->with('success', __('Allowance Tax successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
