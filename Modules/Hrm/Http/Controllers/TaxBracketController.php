<?php

namespace Modules\Hrm\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\TaxBracket;
use Modules\Hrm\Events\CreateTaxBracket;
use Modules\Hrm\Events\DestroyTaxBracket;
use Modules\Hrm\Events\UpdateTaxBracket;

class TaxBracketController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('tax bracket manage')) {
            $taxbrackets = TaxBracket::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();

            return view('hrm::taxbracket.index', compact('taxbrackets'));
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
        if (Auth::user()->isAbleTo('tax bracket create')) {
            return view('hrm::taxbracket.create');
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
        if (Auth::user()->isAbleTo('tax bracket create')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'from' => 'required',
                    'to' => 'required',
                    'fixed_amount' => 'required',
                    'percentage' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $taxbracket               = new TaxBracket();
            $taxbracket->from         = $request->from;
            $taxbracket->to           = $request->to;
            $taxbracket->fixed_amount = $request->fixed_amount;
            $taxbracket->percentage   = $request->percentage;
            $taxbracket->workspace    = getActiveWorkSpace();
            $taxbracket->created_by   = creatorId();
            $taxbracket->save();

            event(new CreateTaxBracket($request, $taxbracket));

            return redirect()->route('taxbracket.index')->with('success', __('Tax Bracket successfully created.'));
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
    public function edit(TaxBracket $taxbracket)
    {
        if (Auth::user()->isAbleTo('tax bracket edit')) {
            if ($taxbracket->created_by == creatorId() && $taxbracket->workspace == getActiveWorkSpace()) {
                return view('hrm::taxbracket.edit', compact('taxbracket'));
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
    public function update(Request $request, TaxBracket $taxbracket)
    {
        if (Auth::user()->isAbleTo('tax bracket edit')) {
            if ($taxbracket->created_by == creatorId() && $taxbracket->workspace == getActiveWorkSpace()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'from' => 'required',
                        'to' => 'required',
                        'fixed_amount' => 'required',
                        'percentage' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
                $taxbracket->from         = $request->from;
                $taxbracket->to           = $request->to;
                $taxbracket->fixed_amount = $request->fixed_amount;
                $taxbracket->percentage   = $request->percentage;
                $taxbracket->save();

                event(new UpdateTaxBracket($request, $taxbracket));

                return redirect()->route('taxbracket.index')->with('success', __('Tax Bracket successfully updated.'));
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
    public function destroy(TaxBracket $taxbracket)
    {
        if (Auth::user()->isAbleTo('tax bracket delete')) {
            if ($taxbracket->created_by == creatorId() && $taxbracket->workspace == getActiveWorkSpace()) {

                event(new DestroyTaxBracket($taxbracket));

                $taxbracket->delete();

                return redirect()->route('taxbracket.index')->with('success', __('Tax Bracket successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
