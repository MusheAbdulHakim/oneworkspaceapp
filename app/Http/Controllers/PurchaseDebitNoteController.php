<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Purchase;
use App\Models\PurchaseDebitNote;

class PurchaseDebitNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('purchases.index');

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($purchase_id)
    {
        if(\Auth::user()->isAbleTo('purchase debitnote create'))
        {
            $purchaseDue = Purchase::where('id', $purchase_id)->first();

            return view('debitNote.create', compact('purchaseDue', 'purchase_id'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $purchase_id)
    {
        if(Auth::user()->isAbleTo('purchase debitnote create'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required',
                                   'amount' => 'required|numeric',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $purchaseDue = Purchase::where('id', $purchase_id)->first();
            if($request->amount > $purchaseDue->getDue())
            {
                return redirect()->back()->with('error', 'Maximum ' . currency_format_with_sym($purchaseDue->getDue()) . ' credit limit of this purchase.');
            }
            $debit              = new PurchaseDebitNote();
            $debit->purchase    = $purchase_id;
            $debit->vendor      = $purchaseDue->vender_id;
            $debit->date        = $request->date;
            $debit->amount      = $request->amount;
            $debit->description = $request->description;
            $debit->save();

            PurchaseDebitNote::userBalance('vender', $purchaseDue->vender_id, $request->amount, 'debit');

            return redirect()->back()->with('success', __('Debit Note successfully created.'));
        }
        else
        {
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
        return view('pos::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($purchase_id, $debitNote_id)
    {
        if(Auth::user()->isAbleTo('purchase debitnote edit'))
        {
            $debitNote = PurchaseDebitNote::find($debitNote_id);

            return view('debitNote.edit', compact('debitNote'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $purchase_id, $debitNote_id)
    {
        if(Auth::user()->isAbleTo('purchase debitnote edit'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'amount' => 'required|numeric',
                                   'date' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $purchaseDue = Purchase::where('id', $purchase_id)->first();
            if($request->amount > $purchaseDue->getDue())
            {
                return redirect()->back()->with('error', 'Maximum ' . currency_format_with_sym($purchaseDue->getDue()) . ' credit limit of this purchase.');
            }
            $debit = PurchaseDebitNote::find($debitNote_id);
            PurchaseDebitNote::userBalance('vender', $purchaseDue->vender_id, $debit->amount, 'credit');

            $debit->date        = $request->date;
            $debit->amount      = $request->amount;
            $debit->description = $request->description;
            $debit->save();
            PurchaseDebitNote::userBalance('vender', $purchaseDue->vender_id, $request->amount, 'debit');

            return redirect()->back()->with('success', __('Debit Note successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($purchase_id, $debitNote_id)
    {
        if(Auth::user()->isAbleTo('purchase debitnote delete'))
        {
            $debitNote = PurchaseDebitNote::find($debitNote_id);
            $debitNote->delete();
            PurchaseDebitNote::userBalance('vender', $debitNote->vender, $debitNote->amount, 'credit');
            return redirect()->back()->with('success', __('Debit Note successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}

