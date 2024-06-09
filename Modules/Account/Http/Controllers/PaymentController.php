<?php

namespace Modules\Account\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Account\Entities\AccountUtility;
use Modules\Account\Entities\BankAccount;
use Modules\Account\Entities\BillPayment;
use Modules\Account\Entities\Payment;
use Modules\Account\Entities\Transaction;
use Modules\Account\Entities\Transfer;
use Modules\Account\Entities\Vender;
use App\Models\EmailTemplate;
use Modules\Account\Events\CreatePayment;
use Modules\Account\Events\DestroyPayment;
use Modules\Account\Events\UpdatePayment;

// use Modules\ProductService\Entities\Category;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {

        if(Auth::user()->isAbleTo('expense payment manage'))
        {
            $vendor = Vender::where('workspace', '=',getActiveWorkSpace())->get()->pluck('name', 'id');

            $account = BankAccount::where('workspace',getActiveWorkSpace())->get()->pluck('holder_name', 'id');

            $category=[];
            if(module_is_active('ProductService'))
            {
                $category = \Modules\ProductService\Entities\Category::where('created_by', '=', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 1)->get()->pluck('name', 'id');
            }

            $query = Payment::select('payments.*', 'bank_accounts.bank_name as bank_name', 'bank_accounts.holder_name as holder_name', 'vendors.name as vendors_name','categories.name as category_name')->where('payments.workspace', '=', getActiveWorkSpace());
            if(!empty($request->date))
            {
                $date_range = explode(',', $request->date);
                if(count($date_range) == 2)
                {
                    $query->whereBetween('date',$date_range);
                }
                else
                {
                    $query->where('date',$date_range[0]);
                }
            }
            if(!empty($request->vendor))
            {
                // $query->where('id', '=', $request->vendor);
                $query->where('payments.vendor_id', '=', $request->vendor);

            }
            if(!empty($request->account))
            {
                // $query->where('account_id', '=', $request->account);
                $query->where('payments.account_id', '=', $request->account);

            }

            if(!empty($request->category))
            {
                // $query->where('category_id', '=', $request->category);
                $query->where('payments.category_id', '=', $request->category);

            }
            $query = $query->join('vendors', 'payments.vendor_id', '=', 'vendors.id')
                ->join('bank_accounts', 'payments.account_id', '=', 'bank_accounts.id')
                ->join('categories', 'payments.category_id', '=', 'categories.id');
            $payments = $query->get();

            return view('account::payment.index', compact('payments', 'account', 'category', 'vendor'));
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
        if(\Auth::user()->isAbleTo('expense payment create'))
        {
            $vendors = Vender::where('workspace', '=',getActiveWorkSpace())->get()->pluck('name', 'id');
            $categories=[];
            if(module_is_active('ProductService'))
            {
                $categories = \Modules\ProductService\Entities\Category::where('created_by', '=', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type','2')->get()->pluck('name', 'id');
            }
            $accounts   = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');

            return view('account::payment.create', compact('vendors', 'categories', 'accounts'));
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
    public function store(Request $request)
    {
        if(Auth::user()->isAbleTo('expense payment create'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required|date_format:Y-m-d',
                                   'amount' => 'required|gte:0',
                                   'account_id' => 'required',
                                   'vendor_id' => 'required',
                                   'category_id' => 'required',
                                   'reference' => 'required',
                                   'description' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $payment                 = new Payment();
            $payment->date           = $request->date;
            $payment->amount         = $request->amount;
            $payment->account_id     = $request->account_id;
            $payment->vendor_id      = $request->vendor_id;
            $payment->category_id    = $request->category_id;
            $payment->payment_method = 0;
            $payment->reference      = $request->reference;
            $payment->description    = $request->description;
            if(!empty($request->add_receipt))
            {
                $fileName = time() . "_" . $request->add_receipt->getClientOriginalName();
                $uplaod = upload_file($request,'add_receipt',$fileName,'payment');
                if($uplaod['flag'] == 1)
                {
                    $url = $uplaod['url'];
                }
                else{
                    return redirect()->back()->with('error',$uplaod['msg']);
                }

                $payment->add_receipt = $url;
            }
            $payment->workspace      = getActiveWorkSpace();
            $payment->created_by     = creatorId();
            $payment->save();

            $category            = \Modules\ProductService\Entities\Category::where('id', $request->category_id)->first();
            $payment->payment_id = $payment->id;
            $payment->type       = 'Payment';
            $payment->category   = $category->name;
            $payment->user_id    = $payment->vendor_id;
            $payment->user_type  = 'Vendor';
            $payment->account    = $request->account_id;

            Transaction::addTransaction($payment);

            $vendor          = Vender::where('id', $request->vendor_id)->first();
            $bill_payment    = new BillPayment();
            $bill_payment->name   = !empty($vendor) ? $vendor['name'] : '';
            $bill_payment->method = '-';
            $bill_payment->date   = company_date_formate($request->date);
            $bill_payment->amount = currency_format_with_sym($request->amount);
            $bill_payment->bill   = '';

            Transfer::bankAccountBalance($request->account_id, $request->amount, 'debit');

            event(new CreatePayment($request,$bill_payment,$payment));

            if(!empty($vendor))
            {
                AccountUtility::userBalance('vendor', $vendor->id, $request->amount, 'debit');
                if(!empty(company_setting('Bill Payment Create')) && company_setting('Bill Payment Create')  == true)
                {
                    $uArr = [
                        'payment_name' => $bill_payment->name,
                        'payment_bill' => $bill_payment->bill,
                        'payment_amount' => $bill_payment->amount,
                        'payment_date' => $bill_payment->date,
                        'payment_method'=> $bill_payment->method

                    ];
                    try
                    {
                        $resp = EmailTemplate::sendEmailTemplate('Bill Payment Create', [$vendor->id => $vendor->email], $uArr);
                    }

                    catch (\Exception $e) {
                        $resp['error'] = $e->getMessage();
                    }
                    return redirect()->route('payment.index')->with('success', __('Payment successfully created.') . ((isset($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
                }
            }

            return redirect()->route('payment.index')->with('success', __('Payment successfully created.'));
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
        return redirect()->back();
        return view('account::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Payment $payment)
    {
        if(Auth::user()->isAbleTo('expense payment edit'))
        {
            $vendors = Vender::where('workspace', '=',getActiveWorkSpace())->get()->pluck('name', 'id');

            $categories=[];
            if(module_is_active('ProductService'))
            {
                $categories = \Modules\ProductService\Entities\Category::where('created_by', '=', creatorId())->where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
            }
            $accounts   = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');

            return view('account::payment.edit', compact('vendors', 'categories', 'accounts', 'payment'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Payment $payment)
    {

        if(Auth::user()->isAbleTo('expense payment edit'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                    'date' => 'required|date_format:Y-m-d',
                                    'amount' => 'required|gte:0',
                                    'account_id' => 'required',
                                    'vendor_id' => 'required',
                                    'category_id' => 'required',
                                    'reference' => 'required',
                                    'description' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $vendor = Vender::where('id', $request->vendor_id)->first();
            if(!empty($vendor))
            {
                AccountUtility::userBalance('vendor', $vendor->id, $payment->amount, 'credit');
            }
            Transfer::bankAccountBalance($payment->account_id, $payment->amount, 'credit');

            $payment->date           = $request->date;
            $payment->amount         = $request->amount;
            $payment->account_id     = $request->account_id;
            $payment->vendor_id      = $request->vendor_id;
            $payment->category_id    = $request->category_id;
            $payment->payment_method = 0;
            $payment->reference      = $request->reference;
            $payment->description    = $request->description;
            if(!empty($request->add_receipt))
            {
                if(!empty($payment->add_receipt))
                {
                    try
                    {
                        delete_file($payment->add_receipt);
                    }
                catch (Exception $e)
                    {

                    }
                }

                $fileName = time() . "_" . $request->add_receipt->getClientOriginalName();
                $uplaod = upload_file($request,'add_receipt',$fileName,'payment');
                if($uplaod['flag'] == 1)
                {
                    $url = $uplaod['url'];
                }
                else{
                    return redirect()->back()->with('error',$uplaod['msg']);
                }

                $payment->add_receipt = $url;
            }
            $payment->save();

            $category            = \Modules\ProductService\Entities\Category::where('id', $request->category_id)->first();
            $payment->category   = $category->name;
            $payment->payment_id = $payment->id;
            $payment->type       = 'Payment';
            $payment->account    = $request->account_id;
            Transaction::editTransaction($payment);
            if(!empty($vendor))
            {
                AccountUtility::userBalance('vendor', $vendor->id, $request->amount, 'debit');
            }

            Transfer::bankAccountBalance($request->account_id, $request->amount, 'debit');

            if(module_is_active('DoubleEntry'))
            {
                $request->merge(['id'=>$payment->id]);
            }

            event(new UpdatePayment($request,$payment));
            return redirect()->back()->with('success', __('Payment successfully updated.'));
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
    public function destroy(Payment $payment)
    {
        if(Auth::user()->isAbleTo('expense payment delete'))
        {
            if($payment->workspace == getActiveWorkSpace())
            {
                if(!empty($payment->add_receipt))
                {
                    try
                    {
                        delete_file($payment->add_receipt);
                    }
                    catch (Exception $e)
                    {

                    }
                }
                event(new DestroyPayment($payment));
                $payment->delete();
                $type = 'Payment';
                $user = 'Vendor';
                Transaction::destroyTransaction($payment->id, $type, $user);

                if($payment->vendor_id != 0)
                {
                    AccountUtility::userBalance('vendor', $payment->vendor_id, $payment->amount, 'credit');
                }
                Transfer::bankAccountBalance($payment->account_id, $payment->amount, 'credit');

                return redirect()->route('payment.index')->with('success', __('Payment successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
