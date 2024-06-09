<?php

namespace Modules\Account\Http\Controllers;

use App\Models\BankTransferPayment;
use App\Events\BankTransferPaymentStatus;
use App\Models\CustomField;
use App\Models\Invoice;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Account\Entities\AccountUtility;
use Modules\Account\Entities\BankAccount;
use Modules\Account\Entities\BillPayment;
use Illuminate\Support\Facades\Validator;
use App\Models\InvoicePayment;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Modules\Account\Entities\Payment;
use Modules\Account\Entities\Revenue;
use Modules\Account\Entities\Transaction;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\Events\CreateBankAccount;
use Modules\Account\Events\DestroyBankAccount;
use Modules\Account\Events\UpdateBankAccount;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('bank account manage')) {
            $accounts = DB::table('bank_accounts')
                ->select('bank_accounts.*', 'chart_of_accounts.name as chart_account_name')
                ->leftJoin('chart_of_accounts', 'bank_accounts.chart_account_id', '=', 'chart_of_accounts.id')
                ->where('bank_accounts.workspace', getActiveWorkSpace())
                ->get();
            return view('account::bankAccount.index', compact('accounts'));
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
        if (Auth::user()->isAbleTo('bank account create')) {
            $chartAccounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
                ->where('parent', '=', 0)
                ->where('workspace', getActiveWorkSpace())
                ->where('created_by', creatorId())->get()
                ->pluck('code_name', 'id');
            $chartAccounts->prepend('Select Account', 0);

            $subAccounts = ChartOfAccount::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name' , 'chart_of_account_parents.account')
                            ->leftjoin('chart_of_account_parents', 'chart_of_accounts.parent', 'chart_of_account_parents.id')
                            ->where('chart_of_accounts.parent', '!=', 0)
                            ->where('chart_of_accounts.workspace', getActiveWorkSpace())
                            ->where('chart_of_accounts.created_by', creatorId())
                            ->get()->toArray();

            return view('account::bankAccount.create',compact('chartAccounts','subAccounts'));
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
        if (Auth::user()->isAbleTo('bank account create')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'holder_name' => 'required',
                    'bank_type' => 'required',
                    'opening_balance' => 'required',
                    'bank_address' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('bank-account.index')->with('error', $messages->first());
            }
            $account                   = new BankAccount();
            $account->chart_account_id = $request->chart_account_id;
            $account->holder_name      = $request->holder_name;
            $account->bank_name        = $request->bank_name;
            $account->bank_type        = $request->bank_type;
            $account->wallet_type      = $request->wallet_type;
            $account->account_number   = $request->account_number;
            $account->opening_balance  = $request->opening_balance;
            $account->contact_number   = $request->contact_number;
            $account->bank_branch      = $request->bank_branch;
            $account->swift            = $request->swift;
            $account->bank_address     = $request->bank_address;
            $account->workspace        = getActiveWorkSpace();
            $account->created_by       = creatorId();
            $account->save();

            //start for opening balance add in chartOfAccount
            $data = [
                'account_id' => $account->chart_account_id,
                'transaction_type' => 'Credit',
                'transaction_amount' => $account->opening_balance,
                'reference' => 'Bank Account',
                'reference_id' => $account->id,
                'reference_sub_id' => 0,
                'date' => date('Y-m-d'),
            ];
            AccountUtility::addTransactionLines($data);
            //end for opening balance add in chartOfAccount

            event(new CreateBankAccount($request, $account));

            return redirect()->route('bank-account.index')->with('success', __('Account successfully created.'));
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
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('bank account edit')) {
           
            $bankAccount = BankAccount::where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->where('id',$id)->first();

            if(!$bankAccount){
                return response()->json(['error',__('Bank Account Not Found!')]);
            }

            $chartAccounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
                ->where('parent', '=', 0)
                ->where('workspace', getActiveWorkSpace())
                ->where('created_by', creatorId())->get()
                ->pluck('code_name', 'id');
            $chartAccounts->prepend('Select Account', 0);

            $subAccounts = ChartOfAccount::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name' , 'chart_of_account_parents.account')
                            ->leftjoin('chart_of_account_parents', 'chart_of_accounts.parent', 'chart_of_account_parents.id')
                            ->where('chart_of_accounts.parent', '!=', 0)
                            ->where('chart_of_accounts.workspace', getActiveWorkSpace())
                            ->where('chart_of_accounts.created_by', creatorId())
                            ->get()->toArray();

            return view('account::bankAccount.edit', compact('bankAccount','chartAccounts','subAccounts'));
           
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
    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('bank account edit')) {
            
            $validator = \Validator::make(
                $request->all(),
                [
                    'bank_type' => 'required',
                    'holder_name' => 'required',
                    'opening_balance' => 'required',
                    'bank_address' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('bank-account.index')->with('error', $messages->first());
            }

            $bankAccount = BankAccount::where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->where('id',$id)->first();

            if(!$bankAccount){
                return redirect()->route('bank-account.index')->with('error',__('Bank Account Not Found!'));
            }

            $bankAccount->chart_account_id = $request->chart_account_id;
            $bankAccount->holder_name     = $request->holder_name;
            $bankAccount->bank_name       = $request->bank_name;
            $bankAccount->bank_type     = $request->bank_type;
            $bankAccount->wallet_type     = $request->wallet_type;
            $bankAccount->account_number  = $request->account_number;
            $bankAccount->opening_balance = $request->opening_balance;
            $bankAccount->contact_number  = $request->contact_number;
            $bankAccount->bank_branch     = $request->bank_branch;
            $bankAccount->swift           = $request->swift;
            $bankAccount->bank_address    = $request->bank_address;
            $bankAccount->save();
            event(new UpdateBankAccount($request, $bankAccount));

            return redirect()->route('bank-account.index')->with('success', __('Account successfully updated.'));
            
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('bank account delete')) {
                $bankAccount = BankAccount::where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->where('id',$id)->first();

                if(!$bankAccount){
                    return redirect()->route('bank-account.index')->with('error',__('Bank Account Not Found!'));
                }

                $revenue        = Revenue::where('account_id', $bankAccount->id)->first();
                $invoicePayment = InvoicePayment::where('account_id', $bankAccount->id)->first();
                $transaction    = Transaction::where('account', $bankAccount->id)->first();
                $payment        = Payment::where('account_id', $bankAccount->id)->first();
                $billPayment    = BillPayment::first();

                if (!empty($revenue) || !empty($invoicePayment) || !empty($transaction) || !empty($payment) || !empty($billPayment)) {
                    return redirect()->route('bank-account.index')->with('error', __('Please delete related record of this account.'));
                } else {
                    event(new DestroyBankAccount($bankAccount));
                    $bankAccount->delete();

                    return redirect()->route('bank-account.index')->with('success', __('Account successfully deleted.'));
                }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function disableAccount(Request $request)
    {
        // Check if bank_account_payment_is_on is checked
        if ($request->has('bank_account_payment_is_on')) {
            $validator = Validator::make($request->all(), [
                'bank_account' => 'required|array',
            ]);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $post = $request->all();
            unset($post['_token']);
            unset($post['_method']);

            // Filter out the bank accounts with "off" value
            $onBankAccounts = array_filter($post['bank_account'], function ($value) {
                return $value === 'on';
            });

            // Get existing bank account IDs from the settings
            $existingBankAccounts = Setting::where('key', 'bank_account')
                ->where('workspace', getActiveWorkSpace())
                ->first();

            $existingIds = $existingBankAccounts ? explode(',', $existingBankAccounts->value) : [];

            // Combine the existing IDs with the selected "on" bank account IDs
            $updatedIds = array_merge($existingIds, array_keys($onBankAccounts));
            $uniqueIds = array_unique($updatedIds);

            $data = [
                'key' => 'bank_account',
                'workspace' => getActiveWorkSpace(),
                'created_by' => creatorId(),
            ];

            // Update or insert the bank account IDs as a comma-separated string
            Setting::updateOrInsert($data, ['value' => implode(',', $uniqueIds)]);

            // Update or insert the bank_account_payment_is_on value
            $paymentOnData = [
                'key' => 'bank_account_payment_is_on',
                'workspace' => getActiveWorkSpace(),
                'created_by' => creatorId(),
            ];

            $paymentOnValue = $request->has('bank_account_payment_is_on') ? 'on' : 'off';

            Setting::updateOrInsert($paymentOnData, ['value' => $paymentOnValue]);
        } else {
            // If bank_account_payment_is_on is not checked, set its value to 'off'
            $data = [
                'key' => 'bank_account_payment_is_on',
                'workspace' => getActiveWorkSpace(),
                'created_by' => creatorId(),
            ];

            Setting::updateOrInsert($data, ['value' => 'off']);
        }

        // Settings Cache forget
        comapnySettingCacheForget();
        return redirect()->back()->with('success', __('Bank Accounts Setting saved successfully'));
    }

    public function bankAccount(Request $request)
    {
        $bankaccounts = BankAccount::where('id', '=', $request->id)->first();
        if (!empty($bankaccounts)) {

            $bankaccounts['bank_name'] = !empty($bankaccounts->bank_name) ? $bankaccounts->bank_name : '';
            $bankaccounts['account_number'] = !empty($bankaccounts->account_number) ? $bankaccounts->account_number : '';
        }

        return view('account::bankaccount_detail', compact('bankaccounts'));
    }

    public function invoicePayWithBankAccount(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                                'paymentbank_receipt' => 'required',
                            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());

        }
        if($request->type == 'invoice'){
            $invoice = Invoice::where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->where('id',$request->invoice_id)->first();
        }
        elseif($request->type == 'salesinvoice'){

            $invoice = \Modules\Sales\Entities\SalesInvoice::where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->where('id',$request->invoice_id)->first();
        }
        elseif($request->type == 'retainer')
        {
            $invoice = \Modules\Retainer\Entities\Retainer::where('created_by',creatorId())->where('id',$request->invoice_id)->first();
        }
        if($invoice){
            $bank_transfer_payment  = new  BankTransferPayment();
            if (!empty($request->paymentbank_receipt))
            {
                $filenameWithExt = $request->file('paymentbank_receipt')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('paymentbank_receipt')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                $uplaod = upload_file($request,'paymentbank_receipt',$fileNameToStore,'bank_transfer');
                if($uplaod['flag'] == 1)
                {
                    $bank_transfer_payment->attachment = $uplaod['url'];
                }
                else
                {
                    return response()->json(
                        [
                            'status' => 'error',
                            'msg' => $uplaod['msg']
                        ]
                    );
                }
            }

            // customer_id
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            $bank_transfer_payment->order_id = $orderID;
            $bank_transfer_payment->user_id = $invoice->created_by;
            $bank_transfer_payment->request = $request->invoice_id;
            $bank_transfer_payment->status = 'Pending';
            $bank_transfer_payment->type = $request->type;
            $bank_transfer_payment->payment_type = $request->payment_type;
            $bank_transfer_payment->bank_accounts_id = $request->customer_id;
            $bank_transfer_payment->price = $request->amount;
            $bank_transfer_payment->price_currency  = company_setting('defult_currancy',$invoice->created_by,$invoice->workspace);
            $bank_transfer_payment->created_by = $invoice->created_by;
            $bank_transfer_payment->workspace = $invoice->workspace;
            $bank_transfer_payment->save();

            if($request->type == 'invoice')
            {
                return redirect()->route('pay.invoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('success', __('Invoice payment request send successfully').('<br> <span class="text-danger"> '.__('Your request will be approved by company and then your payment will be activated.').'</span>'));
            }
            elseif($request->type == 'salesinvoice')
            {
                return redirect()->route('pay.salesinvoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('success', __('Sales Invoice payment request send successfully').('<br> <span class="text-danger"> '.__('Your request will be approved by company and then your payment will be activated.').'</span>'));
            }
            elseif($request->type == 'retainer')
            {
                return redirect()->route('pay.retainer',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('success', __('Retainer payment request send successfully').('<br> <span class="text-danger"> '.__('Your request will be approved by company and then your payment will be activated.').'</span>'));
            }

        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function invoiceBankAccountRequestEdit($id)
    {
        $bank_transfer_payment = BankTransferPayment::where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->where('id',$id)->first();

        if($bank_transfer_payment)
        {
            $bank_account_id = $bank_transfer_payment->bank_accounts_id;
            $bank_account = '';
            if($bank_account_id != '0')
            {
                $bank_account = BankAccount::where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->where('id',$bank_account_id)->first();
            }

            if($bank_transfer_payment->type == 'invoice')
            {
                $invoice = Invoice::where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->where('id',$bank_transfer_payment->request)->first();
                $invoice_id = Invoice::invoiceNumberFormat($invoice->invoice_id);

            }
            elseif($bank_transfer_payment->type == 'salesinvoice')
            {
                $salesinvoice = \Modules\Sales\Entities\SalesInvoice::where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->where('id',$bank_transfer_payment->request)->first();
                $invoice_id = \Modules\Sales\Entities\SalesInvoice::invoiceNumberFormat($salesinvoice->invoice_id);

            }
            elseif($bank_transfer_payment->type == 'retainer')
            {
                $retainer = \Modules\Retainer\Entities\Retainer::where('created_by',creatorId())->where('id',$bank_transfer_payment->request)->first();
                $invoice_id = \Modules\Retainer\Entities\Retainer::retainerNumberFormat($retainer->retainer_id);
            }

            return view('account::payment.invoice_action', compact('bank_transfer_payment','invoice_id','bank_account'));
        }
        else
        {
            return response()->json(['error' => __('Request data not found!')], 401);
        }
    }

    public function invoiceBankAccountRequestupdate(Request $request, $id)
    {
        $bank_transfer_payment = BankTransferPayment::where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->where('id',$id)->first();

        if($bank_transfer_payment && $bank_transfer_payment->status == 'Pending')
        {
            $bank_transfer_payment->status = $request->status;
            $bank_transfer_payment->save();

            if($request->status == 'Approved')
            {
                $bank_account_id = $bank_transfer_payment->bank_accounts_id;
                $bank_account = BankAccount::where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->where('id',$bank_account_id)->first();

                if ($bank_account) {
                    // Update the opening balance in the BankAccount table
                    $bank_account->opening_balance += $bank_transfer_payment->price;
                    $bank_account->save();
                }

                if($bank_transfer_payment->type == 'invoice')
                {
                    $invoice = Invoice::where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->where('id',$bank_transfer_payment->request)->first();

                    $invoice_payment                 = new \App\Models\InvoicePayment();
                    $invoice_payment->invoice_id     = $bank_transfer_payment->request;
                    $invoice_payment->date           = Date('Y-m-d');
                    $invoice_payment->account_id     = $bank_transfer_payment->bank_accounts_id;
                    $invoice_payment->payment_method = 0;
                    $invoice_payment->amount         = $bank_transfer_payment->price;
                    $invoice_payment->order_id       = $bank_transfer_payment->order_id;
                    $invoice_payment->currency       = $bank_transfer_payment->price_currency;
                    $invoice_payment->payment_type   = 'Bank Account';
                    $invoice_payment->receipt        = $bank_transfer_payment->attachment;
                    $invoice_payment->save();

                    $due     = $invoice->getDue();
                    if ($due <= 0) {
                        $invoice->status = 4;
                        $invoice->save();
                    } else {
                        $invoice->status = 3;
                        $invoice->save();
                    }
                    event(new BankTransferPaymentStatus($invoice,$bank_transfer_payment->type,$invoice_payment));
                }
                elseif($bank_transfer_payment->type == 'salesinvoice')
                {
                    $salesinvoice = \Modules\Sales\Entities\SalesInvoice::where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->where('id',$bank_transfer_payment->request)->first();

                    $salesinvoice_payment                 = new \Modules\Sales\Entities\SalesInvoicePayment();
                    $salesinvoice_payment->invoice_id     = $bank_transfer_payment->request;
                    $salesinvoice_payment->transaction_id = app('Modules\Sales\Http\Controllers\SalesInvoiceController')->transactionNumber($salesinvoice->created_by);
                    $salesinvoice_payment->date           = Date('Y-m-d');
                    $salesinvoice_payment->amount         = $bank_transfer_payment->price;
                    $salesinvoice_payment->client_id      = 0;
                    $salesinvoice_payment->payment_type   = 'Bank Account';
                    $salesinvoice_payment->receipt        = $bank_transfer_payment->attachment;
                    $salesinvoice_payment->save();

                    $due     = $salesinvoice->getDue();
                    if ($due <= 0) {
                        $salesinvoice->status = 3;
                        $salesinvoice->save();
                    } else {
                        $salesinvoice->status = 2;
                        $salesinvoice->save();
                    }
                    event(new BankTransferPaymentStatus($salesinvoice,$bank_transfer_payment->type,$bank_transfer_payment));

                }
                elseif($bank_transfer_payment->type == 'retainer')
                {
                    $retainer = \Modules\Retainer\Entities\Retainer::where('created_by',creatorId())->where('id',$bank_transfer_payment->request)->first();

                    $retainer_payment                 = new \Modules\Retainer\Entities\RetainerPayment();
                    $retainer_payment->retainer_id     = $bank_transfer_payment->request;
                    $retainer_payment->date           = Date('Y-m-d');
                    $retainer_payment->account_id     = 0;
                    $retainer_payment->payment_method = 0;
                    $retainer_payment->amount         = $bank_transfer_payment->price;
                    $retainer_payment->order_id       = $bank_transfer_payment->order_id;
                    $retainer_payment->currency       = $bank_transfer_payment->price_currency;
                    $retainer_payment->payment_type   = 'Bank Account';
                    $retainer_payment->receipt        = $bank_transfer_payment->attachment;
                    $retainer_payment->save();

                    $due     = $retainer->getDue();
                    if ($due <= 0) {
                        $retainer->status = 4;
                        $retainer->save();
                    } else {
                        $retainer->status = 2;
                        $retainer->save();
                    }
                    event(new BankTransferPaymentStatus($retainer,$bank_transfer_payment->type,$retainer_payment));

                }
                $bank_transfer_payment->delete();
                return redirect()->back()->with('success', __('Bank Account request Approve successfully'));
            }
            else
            {
                return redirect()->back()->with('success', __('Bank Account request Reject successfully'));
            }
        }
        else
        {
            return response()->json(['error' => __('Request data not found!')], 401);
        }
    }

    public function BankAccountRequestdestroy($id)
    {
        $bank_transfer_payment = BankTransferPayment::where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->where('id',$id)->first();

        if($bank_transfer_payment)
        {
           if($bank_transfer_payment->status != 'Pending')
           {
            $bank_account_id = $bank_transfer_payment->bank_accounts_id;
            $bank_account = BankAccount::find($bank_account_id);

                if ($bank_account) {
                    $bank_account->opening_balance -= $bank_transfer_payment->price;
                    $bank_account->save();
                }
            }

            if($bank_transfer_payment->attachment)
            {
                delete_file($bank_transfer_payment->attachment);
            }
            $bank_transfer_payment->delete();

            return redirect()->back()->with('success', __('Bank Account request successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Request data not found!'));
        }
    }
}
