<?php

namespace Modules\Account\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Account\Entities\AccountUtility;
use Modules\Account\Entities\Bill;
use Modules\Account\Entities\DebitNote;
use Modules\Account\Entities\Vender;

class DebitNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('account::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($bill_id)
    {
        if (Auth::user()->isAbleTo('debitnote create')) {
            $billDue = Bill::where('id', $bill_id)->first();
            $vendor  = Vender::where('user_id', $billDue->user_id)->first();

            return view('account::debitNote.create', compact('billDue', 'bill_id','vendor'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $bill_id)
    {
        if (Auth::user()->isAbleTo('debitnote create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'date' => 'required|date_format:Y-m-d',
                    'amount' => 'required|numeric|gte:0',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $billDue = Bill::where('id', $bill_id)->first();
            $vendor  = Vender::where('user_id', $billDue->user_id)->first();

            if($request->amount > $vendor->debit_note_balance) {
            return redirect()->back()->with('error', 'Maximum ' .currency_format_with_sym($vendor->debit_note_balance) . ' debit limit of this bill.');

            }

            $debit              = new DebitNote();
            $debit->bill        = $bill_id;
            $debit->vendor      = $vendor->vendor_id;
            $debit->date        = $request->date;
            $debit->amount      = $request->amount;
            $debit->description = $request->description;
            $debit->save();

            AccountUtility::userBalance('vendor', $billDue->vendor_id, $request->amount, 'debit');
            // store debitnote balance vendor's table
            AccountUtility::updateDebitnoteBalance('vendor', $vendor->vendor_id, $request->amount, 'credit');

            return redirect()->back()->with('success', __('Debit Note successfully created.'));
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
        return view('account::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($bill_id, $debitNote_id)
    {
        if (Auth::user()->isAbleTo('debitnote edit')) {
            $debitNote = DebitNote::find($debitNote_id);

            return view('account::debitNote.edit', compact('debitNote'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $bill_id, $debitNote_id)
    {
        if (Auth::user()->isAbleTo('debitnote edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'amount' => 'required|numeric|gte:0',
                    'date' => 'required|date_format:Y-m-d',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $billDue = Bill::where('id', $bill_id)->first();

            $debit = DebitNote::find($debitNote_id);

            $vendor         = Vender::where('user_id', $billDue->user_id)->first();


            if ($request->amount > $vendor->debit_note_balance) {
                return redirect()->back()->with('error', 'Maximum ' . currency_format_with_sym($billDue->getDue()) . ' debit limit of this bill.');
            }

            AccountUtility::updateUserBalance('vendor', $billDue->vendor_id, $debit->amount, 'credit');

            // store creditnote customer's table
            if ($request->amount < $debit->amount) {
                $amount = $debit->amount - $request->amount;
                AccountUtility::updateDebitnoteBalance('vendor', $vendor->vendor_id, $amount, 'debit');
            }
            if ($request->amount > $debit->amount) {
                $amount = $request->amount - $debit->amount;
                AccountUtility::updateDebitnoteBalance('vendor', $vendor->vendor_id, $amount, 'credit');
            }

            $debit->date        = $request->date;
            $debit->amount      = $request->amount;
            $debit->description = $request->description;
            $debit->save();

            AccountUtility::userBalance('vendor', $billDue->vendor_id, $request->amount, 'debit');


            return redirect()->back()->with('success', __('Debit Note successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($bill_id, $debitNote_id)
    {
        if (Auth::user()->isAbleTo('debitnote delete')) {
            $debitNote = DebitNote::find($debitNote_id);
            $debitNote->delete();
            AccountUtility::userBalance('vendor', $debitNote->vendor, $debitNote->amount, 'credit');

            // store debitnote balance vendor's table
            AccountUtility::updateDebitnoteBalance('vendor', $debitNote->vendor, $debitNote->amount, 'debit');


            return redirect()->back()->with('success', __('Debit Note successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
