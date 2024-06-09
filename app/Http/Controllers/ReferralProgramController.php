<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Plan;
use App\Models\ReferralSetting;
use App\Models\ReferralTransaction;
use App\Models\TransactionOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReferralProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setting = ReferralSetting::where('created_by',creatOrId())->first();
        $payRequests = TransactionOrder::select('transaction_orders.*', 'users.name as company_name',)
        ->leftJoin('users', 'transaction_orders.req_user_id', '=', 'users.id')->where('status' , 1)->get();

        $transactions = ReferralTransaction::select('referral_transactions.*', 'users.name as user_name', 'plans.name as plan_name', 'companies.name as company_name')
        ->leftJoin('users', 'referral_transactions.company_id', '=', 'users.id')
        ->leftJoin('plans', 'referral_transactions.plan_id', '=', 'plans.id')
        ->leftJoin('users as companies', 'referral_transactions.referral_code', '=', 'companies.referral_code')
        ->get();


        return view('referral-program.index' , compact('setting' , 'payRequests' , 'transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                               'percentage' => 'required',
                               'minimum_threshold_amount' => 'required',
                               'guideline' => 'required',
                           ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        if($request->has('is_enable') && $request->is_enable == 'on')
        {
            $is_enable = 1;
        }
        else
        {
            $is_enable = 0;
        }

        $setting = ReferralSetting::where('created_by' , creatOrId())->first();

        if($setting == null)
        {
            $setting = new ReferralSetting();
        }
        $setting->percentage = $request->percentage;
        $setting->minimum_threshold_amount = $request->minimum_threshold_amount;
        $setting->is_enable  = $is_enable;
        $setting->guideline = $request->guideline;
        $setting->created_by = creatOrId();
        $setting->save();

        return redirect()->route('referral-program.index')->with('success', __('Referral Program Setting successfully Updated.'));
    }

    public function companyIndex()
    {
        $setting = ReferralSetting::where('created_by',1)->first();

        $transactions = ReferralTransaction::select('referral_transactions.*', 'users.name as user_name','plans.name as plan_name')->leftJoin('users', 'referral_transactions.company_id', '=', 'users.id')->leftJoin('plans', 'referral_transactions.plan_id', '=', 'plans.id')->where('referral_transactions.referral_code' , Auth::user()->referral_code)->get();

        $transactionsOrder = TransactionOrder::select('transaction_orders.*', 'coupons.code as coupon_code','coupons.expiry_date','user_coupons.coupon as usercoupon')->leftJoin('coupons', 'transaction_orders.coupon_id', '=', 'coupons.id')->leftJoin('user_coupons', 'transaction_orders.coupon_id', '=', 'user_coupons.coupon')->where('req_user_id',Auth::user()->id)->get();
        $paidAmount = $transactionsOrder->where('usercoupon','!=',null)->where('status' , 2)->sum('req_amount');

        $company_settings = getCompanyAllSetting();

        $paymentRequest = TransactionOrder::where('status' , 1)->where('req_user_id',Auth::user()->id)->first();
        return view('referral-program.company' , compact('setting' , 'transactions' , 'paidAmount' , 'transactionsOrder' , 'paymentRequest','company_settings'));
    }

    public function requestedAmountSent($id)
    {
        $id  = \Illuminate\Support\Facades\Crypt::decrypt($id);
        $paidAmount = TransactionOrder::where('req_user_id',creatorId())->where('status' , 2)->sum('req_amount');
        $user = User::find(creatorId());

        $netAmount = $user->commission_amount - $paidAmount;

        return view('referral-program.request_amount' , compact('id' , 'netAmount'));
    }

    public function requestedAmountStore(Request $request , $id)
    {
        $order = new TransactionOrder();
        $order->req_amount =  $request->request_amount;
        $order->req_user_id = creatorId();
        $order->status = 1;
        $order->date = date('Y-m-d');
        $order->save();

        return redirect()->route('referral-program.company')->with('success', __('Request Send Successfully.'));
    }

    public function requestCancel($id)
    {
        $transaction = TransactionOrder::where('req_user_id',$id)->orderBy('id','desc')->first();
        $transaction->delete();

        return redirect()->route('referral-program.company')->with('success', __('Request Cancel Successfully.'));
    }

    public function requestedAmount($id , $status)
    {
        $setting = ReferralSetting::where('created_by',1)->first();

        $transaction = TransactionOrder::find($id);

        $paidAmount = TransactionOrder::where('req_user_id',$transaction->req_user_id)->where('status' , 2)->sum('req_amount');
        $user = User::find($transaction->req_user_id);

        $netAmount = $user->commission_amount - $paidAmount;

        $minAmount = isset($setting) ? $setting->minimum_threshold_amount : 0;

        if($status == 0)
        {
            $transaction->status = 0;

            $transaction->save();

            return redirect()->route('referral-program.index')->with('error', __('Request Rejected Successfully.'));
        }
        elseif($transaction->req_amount > $netAmount)
        {
            $transaction->status = 0;

            $transaction->save();

            return redirect()->route('referral-program.index')->with('error', __('This request cannot be accepted because it exceeds the commission amount.'));
        }
        elseif($transaction->req_amount <= $minAmount)
        {
            $transaction->status = 0;

            $transaction->save();
            return redirect()->route('referral-program.index')->with('error', __('This request cannot be accepted because it less than the threshold amount.'));
        }
        else
        {
            $plan = Plan::get()->pluck('package_price_yearly','id');
            $length = 10; // Adjust the length of the coupon code as needed
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; // Define the characters to be used

            $couponCode = [];

            for ($i = 0; $i < $length; $i++) {
                $couponCode[] = $characters[rand(0, strlen($characters) - 1)];
            }
            $coupon_name = 'Coupon - '.$transaction->req_amount;
            $expiry_date = Carbon::now()->addDays(13);


            $coupon                     = new Coupon();
            $coupon->name               = $coupon_name;
            $coupon->type               = 'Flat';
            $coupon->minimum_spend      = 0;
            $coupon->maximum_spend      = $plan->max();
            $coupon->discount           = $transaction->req_amount;
            $coupon->limit              = 1;
            $coupon->limit_per_user     = 1;
            $coupon->expiry_date        = $expiry_date->format('Y-m-d');
            $coupon->code               = implode('',$couponCode);
            $coupon->save();

            $transaction->status    = 2;
            $transaction->coupon_id = $coupon->id;
            $transaction->save();

            return redirect()->route('referral-program.index')->with('success', __('Request Aceepted Successfully.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
