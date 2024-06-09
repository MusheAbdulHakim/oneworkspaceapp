<?php

namespace App\Http\Controllers;

use App\Events\BankTransferPaymentStatus;
use App\Events\BankTransferRequestUpdate;
use App\Models\BankTransferPayment;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class BanktransferController extends Controller
{
    public function settingGet($settings)
    {
        return view('bank_transfer.setting',compact('settings'));
    }

    public function setting(Request $request)
    {
        if ($request->has('bank_transfer_payment_is_on'))
        {
            $validator = Validator::make($request->all(),
            [
                'bank_number' => 'required|string',
            ]);
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $bank_transfer = [];
            $bank_transfer['bank_transfer_payment_is_on'] =  $request->bank_transfer_payment_is_on;
            $bank_transfer['bank_number'] =  $request->bank_number;

            foreach ($bank_transfer as $key => $value) {
                // Define the data to be updated or inserted
                $data = [
                    'key' => $key,
                    'workspace' => getActiveWorkSpace(),
                    'created_by' => creatorId(),
                ];

                // Check if the record exists, and update or insert accordingly
                Setting::updateOrInsert($data, ['value' => $value]);
            }

        } else{
               // Define the data to be updated or inserted
               $data = [
                'key' => 'bank_transfer_payment_is_on',
                'workspace' => getActiveWorkSpace(),
                'created_by' => creatorId(),
            ];

            // Check if the record exists, and update or insert accordingly
            Setting::updateOrInsert($data, ['value' => 'off']);
        }
        // Settings Cache forget
        AdminSettingCacheForget();
        comapnySettingCacheForget();
        return redirect()->back()->with('success', __('Bank Transfer Setting save successfully'));
    }

    public function index()
    {
        if (Auth::user()->isAbleTo('plan orders'))
        {
            $bank_transfer_payments = BankTransferPayment::where('type','plan')->orderBy('created_at', 'DESC')->get();
            return view('bank_transfer.index', compact('bank_transfer_payments'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function edit($id)
    {
        $bank_transfer_payment = BankTransferPayment::find($id);
        if($bank_transfer_payment)
        {
            return view('bank_transfer.action', compact('bank_transfer_payment'));
        }
        else
        {
            return response()->json(['error' => __('Request data not found!')], 401);
        }
    }
    public function update(Request $request, $id)
    {
        $bank_transfer_payment = BankTransferPayment::find($id);
        if($bank_transfer_payment && $bank_transfer_payment->status == 'Pending')
        {
            $bank_transfer_payment->status = $request->status;
            $bank_transfer_payment->save();

            if($request->status == 'Approved')
            {
                $requests = json_decode($bank_transfer_payment->request);
                $plan = Plan::find($requests->plan_id);
                $counter = [
                    'user_counter' => (isset($requests->user_counter_input)) ? $requests->user_counter_input : -1,
                    'workspace_counter' => (isset($requests->workspace_counter_input)) ? $requests->workspace_counter_input : -1,
                ];
                $user_module = (isset($requests->user_module_input)) ? $requests->user_module_input : '';
                $duration = (isset($requests->time_period)) ? $requests->time_period : 'Month';
                $user = User::find($bank_transfer_payment->user_id);
                $assignPlan = $user->assignPlan($plan->id,$duration,$user_module,$counter,$bank_transfer_payment->user_id);

                if ($assignPlan['is_success'])
                {
                    $order = Order::create(
                        [
                            'order_id' => $bank_transfer_payment->order_id,
                            'name' => null,
                            'email' => null,
                            'card_number' => null,
                            'card_exp_month' => null,
                            'card_exp_year' => null,
                            'plan_name' => !empty($plan->name) ? $plan->name : 'Basic Package',
                            'plan_id' => $plan->id,
                            'price' => $bank_transfer_payment->price,
                            'price_currency' => $bank_transfer_payment->price_currency,
                            'txn_id' => '',
                            'payment_type' => __('Bank Transfer'),
                            'payment_status' => 'succeeded',
                            'receipt' => $bank_transfer_payment->attachment,
                            'user_id' => $bank_transfer_payment->user_id,
                        ]
                    );

                    $type = 'Subscription';
                    if($requests->coupon_code){

                        UserCoupon($requests->coupon_code,$bank_transfer_payment->order_id);
                    }

                    // first parameter request second parameter Bank Transfer Payment
                    event(new BankTransferRequestUpdate($plan ,$type,$order));
                }
                else
                {
                    return redirect()->route('bank-transfer-request.index')->with('error', __('Something went wrong, Please try again,'));
                }

                return redirect()->back()->with('success', __('Bank transfer request Approve successfully'));
            }
            else
            {
                return redirect()->back()->with('success', __('Bank transfer request Reject successfully'));
            }

        }
        else
        {
            return response()->json(['error' => __('Request data not found!')], 401);
        }
    }

    public function destroy($id)
    {
        $bank_transfer_payment = BankTransferPayment::find($id);
        if($bank_transfer_payment)
        {
            if($bank_transfer_payment->attachment)
            {
                delete_file($bank_transfer_payment->attachment);
            }
            $bank_transfer_payment->delete();

            return redirect()->back()->with('success', __('Bank transfer request successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Request data not found!'));
        }
    }
    public function planPayWithBank(Request $request)
    {
        $validator  = \Validator::make(
            $request->all(),
            [
                'user_counter_input' => 'required',
                'workspace_counter_input' => 'required',
                'userprice_input' => 'required',
                'user_module_price_input' => 'required',
                'time_period' => 'required',
                'payment_receipt' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return response()->json(
                [
                    'status' => 'error',
                    'msg' => $messages->first()
                ]
            );
        }

        $bank_transfer_payment  = new  BankTransferPayment();

        if (!empty($request->payment_receipt))
        {
            $filenameWithExt = $request->file('payment_receipt')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('payment_receipt')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            $uplaod = upload_file($request,'payment_receipt',$fileNameToStore,'bank_transfer');
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
        //calculation
        $plan = Plan::find($request->plan_id);

        $user_counter = !empty($request->user_counter_input) ? $request->user_counter_input : 0;
        $workspace_counter = !empty($request->workspace_counter_input) ? $request->workspace_counter_input : 0;

        $user_module = !empty($request->user_module_input) ? $request->user_module_input : '';
        $duration = !empty($request->time_period) ? $request->time_period : 'Month';

        $user_module_price = 0;
        if(!empty($user_module) && $plan->custom_plan == 1)
        {
            $user_module_array =    explode(',',$user_module);
            foreach ($user_module_array as $key => $value)
            {
                $temp = ($duration == 'Year') ? ModulePriceByName($value)['yearly_price'] : ModulePriceByName($value)['monthly_price'];
                $user_module_price = $user_module_price + $temp;
            }
        }

        $temp = ($duration == 'Year') ? $plan->price_per_user_yearly : $plan->price_per_user_monthly;
        $user_price = 0;
        if ($user_counter > 0) {

            $user_price = $user_counter * $temp;
        }
        $workspace_price = 0;
        if($workspace_counter > 0)
        {
            $workspace_price = $workspace_counter * $temp;
        }
        $plan_price = ($duration == 'Year') ? $plan->package_price_yearly : $plan->package_price_monthly;
        if($request->coupon_code)
        {
            $plan_price = CheckCoupon($request->coupon_code,$plan_price,$plan->id);
        }
        $price                  = $plan_price + $user_module_price + $user_price + $workspace_price;

        $post = $request->all();
        unset($post['_token']);
        unset($post['_method']);
        unset($post['payment_receipt']);


        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

        $bank_transfer_payment->order_id = $orderID;
        $bank_transfer_payment->user_id = Auth::user()->id;
        $bank_transfer_payment->request = json_encode($post);
        $bank_transfer_payment->status = 'Pending';
        $bank_transfer_payment->type = 'plan';
        $bank_transfer_payment->price = $price;
        $bank_transfer_payment->price_currency  = admin_setting('defult_currancy');
        $bank_transfer_payment->created_by = creatorId();
        $bank_transfer_payment->workspace = getActiveWorkSpace();
        $bank_transfer_payment->save();


        return response()->json(
            [
                'status' => 'success',
                'msg' =>  __('Plan payment request send successfully'). '<br> <span class="text-danger">'. __("Your request will be approved by admin and then your plan is activated.") .'</span>'
            ]
        );

    }

    public function invoicePayWithBank(Request $request)
    {
        $validator  = \Validator::make(
            $request->all(),
            [
                'payment_receipt' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return response()->json(
                [
                    'status' => 'error',
                    'msg' => $messages->first()
                ]
            );
        }
        if($request->type == 'invoice'){
            $invoice = Invoice::find($request->invoice_id);
        }
        elseif($request->type == 'retainer')
        {
            $invoice = \Modules\Retainer\Entities\Retainer::find($request->invoice_id);
        }
        if($invoice){
            $bank_transfer_payment  = new  BankTransferPayment();
            if (!empty($request->payment_receipt))
            {
                $filenameWithExt = $request->file('payment_receipt')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('payment_receipt')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                $uplaod = upload_file($request,'payment_receipt',$fileNameToStore,'bank_transfer');
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
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            $bank_transfer_payment->order_id = $orderID;
            $bank_transfer_payment->user_id = $invoice->created_by;
            $bank_transfer_payment->request = $request->invoice_id;
            $bank_transfer_payment->status = 'Pending';
            $bank_transfer_payment->type = $request->type;
            $bank_transfer_payment->price = $request->amount;
            $bank_transfer_payment->price_currency  = company_setting('defult_currancy',$invoice->created_by,$invoice->workspace);
            $bank_transfer_payment->created_by = $invoice->created_by;
            $bank_transfer_payment->workspace = $invoice->workspace;
            $bank_transfer_payment->save();

            if($request->type == 'invoice')
            {
                return redirect()->route('pay.invoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('success', __('Invoice payment request send successfully').('<br> <span class="text-danger"> '.__('Your request will be approved by company and then your payment will be activated.').'</span>'));
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
    public function invoiceBankRequestEdit($id)
    {
        $bank_transfer_payment = BankTransferPayment::find($id);
        if($bank_transfer_payment)
        {
            if($bank_transfer_payment->type == 'invoice')
            {
                $invoice = Invoice::find($bank_transfer_payment->request);
                $invoice_id = Invoice::invoiceNumberFormat($invoice->invoice_id);
            }
            elseif($bank_transfer_payment->type == 'retainer')
            {
                $retainer = \Modules\Retainer\Entities\Retainer::find($bank_transfer_payment->request);
                $invoice_id = \Modules\Retainer\Entities\Retainer::retainerNumberFormat($retainer->retainer_id);
            }
            return view('bank_transfer.invoice_action', compact('bank_transfer_payment','invoice_id'));
        }
        else
        {
            return response()->json(['error' => __('Request data not found!')], 401);
        }
    }

    public function invoiceBankRequestupdate(Request $request, $id)
    {
        $bank_transfer_payment = BankTransferPayment::find($id);
        if($bank_transfer_payment && $bank_transfer_payment->status == 'Pending')
        {
            $bank_transfer_payment->status = $request->status;
            $bank_transfer_payment->save();

            if($request->status == 'Approved')
            {
                if($bank_transfer_payment->type == 'invoice')
                {
                    $invoice = Invoice::find($bank_transfer_payment->request);

                    $invoice_payment                 = new \App\Models\InvoicePayment();
                    $invoice_payment->invoice_id     = $bank_transfer_payment->request;
                    $invoice_payment->date           = Date('Y-m-d');
                    $invoice_payment->account_id     = 0;
                    $invoice_payment->payment_method = 0;
                    $invoice_payment->amount         = $bank_transfer_payment->price;
                    $invoice_payment->order_id       = $bank_transfer_payment->order_id;
                    $invoice_payment->currency       = $bank_transfer_payment->price_currency;
                    $invoice_payment->payment_type   = 'Bank Transfer';
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
                elseif($bank_transfer_payment->type == 'retainer')
                {
                    $retainer = \Modules\Retainer\Entities\Retainer::find($bank_transfer_payment->request);

                    $retainer_payment                 = new \Modules\Retainer\Entities\RetainerPayment();
                    $retainer_payment->retainer_id     = $bank_transfer_payment->request;
                    $retainer_payment->date           = Date('Y-m-d');
                    $retainer_payment->account_id     = 0;
                    $retainer_payment->payment_method = 0;
                    $retainer_payment->amount         = $bank_transfer_payment->price;
                    $retainer_payment->order_id       = $bank_transfer_payment->order_id;
                    $retainer_payment->currency       = $bank_transfer_payment->price_currency;
                    $retainer_payment->payment_type   = 'Bank Transfer';
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
                return redirect()->back()->with('success', __('Bank transfer request Approve successfully'));
            }
            else
            {
                return redirect()->back()->with('success', __('Bank transfer request Reject successfully'));
            }
        }
        else
        {
            return response()->json(['error' => __('Request data not found!')], 401);
        }
    }
}
