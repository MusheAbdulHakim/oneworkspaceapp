<?php

namespace Modules\Account\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Account\Entities\CustomerCreditNotes;
use App\Models\Invoice;
use App\Models\User;
use Modules\Account\Entities\AccountUtility;
use Modules\Account\Entities\Customer;

class CustomerCreditNotesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
     public function index()
    {

        if(Auth::user()->isAbleTo('creditnote manage'))
        {
            $invoices = Invoice::where('created_by', creatorId())->get();

            return view('account::customerCreditNote.index', compact('invoices'));
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

    public function create($invoice_id)
    {

        if(Auth::user()->isAbleTo('creditnote create'))
        {

            $invoiceDue = Invoice::where('id', $invoice_id)->first();

            return view('account::customerCreditNote.create', compact('invoiceDue', 'invoice_id'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */

    public function store(Request $request, $invoice_id)
    {
        if(\Auth::user()->isAbleTo('creditnote create'))
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
            $invoiceDue = Invoice::where('id', $invoice_id)->first();
            if($request->amount > $invoiceDue->getDue())
            {
                return redirect()->back()->with('error', 'Maximum ' . \Auth::user()->priceFormat($invoiceDue->getDue()) . ' credit limit of this invoice.');
            }
            $invoice = Invoice::where('id', $invoice_id)->first();

            $credit              = new CustomerCreditNotes();
            $credit->invoice     = $invoice_id;
            $credit->customer    = $invoice->customer_id;
            $credit->date        = $request->date;
            $credit->amount      = $request->amount;
            $credit->description = $request->description;
            $credit->save();
            AccountUtility::updateUserBalance('customer', $invoice->customer_id, $request->amount, 'debit');

            return redirect()->back()->with('success', __('Credit Note successfully created.'));
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
        return view('account::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */


    public function edit($invoice_id, $creditNote_id)
    {
        if(\Auth::user()->isAbleTo('creditnote edit'))
        {

            $creditNote = CustomerCreditNotes::find($creditNote_id);
            $statues = CustomerCreditNotes :: $statues;
            return view('account::customerCreditNote.edit', compact('creditNote','statues'));
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
    public function update(Request $request, $invoice_id, $creditNote_id)
    {

        if(\Auth::user()->isAbleTo('creditnote edit'))
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

            // $billDue = Bill::where('id', $bill_id)->first();
            $invoiceDue = Invoice::where('id', $invoice_id)->first();
            $credit = CustomerCreditNotes::find($creditNote_id);
            if($request->amount > $invoiceDue->getDue()+$credit->amount)
            {
                return redirect()->back()->with('error', 'Maximum ' . currency_format_with_sym($invoiceDue->getDue()) . ' credit limit of this invoice.');
            }


            AccountUtility::updateUserBalance('customer', $invoiceDue->customer_id, $credit->amount, 'credit');

             // store creditnote customer's table
            AccountUtility::updateCreditnoteBalance('customer', $invoiceDue->customer_id, $credit->amount, 'credit');


            $credit->date        = $request->date;
            $credit->amount      = $request->amount;
            $credit->status      = $request->status;
            $credit->description = $request->description;
            $credit->save();

            AccountUtility::updateUserBalance('customer', $invoiceDue->customer_id, $request->amount, 'debit');

            // store creditnote customer's table
            AccountUtility::updateCreditnoteBalance('customer', $invoiceDue->customer_id, $request->amount, 'debit');

            return redirect()->back()->with('success', __('Credit Note successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy($invoice_id, $creditNote_id)
    {
        if(\Auth::user()->isAbleTo('creditnote delete'))
        {

            $creditNote = CustomerCreditNotes::find($creditNote_id);
            $creditNote->delete();

            AccountUtility::updateUserBalance('customer', $creditNote->customer, $creditNote->amount, 'credit');

            // store creditnote customer's table
            AccountUtility::updateCreditnoteBalance('customer', $creditNote->customer, $creditNote->amount, 'credit');

            return redirect()->back()->with('success', __('Credit Note successfully deleted.'));

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function customCreate()
    {

        if(\Auth::user()->isAbleTo('creditnote create'))
        {
            $invoices = Invoice::where('created_by', creatorId())->get()->pluck('invoice_id', 'id');
            $statues = CustomerCreditNotes :: $statues;
            return view('account::customerCreditNote.custom_create', compact('invoices','statues'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function customStore(Request $request)
    {
        if(\Auth::user()->isAbleTo('creditnote create'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'invoice' => 'required|numeric',
                                   'amount' => 'required|numeric',
                                   'date' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $invoice_id = $request->invoice;
            $invoiceDue = Invoice::where('id', $invoice_id)->first();
            if($invoiceDue){
                if($request->amount > $invoiceDue->getDue())
                {
                    return redirect()->back()->with('error', 'Maximum ' . currency_format_with_sym($invoiceDue->getDue()) . ' credit limit of this invoice.');
                }
                $invoice             = Invoice::where('id', $invoice_id)->first();
                $customer = Customer::where('customer_id', '=', $invoice->customer_id)->first();
                if(empty($customer)){
                    $customer = User::find($invoice->customer_id);
                }
                if(!empty($customer))
                {
                    $credit              = new CustomerCreditNotes();
                    $credit->invoice     = $invoice_id;
                    $credit->customer    = $customer->id;
                    $credit->date        = $request->date;
                    $credit->amount      = $request->amount;
                    $credit->status      = $request->status;
                    $credit->description = $request->description;
                    $credit->save();

                    AccountUtility::updateUserBalance('customer', $invoice->customer_id, $request->amount, 'debit');

                    // store creditnote customer's table
                    AccountUtility::updateCreditnoteBalance('customer', $customer->id, $request->amount, 'debit');

                    return redirect()->route('custom-credit.note')->with('success', __('Credit Note successfully created.'));
                }
                else
                {
                    return redirect()->back()->with('error', __('User is not converted into customer.'));
                }
            }else{
                return redirect()->back()->with('error', __('The invoice field is required.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getinvoice(Request $request)
    {
        $invoice = Invoice::where('id', $request->id)->first();

        echo json_encode($invoice->getDue());
    }
}
