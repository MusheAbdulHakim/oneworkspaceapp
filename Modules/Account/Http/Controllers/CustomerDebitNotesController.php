<?php

namespace Modules\Account\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Account\Entities\AccountUtility;
use Modules\Account\Entities\Bill;
use Modules\Account\Entities\CustomerDebitNotes;
use Modules\Account\Entities\Vender;

class CustomerDebitNotesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

        if(\Auth::user()->isAbleTo('debitnote manage'))
        {
            $bills = Bill::where('created_by', creatorId())->get();

            return view('account::customerDebitNote.index', compact('bills'));

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('account::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('account::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($bill_id, $debitNote_id)
    {
        if(\Auth::user()->isAbleTo('debitnote edit'))
        {
            $debitNote = CustomerDebitNotes::find($debitNote_id);
            $statues   = CustomerDebitNotes :: $statues;

            return view('account::customerDebitNote.edit', compact('debitNote','statues'));
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
    public function update(Request $request, $bill_id, $debitNote_id)
    {
        if(\Auth::user()->isAbleTo('debitnote edit'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'amount' => 'required|numeric|gte:0',
                                   'date' => 'required|date_format:Y-m-d',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $billDue = Bill::where('id', $bill_id)->first();

            if($request->amount > $billDue->getDue())
            {
                return redirect()->back()->with('error', 'Maximum ' . currency_format_with_sym($billDue->getDue()) . ' credit limit of this bill.');
            }

            $debit = CustomerDebitNotes::find($debitNote_id);
            AccountUtility::userBalance('vendor', $billDue->vendor_id, $debit->amount, 'credit');

            // store debitnote balance vendor's table
            AccountUtility::updateDebitnoteBalance('vendor', $billDue->vendor_id, $debit->amount, 'credit');

            $debit->date        = $request->date;
            $debit->amount      = $request->amount;
            $debit->description = $request->description;
            $debit->save();
            AccountUtility::userBalance('vendor', $billDue->vendor_id, $request->amount, 'debit');

            AccountUtility::updateDebitnoteBalance('vendor', $billDue->vendor_id, $request->amount, 'debit');

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

    public function destroy($bill_id, $debitNote_id)
    {
        if(\Auth::user()->isAbleTo('debitnote delete'))
        {
            $debitNote = CustomerDebitNotes::find($debitNote_id);
            $debitNote->delete();
            AccountUtility::userBalance('vendor', $debitNote->vendor, $debitNote->amount, 'credit');

            // store debitnote balance vendor's table
            AccountUtility::updateDebitnoteBalance('vendor', $debitNote->vendor, $debitNote->amount, 'credit');
            return redirect()->back()->with('success', __('Debit Note successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function customCreate()
    {
        if(\Auth::user()->isAbleTo('debitnote create'))
        {

            $bills = Bill::where('created_by', creatorId())->get()->pluck('bill_id', 'id');

            $statues = CustomerDebitNotes :: $statues;
            return view('account::customerDebitNote.custom_create', compact('bills','statues'));

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    // CustomerDebitNotes


    public function customStore(Request $request)
    {
        if(\Auth::user()->isAbleTo('debitnote create'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'bill' => 'required|numeric',
                                   'amount' => 'required|numeric|gte:0',
                                   'date' => 'required|date_format:Y-m-d',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $bill_id = $request->bill;
            $billDue = Bill::where('id', $bill_id)->first();

            if($request->amount > $billDue->getDue())
            {
                return redirect()->back()->with('error', 'Maximum ' . currency_format_with_sym($billDue->getDue()) . ' credit limit of this bill.');
            }
            $bill               = Bill::where('id', $bill_id)->first();
            $vendor             = Vender::where('user_id',$bill->user_id)->first();

            if(!empty($vendor))
            {
                $debit              = new CustomerDebitNotes();
                $debit->bill        = $bill_id;
                $debit->vendor      = $vendor->vendor_id;
                $debit->date        = $request->date;
                $debit->amount      = $request->amount;
                $debit->status      = $request->status;
                $debit->description = $request->description;
                $debit->save();

                AccountUtility::updateUserBalance('vendor', $bill->vendor_id, $request->amount, 'credit');

                // store debitnote balance vendor's table
                AccountUtility::updateDebitnoteBalance('vendor', $vendor->vendor_id, $request->amount, 'debit');

                return redirect()->back()->with('success', __('Debit Note successfully created.'));
            }
            else
            {
                return redirect()->back()->with('error', __('User is not converted into vendor.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getbill(Request $request)
    {
        $bill = Bill::where('id', $request->bill_id)->first();
        echo json_encode($bill->getDue());
    }

}
