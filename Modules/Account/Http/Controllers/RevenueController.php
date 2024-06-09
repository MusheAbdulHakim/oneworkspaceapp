<?php

namespace Modules\Account\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Account\Entities\AccountUtility;
use Modules\Account\Entities\BankAccount;
use Modules\Account\Entities\Customer;
use Modules\Account\Entities\Revenue;
use Modules\Account\Entities\Transaction;
use Modules\Account\Entities\Transfer;
use Modules\Account\Events\CreateRevenue;
use Modules\Account\Events\DestroyRevenue;
use Modules\Account\Events\UpdateRevenue;

class RevenueController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
            if(Auth::user()->isAbleTo('revenue manage'))
            {
                $customer = Customer::where('workspace', '=',getActiveWorkSpace())->get()->pluck('name', 'id');

                $account = BankAccount::where('workspace',getActiveWorkSpace())->get()->pluck('holder_name', 'id');
                $category = [];
                if(module_is_active('ProductService'))
                {
                    $category = \Modules\ProductService\Entities\Category::where('created_by', '=', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 1)->get()->pluck('name', 'id');
                }

                $query = Revenue::select('revenues.*', 'bank_accounts.bank_name as bank_name', 'bank_accounts.holder_name as holder_name', 'customers.name as customer_name','categories.name as category_name')->where('revenues.workspace', '=', getActiveWorkSpace());
                

                if(count(explode('to', $request->date)) > 1)
                {
                    $date_range = explode(' to ', $request->date);
                    $query->whereBetween('date', $date_range);
                }
                elseif(!empty($request->date))
                {
                    $date_range = [$request->date , $request->date];
                    $query->whereBetween('date', $date_range);
                }

                if (!empty($request->customer)) {
                    $query->where('revenues.customer_id', '=', $request->customer);
                }

                if (!empty($request->account)) {
                    $query->where('revenues.account_id', '=', $request->account);
                }

                if (!empty($request->category)) {
                    $query->where('revenues.category_id', '=', $request->category);
                }

                if (!empty($request->payment)) {
                    $query->where('revenues.payment_method', '=', $request->payment);
                }
                $query = $query->join('customers', 'revenues.customer_id', '=', 'customers.id')
                        ->join('bank_accounts', 'revenues.account_id', '=', 'bank_accounts.id')
                        ->join('categories', 'revenues.category_id', '=', 'categories.id');
                $revenues = $query->get();
                return view('account::revenue.index', compact('revenues', 'customer', 'account', 'category'));
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
        if(Auth::user()->isAbleTo('revenue create'))
        {
            $customers = Customer::where('workspace', '=',getActiveWorkSpace())->get()->pluck('name', 'id');
            if(module_is_active('ProductService'))
            {
                $categories = \Modules\ProductService\Entities\Category::where('created_by', '=', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 1)->get()->pluck('name', 'id');
            }
            else
            {
                $categories = [];
            }
            $accounts   = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');

            return view('account::revenue.create', compact('customers', 'categories', 'accounts'));
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
        if(Auth::user()->isAbleTo('revenue create'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required|date_format:Y-m-d',
                                   'amount' => 'required|numeric|gt:0',
                                   'account_id' => 'required',
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
            $customer    = Customer::where('id',$request->customer_id)->where('workspace',getActiveWorkSpace())->first();

            $revenue                 = new Revenue();
            $revenue->date           = $request->date;
            $revenue->amount         = $request->amount;
            $revenue->account_id     = $request->account_id;
            $revenue->customer_id    = $request->customer_id;
            $revenue->user_id        = $customer->user_id;
            $revenue->category_id    = $request->category_id;
            $revenue->payment_method = 0;
            $revenue->reference      = $request->reference;
            $revenue->description    = $request->description;
            if(!empty($request->add_receipt))
            {
                $fileName = time() . "_" . $request->add_receipt->getClientOriginalName();

                $uplaod = upload_file($request,'add_receipt',$fileName,'revenue');
                if($uplaod['flag'] == 1)
                {
                    $url = $uplaod['url'];
                }
                else{
                    return redirect()->back()->with('error',$uplaod['msg']);
                }
                $revenue->add_receipt = $url;

            }
            $revenue->created_by     = creatorId();
            $revenue->workspace        = getActiveWorkSpace();
            $revenue->save();
            if(module_is_active('ProductService'))
            {
                $category            = \Modules\ProductService\Entities\Category::where('id', $request->category_id)->first();
            }
            else
            {
                $category = [];
            }
            $revenue->payment_id = $revenue->id;
            $revenue->type       = 'Revenue';
            $revenue->category   = !empty($category) ? $category->name : '';
            $revenue->user_id    = $revenue->customer_id;
            $revenue->user_type  = 'Customer';
            $revenue->account    = $request->account_id;
            Transaction::addTransaction($revenue);

            $customer         = Customer::where('id', $request->customer_id)->first();
            $payment          = new InvoicePayment();
            $payment->name    = !empty($customer) ? $customer['name'] : '';
            $payment->date    = company_date_formate($request->date);
            $payment->amount  = currency_format_with_sym($request->amount);
            $payment->invoice = '';
            if(!empty($customer))
            {
                AccountUtility::userBalance('customer', $customer->id, $revenue->amount, 'credit');
            }

            Transfer::bankAccountBalance($request->account_id, $revenue->amount, 'credit');

            event(new CreateRevenue($request,$revenue));

            if(!empty(company_setting('Revenue Payment Create')) && company_setting('Revenue Payment Create')  == true)
            {
                $uArr = [
                    'payment_name' => $payment->name,
                    'payment_amount' => $payment->amount,
                    'revenue_type' =>$revenue->type,
                    'payment_date' => $payment->date,
                ];
                try
                {
                    $resp = EmailTemplate::sendEmailTemplate('Revenue Payment Create', [$customer->id => $customer->email], $uArr);
                }
                catch(\Exception $e)
                {
                    $resp['error'] = $e->getMessage();
                    }
                    return redirect()->route('revenue.index')->with('success', __('Revenue successfully created.') . ((isset($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }

            return redirect()->route('revenue.index')->with('success', __('Revenue successfully created.'));

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
    public function edit(Revenue $revenue)
    {
        if(Auth::user()->isAbleTo('revenue edit'))
        {
            $customers = Customer::where('workspace', '=',getActiveWorkSpace())->get()->pluck('name', 'id');
            if(module_is_active('ProductService'))
            {
                $categories = \Modules\ProductService\Entities\Category::where('created_by', '=', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 1)->get()->pluck('name', 'id');
            }
            else
            {
                $categories = [];
            }
            $accounts   = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');

            return view('account::revenue.edit', compact('customers', 'categories', 'accounts', 'revenue'));
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
    public function update(Request $request, Revenue $revenue)
    {
        if(Auth::user()->isAbleTo('revenue edit'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                    'date' => 'required|date_format:Y-m-d',
                                    'amount' => 'required|numeric|gt:0',
                                    'account_id' => 'required',
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

            $customer = Customer::where('id', $request->customer_id)->first();
            if(!empty($customer))
            {
                AccountUtility::userBalance('customer', $customer->id, $revenue->amount, 'debit');
            }

            Transfer::bankAccountBalance($revenue->account_id, $revenue->amount, 'debit');

            $revenue->date           = $request->date;
            $revenue->amount         = $request->amount;
            $revenue->account_id     = $request->account_id;
            $revenue->customer_id    = $request->customer_id;
            $revenue->user_id        = $customer->user_id;
            $revenue->category_id    = $request->category_id;
            $revenue->payment_method = 0;
            $revenue->reference      = $request->reference;
            $revenue->description    = $request->description;
            if(!empty($request->add_receipt))
            {
                if(!empty($revenue->add_receipt))
                {
                    try
                    {
                        delete_file($revenue->add_receipt);
                    }
                    catch (Exception $e)
                    {

                    }
                }
                $fileName = time() . "_" . $request->add_receipt->getClientOriginalName();
                $uplaod = upload_file($request,'add_receipt',$fileName,'revenue');
                if($uplaod['flag'] == 1)
                {
                    $url = $uplaod['url'];
                }
                else{
                    return redirect()->back()->with('error',$uplaod['msg']);
                }
                $revenue->add_receipt = $url;
            }

            $revenue->save();
            if(module_is_active('ProductService'))
            {
                $category            = \Modules\ProductService\Entities\Category::where('id', $request->category_id)->first();
            }
            else
            {
                $category = [];
            }
            $revenue->category   = !empty($category) ? $category->name : '';
            $revenue->payment_id = $revenue->id;
            $revenue->type       = 'Revenue';
            $revenue->account    = $request->account_id;
            Transaction::editTransaction($revenue);

            if(!empty($customer))
            {
                AccountUtility::userBalance('customer', $customer->id, $request->amount, 'credit');
            }

            Transfer::bankAccountBalance($request->account_id, $request->amount, 'credit');

            if(module_is_active('DoubleEntry'))
            {
                $request->merge(['id'=>$revenue->id]);
            }
            event(new UpdateRevenue($request,$revenue));
            return redirect()->route('revenue.index')->with('success', __('Revenue successfully updated.'));
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
    public function destroy(Revenue $revenue)
    {
        if(Auth::user()->isAbleTo('revenue delete'))
        {
            if($revenue->workspace == getActiveWorkSpace())
            {
                $type = 'Revenue';
                $user = 'Customer';
                Transaction::destroyTransaction($revenue->id, $type, $user);

                if($revenue->customer_id != 0)
                {
                    AccountUtility::userBalance('customer', $revenue->customer_id, $revenue->amount, 'debit');
                }

                Transfer::bankAccountBalance($revenue->account_id, $revenue->amount, 'debit');
                if(!empty($revenue->add_receipt))
                {
                    try
                    {
                        delete_file($revenue->add_receipt);
                    }
                    catch (Exception $e)
                    {

                    }
                }
                event(new DestroyRevenue($revenue));
                $revenue->delete();
                return redirect()->route('revenue.index')->with('success', __('Revenue successfully deleted.'));
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
