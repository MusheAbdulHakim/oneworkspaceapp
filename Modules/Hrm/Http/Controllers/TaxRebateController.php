<?php

namespace Modules\Hrm\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\TaxRebate;
use Modules\Hrm\Events\CreateTaxRebate;
use Modules\Hrm\Events\DestroyTaxRebate;
use Modules\Hrm\Events\UpdateTaxRebate;

class TaxRebateController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('tax rebate manage')) {
            $taxrebates = TaxRebate::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();

            return view('hrm::taxrebate.index', compact('taxrebates'));
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
        if (Auth::user()->isAbleTo('tax rebate create')) {
            return view('hrm::taxrebate.create');
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
        if (Auth::user()->isAbleTo('tax rebate create')) {

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

            $taxrebate              = new TaxRebate();
            $taxrebate->description = $request->description;
            $taxrebate->amount      = $request->amount;
            $taxrebate->workspace   = getActiveWorkSpace();
            $taxrebate->created_by  = creatorId();
            $taxrebate->save();

            event(new CreateTaxRebate($request, $taxrebate));

            return redirect()->route('taxrebate.index')->with('success', __('Tax Rebate successfully created.'));
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
    public function edit(TaxRebate $taxrebate)
    {
        if (Auth::user()->isAbleTo('tax rebate edit')) {
            if ($taxrebate->created_by == creatorId() && $taxrebate->workspace == getActiveWorkSpace()) {
                return view('hrm::taxrebate.edit', compact('taxrebate'));
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
    public function update(Request $request, TaxRebate $taxrebate)
    {
        if (Auth::user()->isAbleTo('tax rebate edit')) {
            if ($taxrebate->created_by == creatorId() && $taxrebate->workspace == getActiveWorkSpace()) {
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
                $taxrebate->description = $request->description;
                $taxrebate->amount      = $request->amount;
                $taxrebate->save();

                event(new UpdateTaxRebate($request, $taxrebate));

                return redirect()->route('taxrebate.index')->with('success', __('Tax Rebate successfully updated.'));
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
    public function destroy(TaxRebate $taxrebate)
    {
        if (Auth::user()->isAbleTo('tax rebate delete')) {
            if ($taxrebate->created_by == creatorId() && $taxrebate->workspace == getActiveWorkSpace()) {

                event(new DestroyTaxRebate($taxrebate));

                $taxrebate->delete();

                return redirect()->route('taxrebate.index')->with('success', __('Tax Rebate successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
