<?php

namespace Modules\Stripe\Http\Controllers;

use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Models\EmailTemplate;
use App\Models\Setting;
use App\Models\WorkSpace;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Modules\Coupons\Entities\UserCoupon;
use Modules\Stripe\Events\StripePaymentStatus;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Modules\Holidayz\Entities\Hotels;
use Modules\Holidayz\Entities\RoomBookingCart;
use Modules\Holidayz\Entities\BookingCoupons;
use Modules\Holidayz\Entities\HotelCustomer;
use Modules\Holidayz\Entities\RoomBooking;
use Modules\Holidayz\Entities\RoomBookingOrder;
use Modules\Holidayz\Entities\UsedBookingCoupons;
use Modules\Holidayz\Events\CreateRoomBooking;
use Modules\GymManagement\Entities\AssignMembershipPlan;
use Modules\BeautySpaManagement\Entities\BeautyService;
use Modules\BeautySpaManagement\Entities\BeautyReceipt;
use Modules\BeautySpaManagement\Entities\BeautyBooking;
use Modules\MovieShowBookingSystem\Entities\MovieSeatBooking;
use Modules\MovieShowBookingSystem\Entities\MovieSeatBookingOrder;
use Modules\ParkingManagement\Entities\Parking;
use Modules\ParkingManagement\Entities\Payment;
use Modules\ParkingManagement\Entities\SlotType;

class StripeController extends Controller
{
    public $stripe_key;
    public $stripe_secret;
    public $is_stripe_enabled;
    public $currancy;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function setting(Request $request)
    {
        if (Auth::user()->isAbleTo('stripe manage')) {
            if ($request->has('stripe_is_on')) {
                $validator = Validator::make($request->all(), [
                    'stripe_key' => 'required|string',
                    'stripe_secret' => 'required|string'
                ]);
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
            }
            $getActiveWorkSpace = getActiveWorkSpace();
            $creatorId = creatorId();
            if ($request->has('stripe_is_on')) {
                $post = $request->all();
                unset($post['_token']);
                foreach ($post as $key => $value) {
                    // Define the data to be updated or inserted
                    $data = [
                        'key' => $key,
                        'workspace' => $getActiveWorkSpace,
                        'created_by' => $creatorId,
                    ];

                    // Check if the record exists, and update or insert accordingly
                    Setting::updateOrInsert($data, ['value' => $value]);
                }
            } else {
                $data = [
                    'key' => 'stripe_is_on',
                    'workspace' => $getActiveWorkSpace,
                    'created_by' => $creatorId,
                ];
                // Check if the record exists, and update or insert accordingly
                Setting::updateOrInsert($data, ['value' => 'off']);
            }
            // Settings Cache forget
            AdminSettingCacheForget();
            comapnySettingCacheForget();
            return redirect()->back()->with('success', 'Stripe setting save sucessfully.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function planPayWithStripe(Request $request)
    {
        $user = User::find(\Auth::user()->id);
        $plan = Plan::find($request->plan_id);
        $admin_settings = getAdminAllSetting();
        $admin_currancy = !empty($admin_settings['defult_currancy']) ? $admin_settings['defult_currancy'] : 'INR';
        $authuser = Auth::user();
        $user_counter = !empty($request->user_counter_input) ? $request->user_counter_input : 0;
        $workspace_counter = !empty($request->workspace_counter_input) ? $request->workspace_counter_input : 0;
        $user_module = !empty($request->user_module_input) ? $request->user_module_input : '';
        $duration = !empty($request->time_period) ? $request->time_period : 'Month';
        $user_module_price = 0;
        if (!empty($user_module) && $plan->custom_plan == 1) {
            $user_module_array = explode(',', $user_module);
            foreach ($user_module_array as $key => $value) {
                $temp = ($duration == 'Year') ? ModulePriceByName($value)['yearly_price'] : ModulePriceByName($value)['monthly_price'];
                $user_module_price = $user_module_price + $temp;
            }
        }
        $user_price = 0;
        if ($user_counter > 0) {
            $temp = ($duration == 'Year') ? $plan->price_per_user_yearly : $plan->price_per_user_monthly;
            $user_price = $user_counter * $temp;
        }
        $workspace_price = 0;
        if ($workspace_counter > 0) {
            $temp = ($duration == 'Year') ? $plan->price_per_workspace_yearly : $plan->price_per_workspace_monthly;
            $workspace_price = $workspace_counter * $temp;
        }
        $plan_price = ($duration == 'Year') ? $plan->package_price_yearly : $plan->package_price_monthly;
        $counter = [
            'user_counter' => $user_counter,
            'workspace_counter' => $workspace_counter,
        ];
        $stripe_session = '';
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        if ($plan) {
            /* Check for code usage */
            $plan->discounted_price = false;
            $payment_frequency = $plan->duration;
            if ($request->coupon_code) {
                $plan_price = CheckCoupon($request->coupon_code, $plan_price,$plan->id);
            }
            $price = $plan_price + $user_module_price + $user_price + $workspace_price;
            if ($price <= 0) {
                $assignPlan = DirectAssignPlan($plan->id, $duration, $user_module, $counter, 'STRIPE', $request->coupon_code);
                if ($assignPlan['is_success']) {
                    return redirect()->route('plans.index')->with('success', __('Plan activated Successfully!'));
                } else {
                    return redirect()->route('plans.index')->with('error', __('Something went wrong, Please try again,'));
                }
            }

            try {

                $payment_plan = $duration;
                $payment_type = 'onetime';
                /* Payment details */
                $code = '';

                $product = 'Basic Package';

                /* Final price */
                $stripe_formatted_price = in_array(
                    $admin_currancy,
                    [
                        'MGA',
                        'BIF',
                        'CLP',
                        'PYG',
                        'DJF',
                        'RWF',
                        'GNF',
                        'UGX',
                        'JPY',
                        'VND',
                        'VUV',
                        'XAF',
                        'KMF',
                        'KRW',
                        'XOF',
                        'XPF',
                    ]
                ) ? number_format($price, 2, '.', '') : number_format($price, 2, '.', '') * 100;
                $return_url_parameters = function ($return_type) use ($payment_frequency, $payment_type) {
                    return '&return_type=' . $return_type . '&payment_processor=stripe&payment_frequency=' . $payment_frequency . '&payment_type=' . $payment_type;
                };
                /* Initiate Stripe */
                \Stripe\Stripe::setApiKey(isset($admin_settings['stripe_secret']) ? $admin_settings['stripe_secret'] : '');
                $stripe_session = \Stripe\Checkout\Session::create(
                    [
                        'payment_method_types' => ['card'],
                        'line_items' => [
                            [
                                'price_data' => [
                                    'currency' => $admin_currancy,
                                    'unit_amount' => (int) $stripe_formatted_price,
                                    'product_data' => [
                                        'name' => !empty($plan->name) ? $plan->name : 'Basic Package',
                                        'description' => $payment_plan,
                                    ],
                                ],
                                'quantity' => 1,
                            ],
                        ],
                        'mode' => 'payment',
                        'metadata' => [
                            'user_id' => $authuser->id,
                            'package_id' => $plan->id,
                            'payment_frequency' => $payment_frequency,
                            'code' => $code,
                        ],
                        'success_url' => route(
                            'plan.get.payment.status',
                            [
                                'order_id' => $orderID,
                                'plan_id' => $plan->id,
                                'user_module' => $user_module,
                                'duration' => $duration,
                                'counter' => $counter,
                                'coupon_code' => $request->coupon_code,
                                $return_url_parameters('success'),
                            ]
                        ),
                        'cancel_url' => route(
                            'plan.get.payment.status',
                            [
                                'plan_id' => $orderID,
                                'order_id' => $plan->id,
                                $return_url_parameters('cancel'),
                            ]
                        ),
                    ]
                );
                Order::create(
                    [
                        'order_id' => $orderID,
                        'name' => null,
                        'email' => null,
                        'card_number' => null,
                        'card_exp_month' => null,
                        'card_exp_year' => null,
                        'plan_name' => !empty($plan->name) ? $plan->name : 'Basic Package',
                        'plan_id' => $plan->id,
                        'price' => !empty($price) ? $price : 0,
                        'price_currency' => $admin_currancy,
                        'txn_id' => '',
                        'payment_type' => __('STRIPE'),
                        'payment_status' => 'pending',
                        'receipt' => null,
                        'user_id' => $authuser->id,
                    ]
                );
                $request->session()->put('stripe_session', $stripe_session);
                $stripe_session = $stripe_session ?? false;
            } catch (\Exception $e) {
                \Log::debug($e->getMessage());
                return redirect()->route('plans.index')->with('error', $e->getMessage());
            }
            return view('stripe::plan.request', compact('stripe_session'));
        } else {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }

    public function planGetStripeStatus(Request $request)
    {
        \Log::debug((array) $request->all());
        $admin_settings = getAdminAllSetting();
        try {
            $stripe = new \Stripe\StripeClient(!empty($admin_settings['stripe_secret']) ? $admin_settings['stripe_secret'] : '');
            $paymentIntents = $stripe->paymentIntents->retrieve(
                $request->session()->get('stripe_session')->payment_intent,
                []
            );
            $receipt_url = $paymentIntents->charges->data[0]->receipt_url;
        } catch (\Exception $exception) {
            $receipt_url = "";
        }
        \Session::forget('stripe_session');
        $request->session()->forget('stripe_session');

        try {
            if ($request->return_type == 'success') {
                $Order = Order::where('order_id', $request->order_id)->first();
                $Order->payment_status = 'succeeded';
                $Order->receipt = $receipt_url;
                $Order->save();

                $user = User::find(\Auth::user()->id);
                $plan = Plan::find($request->plan_id);
                $assignPlan = $user->assignPlan($plan->id, $request->duration, $request->user_module, $request->counter);
                if ($request->coupon_code) {
                    UserCoupon($request->coupon_code, $request->order_id);
                }
                $type = 'Subscription';
                event(new StripePaymentStatus($plan, $type, $Order));
                $value = Session::get('user-module-selection');
                if (!empty($value)) {
                    Session::forget('user-module-selection');
                }
                if ($assignPlan['is_success']) {
                    return redirect()->route('plans.index')->with('success', __('Plan activated Successfully!'));
                } else {
                    return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                }
            } else {
                return redirect()->route('plans.index')->with('error', __('Your Payment has failed!'));
            }
        } catch (\Exception $exception) {
            return redirect()->route('plans.index')->with('error', $exception->getMessage());
        }
    }

    public function payment_setting($id = null, $wokspace = Null)
    {
        if (!empty($id) && empty($wokspace)) {
            $company_settings = getCompanyAllSetting($id);
        } elseif (!empty($id) && !empty($wokspace)) {
            $company_settings = getCompanyAllSetting($id, $wokspace);
        } else {
            $company_settings = getCompanyAllSetting();
        }
        $this->currancy = !empty($company_settings['defult_currancy']) ? $company_settings['defult_currancy'] : 'INR';
        $this->is_stripe_enabled = ($company_settings['stripe_is_on']) ? $company_settings['stripe_is_on'] : 'off';
        $this->stripe_key = ($company_settings['stripe_key']) ? $company_settings['stripe_key'] : '';
        $this->stripe_secret = ($company_settings['stripe_secret']) ? $company_settings['stripe_secret'] : '';
    }

    public function invoicePayWithStripe(Request $request)
    {
        if ($request->type == "invoice") {
            $invoice = \App\Models\Invoice::find($request->invoice_id);
            $user_id = $invoice->created_by;
            $wokspace = $invoice->workspace;
        } elseif ($request->type == "retainer") {
            $invoice = \Modules\Retainer\Entities\Retainer::find($request->invoice_id);
            $user_id = $invoice->created_by;
            $wokspace = $invoice->workspace;
        }

        self::payment_setting($user_id, $wokspace);
        if (isset($this->is_stripe_enabled) && $this->is_stripe_enabled == 'on' && !empty($this->stripe_key) && !empty($this->stripe_secret)) {

            $user = Auth::user();
            $validator = Validator::make(
                $request->all(),
                [
                    'amount' => 'required|numeric',
                    'invoice_id' => 'required',
                ]
            );
            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }
            $comapany_stripe_data = '';
            $invoice_id = $request->input('invoice_id');
            if ($request->type == "invoice") {

                $invoice = \App\Models\Invoice::find($invoice_id);
                $invoice_payID = $invoice->invoice_id;
                $invoiceID = $invoice->id;
                $printID = \App\Models\Invoice::invoiceNumberFormat($invoice_payID, $user_id, $wokspace);
            } elseif ($request->type == "retainer") {
                $invoice = \Modules\Retainer\Entities\Retainer::find($invoice_id);
                $invoice_payID = $invoice->invoice_id;
                $invoiceID = $invoice->id;
                $printID = \Modules\Retainer\Entities\Retainer::retainerNumberFormat($invoice_payID, $user_id, $wokspace);
            }

            if ($invoice) {

                /* Check for code usage */
                $price = $request->amount;

                try {

                    $stripe_formatted_price = in_array(
                        company_setting('defult_currancy', $user_id, $wokspace),
                        [
                            'MGA',
                            'BIF',
                            'CLP',
                            'PYG',
                            'DJF',
                            'RWF',
                            'GNF',
                            'UGX',
                            'JPY',
                            'VND',
                            'VUV',
                            'XAF',
                            'KMF',
                            'KRW',
                            'XOF',
                            'XPF',
                        ]
                    ) ? number_format($price, 2, '.', '') : number_format($price, 2, '.', '') * 100;

                    $return_url_parameters = function ($return_type) {
                        return '&return_type=' . $return_type;
                    };
                    /* Initiate Stripe */
                    \Stripe\Stripe::setApiKey(company_setting('stripe_secret', $user_id, $wokspace));
                    $code = '';

                    $comapany_stripe_data = \Stripe\Checkout\Session::create(
                        [
                            'payment_method_types' => ['card'],
                            'line_items' => [
                                [
                                    'price_data' => [
                                        'currency' => $this->currancy,
                                        'unit_amount' => (int) $stripe_formatted_price,
                                        'product_data' => [
                                            'name' => $printID,
                                        ],
                                    ],
                                    'quantity' => 1,
                                ],
                            ],
                            'mode' => 'payment',
                            'metadata' => [
                                'user_id' => isset($user->name) ? $user->name : 0,
                                'package_id' => $invoiceID,
                                'code' => $code,
                            ],
                            'success_url' => route(
                                'invoice.stripe',
                                [
                                    'invoice_id' => encrypt($invoiceID),
                                    $request->type,
                                    $return_url_parameters('success'),
                                ]
                            ),
                            'cancel_url' => route(
                                'invoice.stripe',
                                [
                                    'invoice_id' => encrypt($invoiceID),
                                    $return_url_parameters('cancel'),
                                ]
                            ),
                        ]

                    );

                    $data = [
                        'amount' => $price,
                        'currency' => $this->currancy,
                        'stripe' => $comapany_stripe_data

                    ];
                    $request->session()->put('comapany_stripe_data', $data);

                    $comapany_stripe_data = $comapany_stripe_data ?? false;
                    return new RedirectResponse($comapany_stripe_data->url);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', $e);
                    \Log::debug($e->getMessage());
                }
            } else {
                if ($request->type == 'invoice') {

                    return redirect()->route('pay.invoice', encrypt($invoiceID))->with('error', __('Invoice is deleted.'));
                } elseif ($request->type == 'retainer') {

                    return redirect()->route('pay.retainer', encrypt($invoiceID))->with('error', __('Retainer is deleted.'));
                }
            }
        } else {
            return redirect()->back()->with('error', __('Please Enter Stripe Details.'));
        }
    }

    public function getInvoicePaymentStatus($invoice_id, Request $request, $type)
    {
        try {
            if ($request->return_type == 'success') {
                if ($type == 'invoice') {
                    if (!empty($invoice_id)) {
                        $invoice_id = decrypt($invoice_id);
                        $invoice = \App\Models\Invoice::find($invoice_id);
                        \Log::debug((array) $request->all());
                        $session_data = $request->session()->get('comapany_stripe_data');
                        try {
                            $stripe = new \Stripe\StripeClient(!empty(company_setting('stripe_secret', $invoice->created_by, $invoice->workspace)) ? company_setting('stripe_secret', $invoice->created_by, $invoice->workspace) : '');
                            $paymentIntents = $stripe->paymentIntents->retrieve(
                                $session_data['stripe']->payment_intent,
                                []
                            );
                            $receipt_url = $paymentIntents->charges->data[0]->receipt_url;
                        } catch (\Exception $exception) {
                            $receipt_url = "";
                        }
                        Session::forget('comapany_stripe_data');
                        $request->session()->forget('comapany_stripe_data');
                        $get_data = $session_data;
                        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                        if ($invoice) {
                            try {
                                if ($request->return_type == 'success') {
                                    $invoice_payment = new \App\Models\InvoicePayment();
                                    $invoice_payment->invoice_id = $invoice_id;
                                    $invoice_payment->date = date('Y-m-d');
                                    $invoice_payment->amount = isset($get_data['amount']) ? $get_data['amount'] : 0;
                                    $invoice_payment->account_id = 0;
                                    $invoice_payment->payment_method = 0;
                                    $invoice_payment->order_id = $orderID;
                                    $invoice_payment->currency = isset($get_data['currency']) ? $get_data['currency'] : 'INR';
                                    $invoice_payment->payment_type = __('STRIPE');
                                    $invoice_payment->receipt = $receipt_url;
                                    $invoice_payment->save();

                                    $due = $invoice->getDue();
                                    if ($due <= 0) {
                                        $invoice->status = 4;
                                        $invoice->save();
                                    } else {
                                        $invoice->status = 3;
                                        $invoice->save();
                                    }
                                    if (module_is_active('Account')) {
                                        //for customer balance update
                                        \Modules\Account\Entities\AccountUtility::updateUserBalance('customer', $invoice->customer_id, $invoice_payment->amount, 'debit');
                                    }
                                    event(new StripePaymentStatus($invoice, $type, $invoice_payment));


                                    return redirect()->route('pay.invoice', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('success', __('Payment added Successfully'));
                                } else {
                                    return redirect()->route('pay.invoice', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('error', __('Transaction has been failed!'));
                                }
                            } catch (\Exception $e) {

                                return redirect()->route('pay.invoice', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('error', __('Transaction has been failed!'));
                            }
                        } else {


                            return redirect()->route('pay.invoice', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('error', __('Invoice not found.'));
                        }
                    } else {

                        return redirect()->route('pay.invoice', \Illuminate\Support\Facades\Crypt::encrypt($invoice_id))->with('error', __('Invoice not found.'));
                    }
                } elseif ($type == 'retainer') {
                    if (!empty($invoice_id)) {
                        $invoice_id = decrypt($invoice_id);
                        $invoice = \Modules\Retainer\Entities\Retainer::find($invoice_id);

                        \Log::debug((array) $request->all());
                        $session_data = $request->session()->get('comapany_stripe_data');
                        try {
                            $stripe = new \Stripe\StripeClient(!empty(company_setting('stripe_secret', $invoice->created_by, $invoice->workspace)) ? company_setting('stripe_secret', $invoice->created_by, $invoice->workspace) : '');

                            $paymentIntents = $stripe->paymentIntents->retrieve(
                                $session_data['stripe']->payment_intent,
                                []
                            );

                            $receipt_url = $paymentIntents->charges->data[0]->receipt_url;
                        } catch (\Exception $exception) {


                            $receipt_url = "";
                        }
                        Session::forget('comapany_stripe_data');
                        $request->session()->forget('comapany_stripe_data');
                        $get_data = $session_data;
                        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                        if ($invoice) {
                            try {
                                if ($request->return_type == 'success') {
                                    $retainer_payment = new \Modules\Retainer\Entities\RetainerPayment();
                                    $retainer_payment->retainer_id = $invoice_id;
                                    $retainer_payment->date = date('Y-m-d');
                                    $retainer_payment->amount = isset($get_data['amount']) ? $get_data['amount'] : 0;
                                    $retainer_payment->account_id = 0;
                                    $retainer_payment->payment_method = 0;
                                    $retainer_payment->order_id = $orderID;
                                    $retainer_payment->currency = isset($get_data['currency']) ? $get_data['currency'] : 'INR';
                                    $retainer_payment->payment_type = __('STRIPE');
                                    $retainer_payment->receipt = $receipt_url;
                                    $retainer_payment->save();

                                    $due = $invoice->getDue();
                                    if ($due <= 0) {
                                        $invoice->status = 3;

                                        $invoice->save();
                                    } else {
                                        $invoice->status = 2;
                                        $invoice->save();
                                    }
                                    //for customer balance update
                                    \Modules\Retainer\Entities\RetainerUtility::updateUserBalance('customer', $invoice->customer_id, $retainer_payment->amount, 'debit');
                                    event(new StripePaymentStatus($invoice, $type, $retainer_payment));
                                    return redirect()->route('pay.retainer', \Illuminate\Support\Facades\Crypt::encrypt($invoice_id))->with('success', __('Payment added Successfully'));
                                } else {

                                    return redirect()->route('pay.retainer', \Illuminate\Support\Facades\Crypt::encrypt($invoice_id))->with('error', __('Transaction has been failed!'));
                                }
                            } catch (\Exception $e) {

                                return redirect()->route('pay.retainer', \Illuminate\Support\Facades\Crypt::encrypt($invoice_id))->with('error', __('Transaction has been failed!'));
                            }
                        } else {
                            return redirect()->route('pay.retainer', \Illuminate\Support\Facades\Crypt::encrypt($invoice_id))->with('error', __('Retainer not found.'));
                        }
                    } else {
                        return redirect()->route('pay.retainer', \Illuminate\Support\Facades\Crypt::encrypt($invoice_id))->with('error', __('Retainer not found.'));
                    }
                } else {
                    return redirect()->back()->with('error', __('Oops something went wrong.'));
                }
            } else {

                return redirect()->back()->with('error', __('Transaction has been failed.'));
            }
        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function coursePayWithStripe(Request $request, $slug)
    {
        $cart = session()->get($slug);
        $products = $cart['products'];

        $store = \Modules\LMS\Entities\Store::where('slug', $slug)->first();
        $student = Auth::guard('students')->user();

        self::payment_setting($store->created_by, $store->wokspace_id);
        $products = $cart['products'];
        $sub_totalprice = 0;
        $totalprice = 0;
        $product_name = [];
        $product_id = [];

        foreach ($products as $key => $product) {
            $product_name[] = $product['product_name'];
            $product_id[] = $product['id'];
            $sub_totalprice += $product['price'];
            $totalprice += $product['price'];
        }
        if (isset($cart['coupon'])) {
            $coupon = $cart['coupon']['coupon'];
        }
        if (!empty($coupon)) {
            if ($coupon['enable_flat'] == 'off') {
                $discount_value = ($sub_totalprice / 100) * $coupon['discount'];
                $totalprice = $sub_totalprice - $discount_value;
            } else {
                $discount_value = $coupon['flat_discount'];
                $totalprice = $sub_totalprice - $discount_value;
            }
        }
        if ($totalprice <= 0) {
            $assignCourse = \Modules\LMS\Entities\LmsUtility::DirectAssignCourse($store, 'Stripe');
            if ($assignCourse['is_success']) {
                return redirect()->route(
                    'store-complete.complete',
                    [
                        $store->slug,
                        \Illuminate\Support\Facades\Crypt::encrypt($assignCourse['courseorder_id']),
                    ]
                )->with('success', __('Transaction has been success'));
            } else {
                return redirect()->route('store.cart', $store->slug)->with('error', __('Something went wrong, Please try again,'));
            }
        }
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        if ($products) {
            try {

                $stripe_formatted_price = in_array(
                    company_setting('defult_currancy', $store->created_by, $store->wokspace_id),
                    [
                        'MGA',
                        'BIF',
                        'CLP',
                        'PYG',
                        'DJF',
                        'RWF',
                        'GNF',
                        'UGX',
                        'JPY',
                        'VND',
                        'VUV',
                        'XAF',
                        'KMF',
                        'KRW',
                        'XOF',
                        'XPF',
                    ]
                ) ? number_format($totalprice, 2, '.', '') : number_format($totalprice, 2, '.', '') * 100;

                $return_url_parameters = function ($return_type) {
                    return '&return_type=' . $return_type;
                };
                /* Initiate Stripe */
                \Stripe\Stripe::setApiKey(company_setting('stripe_secret', $store->created_by, $store->wokspace_id));
                $code = '';

                $comapany_stripe_data = \Stripe\Checkout\Session::create(
                    [
                        'payment_method_types' => ['card'],
                        'line_items' => [
                            [
                                'price_data' => [
                                    'currency' => $this->currancy,
                                    'unit_amount' => (int) $stripe_formatted_price,
                                    'product_data' => [
                                        'name' => $orderID,
                                    ],
                                ],
                                'quantity' => 1,
                            ],
                        ],
                        'mode' => 'payment',
                        'metadata' => [
                            'user_id' => isset($student->name) ? $student->name : 0,
                            'package_id' => '',
                            'code' => $code,
                        ],
                        'success_url' => route(
                            'course.stripe',
                            [
                                $store->slug,
                                $return_url_parameters('success'),
                            ]
                        ),
                        'cancel_url' => route(
                            'course.stripe',
                            [
                                $return_url_parameters('cancel'),
                            ]
                        ),
                    ]

                );

                $data = [
                    'amount' => $totalprice,
                    'currency' => $this->currancy,
                    'stripe' => $comapany_stripe_data

                ];

                $comapany_stripe_data = $comapany_stripe_data ?? false;
                return new RedirectResponse($comapany_stripe_data->url);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e);
                \Log::debug($e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', __('Plan is deleted.'));
        }
    }

    public function getCoursePaymentStatus($slug, Request $request)
    {
        try {
            if ($request->return_type == 'success') {
                $store = \Modules\LMS\Entities\Store::where('slug', $slug)->first();

                $cart = session()->get($slug);
                if (isset($cart['coupon'])) {
                    $coupon = $cart['coupon']['coupon'];
                }
                $products = $cart['products'];
                $sub_totalprice = 0;
                $totalprice = 0;
                $product_name = [];
                $product_id = [];

                foreach ($products as $key => $product) {
                    $product_name[] = $product['product_name'];
                    $product_id[] = $product['id'];
                    $sub_totalprice += $product['price'];
                    $totalprice += $product['price'];
                }
                if (!empty($coupon)) {
                    if ($coupon['enable_flat'] == 'off') {
                        $discount_value = ($sub_totalprice / 100) * $coupon['discount'];
                        $totalprice = $sub_totalprice - $discount_value;
                    } else {
                        $discount_value = $coupon['flat_discount'];
                        $totalprice = $sub_totalprice - $discount_value;
                    }
                }
                if ($products) {
                    \Log::debug((array) $request->all());
                    $session_data = $request->session()->get('comapany_stripe_data');
                    try {
                        $stripe = new \Stripe\StripeClient(!empty(company_setting('stripe_secret', $store->created_by, $store->workspace)) ? company_setting('stripe_secret', $store->created_by, $store->workspace) : '');
                        $paymentIntents = $stripe->paymentIntents->retrieve(
                            $session_data['stripe']->payment_intent,
                            []
                        );
                        $receipt_url = $paymentIntents->charges->data[0]->receipt_url;
                    } catch (\Exception $exception) {
                        $receipt_url = "";
                    }
                    try {

                        $student = Auth::guard('students')->user();
                        $course_order = new \Modules\LMS\Entities\CourseOrder();
                        $course_order->order_id = '#' . time();
                        $course_order->name = $student->name;
                        $course_order->card_number = '';
                        $course_order->card_exp_month = '';
                        $course_order->card_exp_year = '';
                        $course_order->student_id = $student->id;
                        $course_order->course = json_encode($products);
                        $course_order->price = $totalprice;
                        $course_order->coupon = !empty($cart['coupon']['coupon']['id']) ? $cart['coupon']['coupon']['id'] : '';
                        $course_order->coupon_json = json_encode(!empty($coupon) ? $coupon : '');
                        $course_order->discount_price = !empty($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
                        $course_order->price_currency = !empty(company_setting('defult_currancy', $store->created_by, $store->workspace_id)) ? company_setting('defult_currancy', $store->created_by, $store->workspace_id) : 'USD';
                        $course_order->txn_id = '';
                        $course_order->payment_type = __('STRIPE');
                        $course_order->payment_status = 'success';
                        $course_order->receipt = $receipt_url;
                        $course_order->store_id = $store['id'];
                        $course_order->save();

                        foreach ($products as $course_id) {
                            $purchased_course = new \Modules\LMS\Entities\PurchasedCourse();
                            $purchased_course->course_id = $course_id['product_id'];
                            $purchased_course->student_id = $student->id;
                            $purchased_course->order_id = $course_order->id;
                            $purchased_course->save();

                            $student = \Modules\LMS\Entities\Student::where('id', $purchased_course->student_id)->first();
                            $student->courses_id = $purchased_course->course_id;
                            $student->save();
                        }
                        if (!empty(company_setting('New Course Order', $store->created_by, $store->workspace_id)) && company_setting('New Course Order', $store->created_by, $store->workspace_id) == true) {
                            $course = \Modules\LMS\Entities\Course::whereIn('id', $product_id)->get()->pluck('title');
                            $course_name = implode(', ', $course->toArray());
                            $user = User::where('id', $store->created_by)->where('workspace_id', $store->workspace_id)->first();
                            $uArr = [
                                'student_name' => $student->name,
                                'course_name' => $course_name,
                                'store_name' => $store->name,
                                'order_url' => route('user.order', [$store->slug, \Illuminate\Support\Facades\Crypt::encrypt($course_order->id),]),
                            ];
                            try {
                                // Send Email
                                $resp = EmailTemplate::sendEmailTemplate('New Course Order', [$user->id => $user->email], $uArr, $store->created_by);
                            } catch (\Exception $e) {
                                $resp['error'] = $e->getMessage();
                            }
                        }
                        $type = 'coursepayment';
                        event(new StripePaymentStatus($store, $type, $course_order));

                        session()->forget($slug);

                        return redirect()->route(
                            'store-complete.complete',
                            [
                                $store->slug,
                                \Illuminate\Support\Facades\Crypt::encrypt($course_order->id),
                            ]
                        )->with('success', __('Transaction has been success'));
                    } catch (\Exception $e) {
                        return redirect()->back()->with('error', __('Transaction has been failed.'));
                    }
                } else {
                    return redirect()->back()->with('error', __('is deleted.'));
                }
            } else {

                return redirect()->back()->with('error', __('Transaction has been failed.'));
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }


    public function contentPayWithStripe(Request $request, $slug)
    {
        $cart = session()->get($slug);
        $products = $cart['products'];

        $store = \Modules\TVStudio\Entities\TVStudioStore::where('slug', $slug)->first();
        $customer = Auth::guard('customers')->user();

        self::payment_setting($store->created_by, $store->wokspace_id);
        $products = $cart['products'];
        $sub_totalprice = 0;
        $totalprice = 0;
        $product_name = [];
        $product_id = [];

        foreach ($products as $key => $product) {
            $product_name[] = $product['product_name'];
            $product_id[] = $product['id'];
            $sub_totalprice += $product['price'];
            $totalprice += $product['price'];
        }
        if (isset($cart['coupon'])) {
            $coupon = $cart['coupon']['coupon'];
        }
        if (!empty($coupon)) {
            if ($coupon['enable_flat'] == 'off') {
                $discount_value = ($sub_totalprice / 100) * $coupon['discount'];
                $totalprice = $sub_totalprice - $discount_value;
            } else {
                $discount_value = $coupon['flat_discount'];
                $totalprice = $sub_totalprice - $discount_value;
            }
        }
        if ($totalprice <= 0) {
            $assignCourse = \Modules\TVStudio\Entities\TVStudioUtility::DirectAssignCourse($store, 'Stripe');
            if ($assignCourse['is_success']) {
                return redirect()->route(
                    'tv.store-complete.complete',
                    [
                        $store->slug,
                        \Illuminate\Support\Facades\Crypt::encrypt($assignCourse['courseorder_id']),
                    ]
                )->with('success', __('Transaction has been success'));
            } else {
                return redirect()->route('store.cart', $store->slug)->with('error', __('Something went wrong, Please try again,'));
            }
        }
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        if ($products) {
            try {

                $stripe_formatted_price = in_array(
                    company_setting('defult_currancy', $store->created_by, $store->wokspace_id),
                    [
                        'MGA',
                        'BIF',
                        'CLP',
                        'PYG',
                        'DJF',
                        'RWF',
                        'GNF',
                        'UGX',
                        'JPY',
                        'VND',
                        'VUV',
                        'XAF',
                        'KMF',
                        'KRW',
                        'XOF',
                        'XPF',
                    ]
                ) ? number_format($totalprice, 2, '.', '') : number_format($totalprice, 2, '.', '') * 100;

                $return_url_parameters = function ($return_type) {
                    return '&return_type=' . $return_type;
                };
                /* Initiate Stripe */
                \Stripe\Stripe::setApiKey(company_setting('stripe_secret', $store->created_by, $store->wokspace_id));
                $code = '';

                $comapany_stripe_data = \Stripe\Checkout\Session::create(
                    [
                        'payment_method_types' => ['card'],
                        'line_items' => [
                            [
                                'price_data' => [
                                    'currency' => $this->currancy,
                                    'unit_amount' => (int) $stripe_formatted_price,
                                    'product_data' => [
                                        'name' => $orderID,
                                    ],
                                ],
                                'quantity' => 1,
                            ],
                        ],
                        'mode' => 'payment',
                        'metadata' => [
                            'user_id' => isset($customer->name) ? $customer->name : 0,
                            'package_id' => '',
                            'code' => $code,
                        ],
                        'success_url' => route(
                            'content.stripe',
                            [
                                $store->slug,
                                $return_url_parameters('success'),
                            ]
                        ),
                        'cancel_url' => route(
                            'content.stripe',
                            [
                                $return_url_parameters('cancel'),
                            ]
                        ),
                    ]

                );

                $data = [
                    'amount' => $totalprice,
                    'currency' => $this->currancy,
                    'stripe' => $comapany_stripe_data

                ];

                $comapany_stripe_data = $comapany_stripe_data ?? false;
                return new RedirectResponse($comapany_stripe_data->url);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e);
                \Log::debug($e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', __('Plan is deleted.'));
        }
    }

    public function getContentPaymentStatus($slug, Request $request)
    {
        try {
            if ($request->return_type == 'success') {
                $store = \Modules\TVStudio\Entities\TVStudioStore::where('slug', $slug)->first();

                $cart = session()->get($slug);
                if (isset($cart['coupon'])) {
                    $coupon = $cart['coupon']['coupon'];
                }
                $products = $cart['products'];
                $sub_totalprice = 0;
                $totalprice = 0;
                $product_name = [];
                $product_id = [];

                foreach ($products as $key => $product) {
                    $product_name[] = $product['product_name'];
                    $product_id[] = $product['id'];
                    $sub_totalprice += $product['price'];
                    $totalprice += $product['price'];
                }
                if (!empty($coupon)) {
                    if ($coupon['enable_flat'] == 'off') {
                        $discount_value = ($sub_totalprice / 100) * $coupon['discount'];
                        $totalprice = $sub_totalprice - $discount_value;
                    } else {
                        $discount_value = $coupon['flat_discount'];
                        $totalprice = $sub_totalprice - $discount_value;
                    }
                }
                if ($products) {
                    \Log::debug((array) $request->all());
                    $session_data = $request->session()->get('comapany_stripe_data');
                    try {
                        $stripe = new \Stripe\StripeClient(!empty(company_setting('stripe_secret', $store->created_by, $store->workspace)) ? company_setting('stripe_secret', $store->created_by, $store->workspace) : '');
                        $paymentIntents = $stripe->paymentIntents->retrieve(
                            $session_data['stripe']->payment_intent,
                            []
                        );
                        $receipt_url = $paymentIntents->charges->data[0]->receipt_url;
                    } catch (\Exception $exception) {
                        $receipt_url = "";
                    }
                    try {

                        $customer = Auth::guard('customers')->user();
                        $content_order = new \Modules\TVStudio\Entities\TVStudioOrder();
                        $content_order->order_id = '#' . time();
                        $content_order->name = $customer->name;
                        $content_order->card_number = '';
                        $content_order->card_exp_month = '';
                        $content_order->card_exp_year = '';
                        $content_order->customer_id = $customer->id;
                        $content_order->content = json_encode($products);
                        $content_order->price = $totalprice;
                        $content_order->coupon = !empty($cart['coupon']['coupon']['id']) ? $cart['coupon']['coupon']['id'] : '';
                        $content_order->coupon_json = json_encode(!empty($coupon) ? $coupon : '');
                        $content_order->discount_price = !empty($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
                        $content_order->price_currency = !empty(company_setting('defult_currancy', $store->created_by, $store->workspace_id)) ? company_setting('defult_currancy', $store->created_by, $store->workspace_id) : 'USD';
                        $content_order->txn_id = '';
                        $content_order->payment_type = __('STRIPE');
                        $content_order->payment_status = 'success';
                        $content_order->receipt = $receipt_url;
                        $content_order->store_id = $store['id'];
                        $content_order->save();

                        foreach ($products as $course_id) {
                            $purchased_content = new \Modules\TVStudio\Entities\TVStudioPurchasedContent();
                            $purchased_content->content_id = $course_id['product_id'];
                            $purchased_content->customer_id = $customer->id;
                            $purchased_content->order_id = $content_order->id;
                            $purchased_content->save();

                            $student = \Modules\TVStudio\Entities\TVStudioCustomer::where('id', $purchased_content->customer_id)->first();
                            $student->contents_id = $purchased_content->content_id;
                            $student->save();
                        }
                        // if (!empty (company_setting('New Course Order', $store->created_by, $store->workspace_id)) && company_setting('New Course Order', $store->created_by, $store->workspace_id) == true) {
                        //     $course = \Modules\LMS\Entities\Course::whereIn('id', $product_id)->get()->pluck('title');
                        //     $course_name = implode(', ', $course->toArray());
                        //     $user = User::where('id', $store->created_by)->where('workspace_id', $store->workspace_id)->first();
                        //     $uArr = [
                        //         'student_name' => $student->name,
                        //         'course_name' => $course_name,
                        //         'store_name' => $store->name,
                        //         'order_url' => route('user.order', [$store->slug, \Illuminate\Support\Facades\Crypt::encrypt($course_order->id),]),
                        //     ];
                        //     try {
                        //         // Send Email
                        //         $resp = EmailTemplate::sendEmailTemplate('New Course Order', [$user->id => $user->email], $uArr, $store->created_by);
                        //     } catch (\Exception $e) {
                        //         $resp['error'] = $e->getMessage();
                        //     }
                        // }
                        // $type = 'coursepayment';
                        // event(new StripePaymentStatus($store, $type, $course_order));

                        session()->forget($slug);

                        return redirect()->route(
                            'tv.store-complete.complete',
                            [
                                $store->slug,
                                \Illuminate\Support\Facades\Crypt::encrypt($content_order->id),
                            ]
                        )->with('success', __('Transaction has been success'));
                    } catch (\Exception $e) {
                        return redirect()->back()->with('error', __('Transaction has been failed.'));
                    }
                } else {
                    return redirect()->back()->with('error', __('is deleted.'));
                }
            } else {

                return redirect()->back()->with('error', __('Transaction has been failed.'));
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }



    // holidayz
    public function BookingPayWithStripe(Request $request, $slug)
    {
        $hotel = Hotels::where('slug', $slug)->first();
        if ($hotel) {
            $grandTotal = $couponsId = 0;
            if (!auth()->guard('holiday')->user()) {
                $Carts = Cookie::get('cart');
                $Carts = json_decode($Carts, true);
                foreach ($Carts as $key => $value) {
                    //
                    $toDate = \Carbon\Carbon::parse($value['check_in']);
                    $fromDate = \Carbon\Carbon::parse($value['check_out']);

                    $days = $toDate->diffInDays($fromDate);
                    //
                    $grandTotal += $value['price'] * $value['room'] * $days;
                    $grandTotal += ($value['serviceCharge']) ? $value['serviceCharge'] : 0;
                }
            } else {
                $Carts = RoomBookingCart::where(['customer_id' => auth()->guard('holiday')->user()->id])->get();
                foreach ($Carts as $key => $value) {
                    $grandTotal += $value->price;   //  * $value->room
                    $grandTotal += ($value->service_charge) ? $value->service_charge : 0;
                }
            }

            $price = $grandTotal;
            if (!empty($request->coupon)) {
                $coupons = BookingCoupons::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $usedCoupun = $coupons->used_coupon();
                    $discount_value = ($price / 100) * $coupons->discount;
                    $price = $price - $discount_value;
                    $couponsId = $coupons->id;
                    if ($coupons->limit == $usedCoupun) {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                } else {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }

            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            if ($price > 0.0) {
                // if(company_setting('defult_currancy') != 'INR'){
                //     return redirect()->back()->with('error', __('This currancy is not work in Stripe. Please Change currancy to INR(Rp). '));
                // }
                $stripe_formatted_price = in_array(
                    company_setting('defult_currancy', $hotel->created_by, $hotel->wokspace),
                    [
                        'MGA',
                        'BIF',
                        'CLP',
                        'PYG',
                        'DJF',
                        'RWF',
                        'GNF',
                        'UGX',
                        'JPY',
                        'VND',
                        'VUV',
                        'XAF',
                        'KMF',
                        'KRW',
                        'XOF',
                        'XPF',
                    ]
                ) ? number_format($price, 2, '.', '') : number_format($price, 2, '.', '') * 100;

                \Stripe\Stripe::setApiKey(company_setting('stripe_secret', $hotel->created_by, $hotel->wokspace));
                $data = \Stripe\Charge::create([
                    // "amount" => 100 * $price,
                    "amount" => (int) $stripe_formatted_price,
                    "currency" => !empty(company_setting('defult_currancy', $hotel->created_by, $hotel->wokspace)) ? company_setting('defult_currancy', $hotel->created_by, $hotel->wokspace) : 'INR',
                    "source" => $request->stripeToken,
                    "description" => " Booking ",
                    "metadata" => ["order_id" => $orderID]
                ]);
            } else {
                $data['amount_refunded'] = 0;
                $data['failure_code'] = '';
                $data['paid'] = 1;
                $data['captured'] = 1;
                $data['status'] = 'succeeded';
            }

            if ($data['amount_refunded'] == 0 && empty($data['failure_code']) && $data['paid'] == 1 && $data['captured'] == 1) {
                if (!auth()->guard('holiday')->user()) {
                    $booking_number = \Modules\Holidayz\Entities\Utility::getLastId('room_booking', 'booking_number');
                    $booking = RoomBooking::create([
                        'booking_number' => $booking_number,
                        'user_id' => isset(auth()->guard('holiday')->user()->id) ? auth()->guard('holiday')->user()->id : 0,
                        'payment_method' => __('STRIPE'),
                        'payment_status' => 1,
                        'invoice' => isset($data['receipt_url']) ? $data['receipt_url'] : '',
                        'workspace' => $hotel->workspace,
                        'created_by' => $hotel->created_by,
                        'total' => $price,
                        'coupon_id' => $couponsId,
                        'first_name' => $request->firstname,
                        'last_name' => $request->lastname,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'address' => $request->address,
                        'city' => $request->city,
                        'country' => ($request->country) ? $request->country : 'india',
                        'zipcode' => $request->zipcode,
                    ]);
                    foreach ($Carts as $key => $value) {
                        //
                        $toDate = \Carbon\Carbon::parse($value['check_in']);
                        $fromDate = \Carbon\Carbon::parse($value['check_out']);

                        $days = $toDate->diffInDays($fromDate);
                        //
                        $bookingOrder = RoomBookingOrder::create([
                            'booking_id' => $booking->id,
                            'customer_id' => isset(auth()->guard('holiday')->user()->id) ? auth()->guard('holiday')->user()->id : 0,
                            'room_id' => $value['room_id'],
                            'workspace' => $value['workspace'],
                            'check_in' => $value['check_in'],
                            'check_out' => $value['check_out'],
                            'price' => $value['price'] * $value['room'] * $days, // * $value['room']
                            'room' => $value['room'],
                            'service_charge' => $value['serviceCharge'],
                            'services' => $value['serviceIds'],
                        ]);
                        unset($Carts[$key]);
                    }
                    $cart_json = json_encode($Carts);
                    Cookie::queue('cart', $cart_json, 1440);
                } else {
                    $booking_number = \Modules\Holidayz\Entities\Utility::getLastId('room_booking', 'booking_number');
                    $booking = RoomBooking::create([
                        'booking_number' => $booking_number,
                        'user_id' => auth()->guard('holiday')->user()->id,
                        'payment_method' => __('STRIPE'),
                        'payment_status' => 1,
                        'total' => $price,
                        'coupon_id' => $couponsId,
                        'invoice' => isset($data['receipt_url']) ? $data['receipt_url'] : 'free coupon',
                        'workspace' => $hotel->workspace,
                        'created_by' => $hotel->created_by,
                    ]);
                    foreach ($Carts as $key => $value) {
                        $bookingOrder = RoomBookingOrder::create([
                            'booking_id' => $booking->id,
                            'customer_id' => auth()->guard('holiday')->user()->id,
                            'room_id' => $value->room_id,
                            'workspace' => $value->workspace,
                            'check_in' => $value->check_in,
                            'check_out' => $value->check_out,
                            'price' => $value->price,   // * $value->room
                            'room' => $value->room,
                            'service_charge' => $value->service_charge,
                            'services' => $value->services,
                        ]);
                    }
                    RoomBookingCart::where(['customer_id' => auth()->guard('holiday')->user()->id])->delete();
                }
                if (!empty($request->coupon) && $couponsId != 0) {
                    $coupons = BookingCoupons::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    $userCoupon = new UsedBookingCoupons();
                    $userCoupon->customer_id = isset(auth()->guard('holiday')->user()->id) ? auth()->guard('holiday')->user()->id : 0;
                    $userCoupon->coupon_id = $coupons->id;
                    $userCoupon->save();
                }

                if ($data['status'] == 'succeeded') {
                    event(new CreateRoomBooking($request, $booking));
                    $type = 'roombookinginvoice';
                    event(new StripePaymentStatus($hotel, $type, $booking));

                    //Email notification
                    if (!empty(company_setting('New Room Booking By Hotel Customer', $hotel->created_by, $hotel->workspace)) && company_setting('New Room Booking By Hotel Customer', $hotel->created_by, $hotel->workspace) == true) {
                        $user = User::where('id', $hotel->created_by)->first();
                        $customer = HotelCustomer::find($booking->user_id);
                        $room = \Modules\Holidayz\Entities\Rooms::find($bookingOrder->room_id);
                        $uArr = [
                            'hotel_customer_name' => isset($customer->name) ? $customer->name : $booking->first_name,
                            'invoice_number' => $booking->booking_number,
                            'check_in_date' => $bookingOrder->check_in,
                            'check_out_date' => $bookingOrder->check_out,
                            'room_type' => $room->type,
                            'hotel_name' => $hotel->name,
                        ];

                        try {
                            $resp = EmailTemplate::sendEmailTemplate('New Room Booking By Hotel Customer', [$user->email], $uArr);
                        } catch (\Exception $e) {
                            $resp['error'] = $e->getMessage();
                        }

                        return redirect()->route('hotel.home', $slug)->with('success', __('Booking Successfully.') . ((isset($resp['error'])) ? '<br> <span class="text-danger" style="color:red">' . $resp['error'] . '</span>' : ''));
                    }
                    return redirect()->route('hotel.home', $slug)->with('success', 'Booking Successfully. email notification is off.');
                    // return redirect()->route('hotel.home',$slug)->with('success', __('Booking successfully activated.'));
                } else {
                    return redirect()->route('hotel.home', $slug)->with('error', __('Your payment has failed.'));
                }
            } else {
                return redirect()->route('hotel.home', $slug)->with('error', __('Transaction has been failed.'));
            }
        }
    }



    public function BookinginvoicePayWithStripe(Request $request, $slug)
    {
        if ($request->type == "roombookinginvoice") {
            $hotel = Hotels::where('slug', $slug)->first();
            $user_id = !empty($hotel->created_by) ? $hotel->created_by : auth()->guard('web')->user()->id;
            $wokspace = $hotel->workspace;
        }

        self::payment_setting($user_id, $wokspace);
        if (isset($this->is_stripe_enabled) && $this->is_stripe_enabled == 'on' && !empty($this->stripe_key) && !empty($this->stripe_secret)) {

            $user = Auth::user();
            $comapany_stripe_data = '';
            $invoice_id = $slug;

            if ($hotel) {

                /* Check for code usage */
                // $price = $request->amount;
                $grandTotal = $couponsId = 0;
                if (!auth()->guard('holiday')->user()) {
                    $Carts = Cookie::get('cart');
                    $Carts = json_decode($Carts, true);
                    foreach ($Carts as $key => $value) {
                        //
                        $toDate = \Carbon\Carbon::parse($value['check_in']);
                        $fromDate = \Carbon\Carbon::parse($value['check_out']);

                        $days = $toDate->diffInDays($fromDate);
                        //
                        $grandTotal += $value['price'] * $value['room'] * $days;
                        $grandTotal += ($value['serviceCharge']) ? $value['serviceCharge'] : 0;
                    }
                } else {
                    $Carts = RoomBookingCart::where(['customer_id' => auth()->guard('holiday')->user()->id])->get();
                    foreach ($Carts as $key => $value) {
                        $grandTotal += $value->price;   // * $value->room
                        $grandTotal += ($value->service_charge) ? $value->service_charge : 0;
                    }
                }

                $price = $grandTotal;

                if (!empty($request->coupon)) {
                    $coupons = BookingCoupons::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    if (!empty($coupons)) {
                        $usedCoupun = $coupons->used_coupon();
                        $discount_value = ($price / 100) * $coupons->discount;
                        $price = $price - $discount_value;
                        $couponsId = $coupons->id;
                        if ($coupons->limit == $usedCoupun) {
                            return redirect()->back()->with('error', __('This coupon code has expired.'));
                        }
                    } else {
                        return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }

                if ($request->type == "roombookinginvoice") {
                    $booking_number = \Modules\Holidayz\Entities\Utility::getLastId('room_booking', 'booking_number');
                    $printID = \Modules\Holidayz\Entities\RoomBooking::bookingNumberFormat($booking_number, $user_id, $wokspace);
                }

                try {
                    $stripe_formatted_price = in_array(
                        company_setting('defult_currancy', $user_id, $wokspace),
                        [
                            'MGA',
                            'BIF',
                            'CLP',
                            'PYG',
                            'DJF',
                            'RWF',
                            'GNF',
                            'UGX',
                            'JPY',
                            'VND',
                            'VUV',
                            'XAF',
                            'KMF',
                            'KRW',
                            'XOF',
                            'XPF',
                        ]
                    ) ? number_format($price, 2, '.', '') : number_format($price, 2, '.', '') * 100;

                    $return_url_parameters = function ($return_type) {
                        return '&return_type=' . $return_type;
                    };
                    /* Initiate Stripe */
                    \Stripe\Stripe::setApiKey(company_setting('stripe_secret', $user_id, $wokspace));
                    $code = '';

                    $comapany_stripe_data = \Stripe\Checkout\Session::create(
                        [
                            'payment_method_types' => ['card'],
                            'line_items' => [
                                [
                                    'name' => $printID,
                                    'amount' => (int) $stripe_formatted_price,
                                    'currency' => $this->currancy,
                                    'quantity' => 1,
                                ],
                            ],
                            'metadata' => [
                                'user_id' => isset($user->name) ? $user->name : 0,
                                'package_id' => $slug,
                                'code' => $code,
                            ],
                            'success_url' => route(
                                'booking.stripe',
                                [
                                    'invoice_id' => $slug,
                                    $request->type,
                                    $return_url_parameters('success'),
                                ]
                            ),
                            'cancel_url' => route(
                                'booking.stripe',
                                [
                                    'invoice_id' => $slug,
                                    $return_url_parameters('cancel'),
                                ]
                            ),
                        ]

                    );

                    $data = [
                        'amount' => $price,
                        'currency' => $this->currancy,
                        'stripe' => $comapany_stripe_data,
                        'cart' => $Carts,
                        'coupon' => $request->coupon,
                        'coupon_id' => $couponsId

                    ];
                    $request->session()->put('comapany_stripe_data', $data);
                    session()->put('guestInfo', $request->only(['firstname', 'email', 'address', 'country', 'lastname', 'phone', 'city', 'zipcode']));
                    $comapany_stripe_data = $comapany_stripe_data ?? false;
                    return new RedirectResponse($comapany_stripe_data->url);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', $e);
                    \Log::debug($e->getMessage());
                }
            } else {
                if ($request->type == 'roombookinginvoice') {

                    return redirect()->route('hotel.home', $slug)->with('error', __('Salesinvoice is deleted.'));
                }
            }
        } else {
            return redirect()->back()->with('error', __('Please Enter Stripe Details.'));
        }
    }

    public function getBookingInvoicePaymentStatus($invoice_id, Request $request, $type)
    {
        try {
            if ($request->return_type == 'success') {
                $slug = $invoice_id;
                if ($type == 'roombookinginvoice') {
                    if (!empty($invoice_id)) {
                        $invoice_id = $invoice_id;
                        $invoice = Hotels::where('slug', $invoice_id)->first();
                        \Log::debug((array) $request->all());
                        $session_data = $request->session()->get('comapany_stripe_data');
                        try {
                            $stripe = new \Stripe\StripeClient(!empty(company_setting('stripe_secret', $invoice->created_by, $invoice->workspace)) ? company_setting('stripe_secret', $invoice->created_by, $invoice->workspace) : '');
                            $paymentIntents = $stripe->paymentIntents->retrieve(
                                $session_data['stripe']->payment_intent,
                                []
                            );
                            $receipt_url = $paymentIntents->charges->data[0]->receipt_url;
                        } catch (\Exception $exception) {

                            $receipt_url = "";
                        }
                        Session::forget('comapany_stripe_data');
                        $request->session()->forget('comapany_stripe_data');
                        $get_data = $session_data;
                        $guestDetails = session()->get('guestInfo');
                        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                        if ($invoice) {
                            try {
                                if ($request->return_type == 'success') {
                                    if (!auth()->guard('holiday')->user()) {
                                        $Carts = Cookie::get('cart');
                                        $Carts = json_decode($Carts, true);
                                        $booking_number = \Modules\Holidayz\Entities\Utility::getLastId('room_booking', 'booking_number');
                                        $booking = RoomBooking::create([
                                            'booking_number' => $booking_number,
                                            'user_id' => isset(auth()->guard('holiday')->user()->id) ? auth()->guard('holiday')->user()->id : 0,
                                            'payment_method' => __('STRIPE'),
                                            'payment_status' => 1,
                                            'invoice' => $receipt_url,
                                            'workspace' => $invoice->workspace,
                                            'created_by' => $invoice->created_by,
                                            'total' => $get_data['amount'],
                                            'coupon_id' => $get_data['coupon_id'],
                                            'first_name' => $guestDetails['firstname'],
                                            'last_name' => $guestDetails['lastname'],
                                            'email' => $guestDetails['email'],
                                            'phone' => $guestDetails['phone'],
                                            'address' => $guestDetails['address'],
                                            'city' => $guestDetails['city'],
                                            'country' => ($guestDetails['country']) ? $guestDetails['country'] : 'india',
                                            'zipcode' => $guestDetails['zipcode'],
                                        ]);
                                        foreach ($Carts as $key => $value) {
                                            //
                                            $toDate = \Carbon\Carbon::parse($value['check_in']);
                                            $fromDate = \Carbon\Carbon::parse($value['check_out']);

                                            $days = $toDate->diffInDays($fromDate);
                                            //
                                            $bookingOrder = RoomBookingOrder::create([
                                                'booking_id' => $booking->id,
                                                'customer_id' => isset(auth()->guard('holiday')->user()->id) ? auth()->guard('holiday')->user()->id : 0,
                                                'room_id' => $value['room_id'],
                                                'workspace' => $value['workspace'],
                                                'check_in' => $value['check_in'],
                                                'check_out' => $value['check_out'],
                                                'price' => $value['price'] * $value['room'] * $days,
                                                'room' => $value['room'],
                                                'service_charge' => $value['serviceCharge'],
                                                'services' => $value['serviceIds'],
                                            ]);
                                            unset($Carts[$key]);
                                        }
                                        $cart_json = json_encode($Carts);
                                        Cookie::queue('cart', $cart_json, 1440);

                                        if (!empty($get_data['coupon']) && $get_data['coupon_id'] != 0) {
                                            $coupons = BookingCoupons::where('code', strtoupper($get_data['coupon']))->where('is_active', '1')->first();
                                            $userCoupon = new UsedBookingCoupons();
                                            $userCoupon->customer_id = isset(auth()->guard('holiday')->user()->id) ? auth()->guard('holiday')->user()->id : 0;
                                            $userCoupon->coupon_id = $coupons->id;
                                            $userCoupon->save();
                                        }
                                    } else {
                                        $Carts = RoomBookingCart::where(['customer_id' => auth()->guard('holiday')->user()->id])->get();
                                        $booking_number = \Modules\Holidayz\Entities\Utility::getLastId('room_booking', 'booking_number');
                                        $booking = RoomBooking::create([
                                            'booking_number' => $booking_number,
                                            'user_id' => auth()->guard('holiday')->user()->id,
                                            'payment_method' => __('STRIPE'),
                                            'payment_status' => 1,
                                            'total' => $get_data['amount'],
                                            'coupon_id' => $get_data['coupon_id'],
                                            'invoice' => $receipt_url,
                                            'workspace' => $invoice->workspace,
                                            'created_by' => $invoice->created_by,
                                        ]);
                                        foreach ($get_data['cart'] as $key => $value) {
                                            $bookingOrder = RoomBookingOrder::create([
                                                'booking_id' => $booking->id,
                                                'customer_id' => auth()->guard('holiday')->user()->id,
                                                'room_id' => $value->room_id,
                                                'workspace' => $value->workspace,
                                                'check_in' => $value->check_in,
                                                'check_out' => $value->check_out,
                                                'price' => $value->price,   //  * $value->room
                                                'room' => $value->room,
                                                'service_charge' => $value->service_charge,
                                                'services' => $value->services,
                                            ]);
                                        }
                                        RoomBookingCart::where(['customer_id' => auth()->guard('holiday')->user()->id])->delete();

                                        if (!empty($get_data['coupon']) && $get_data['coupon_id'] != 0) {
                                            $coupons = BookingCoupons::where('code', strtoupper($get_data['coupon']))->where('is_active', '1')->first();
                                            $userCoupon = new UsedBookingCoupons();
                                            $userCoupon->customer_id = isset(auth()->guard('holiday')->user()->id) ? auth()->guard('holiday')->user()->id : 0;
                                            $userCoupon->coupon_id = $coupons->id;
                                            $userCoupon->save();
                                        }
                                    }

                                    event(new CreateRoomBooking($request, $booking));
                                    $type = 'roombookinginvoice';
                                    event(new StripePaymentStatus($invoice, $type, $booking));

                                    //Email notification
                                    if (!empty(company_setting('New Room Booking By Hotel Customer', $invoice->created_by, $invoice->workspace)) && company_setting('New Room Booking By Hotel Customer', $invoice->created_by, $invoice->workspace) == true) {
                                        $user = User::where('id', $invoice->created_by)->first();
                                        $customer = HotelCustomer::find($booking->user_id);
                                        $room = \Modules\Holidayz\Entities\Rooms::find($bookingOrder->room_id);
                                        $uArr = [
                                            'hotel_customer_name' => isset($customer->name) ? $customer->name : $booking->first_name,
                                            'invoice_number' => $booking->booking_number,
                                            'check_in_date' => $bookingOrder->check_in,
                                            'check_out_date' => $bookingOrder->check_out,
                                            'room_type' => $room->type,
                                            'hotel_name' => $invoice->name,
                                        ];

                                        try {
                                            $resp = EmailTemplate::sendEmailTemplate('New Room Booking By Hotel Customer', [$user->email], $uArr);
                                        } catch (\Exception $e) {
                                            $resp['error'] = $e->getMessage();
                                        }

                                        return redirect()->route('hotel.home', $slug)->with('success', __('Booking Successfully.') . ((isset($resp['error'])) ? '<br> <span class="text-danger" style="color:red">' . $resp['error'] . '</span>' : ''));
                                    }
                                    return redirect()->route('hotel.home', $slug)->with('success', 'Booking Successfully. email notification is off.');

                                    return redirect()->route('hotel.home', $slug)->with('success', __('Payment added Successfully'));
                                } else {

                                    return redirect()->route('hotel.home', $slug)->with('error', __('Transaction has been failed!'));
                                }
                            } catch (\Exception $e) {
                                return redirect()->route('hotel.home', $slug)->with('error', __('Transaction has been failed!'));
                            }
                        } else {
                            return redirect()->route('hotel.home', $slug)->with('error', __('Invoice not found.'));
                        }
                    } else {
                        return redirect()->route('hotel.home', $slug)->with('error', __('Invoice not found.'));
                    }
                }
            } else {

                return redirect()->back()->with('error', __('Transaction has been failed.'));
            }
        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());
        }
    }


    public function propertyPayWithStripe(Request $request)
    {
        $invoice = \Modules\PropertyManagement\Entities\PropertyInvoice::find($request->invoice_id);
        $user_id = $invoice->created_by;
        $wokspace = $invoice->workspace;
        self::payment_setting($user_id, $wokspace);

        if (isset($this->is_stripe_enabled) && $this->is_stripe_enabled == 'on' && !empty($this->stripe_key) && !empty($this->stripe_secret)) {

            $tenant = Auth::user();
            $user = User::find($tenant->user_id);

            $comapany_stripe_data = '';
            if ($invoice) {

                $invoiceID = $invoice->id;
                $printID = \Modules\PropertyManagement\Entities\PropertyInvoice::tenantNumberFormat($invoiceID, $user_id, $wokspace);

                /* Check for code usage */
                $price = $invoice->total_amount;

                try {

                    $stripe_formatted_price = in_array(
                        company_setting('defult_currancy', $user_id, $wokspace),
                        [
                            'MGA',
                            'BIF',
                            'CLP',
                            'PYG',
                            'DJF',
                            'RWF',
                            'GNF',
                            'UGX',
                            'JPY',
                            'VND',
                            'VUV',
                            'XAF',
                            'KMF',
                            'KRW',
                            'XOF',
                            'XPF',
                        ]
                    ) ? number_format($price, 2, '.', '') : number_format($price, 2, '.', '') * 100;

                    $return_url_parameters = function ($return_type) {
                        return '&return_type=' . $return_type;
                    };
                    /* Initiate Stripe */
                    \Stripe\Stripe::setApiKey(company_setting('stripe_secret', $user_id, $wokspace));
                    $code = '';

                    $comapany_stripe_data = \Stripe\Checkout\Session::create(
                        [
                            'payment_method_types' => ['card'],
                            'line_items' => [
                                [
                                    'price_data' => [
                                        'currency' => $this->currancy,
                                        'unit_amount' => (int) $stripe_formatted_price,
                                        'product_data' => [
                                            'name' => $printID,
                                        ],
                                    ],
                                    'quantity' => 1,
                                ],
                            ],
                            'mode' => 'payment',
                            'metadata' => [
                                'user_id' => isset($user->name) ? $user->name : 0,
                                'package_id' => $invoiceID,
                                'code' => $code,
                            ],
                            'success_url' => route(
                                'property.get.payment.status',
                                [
                                    'invoice_id' => encrypt($invoiceID),
                                    $return_url_parameters('success'),
                                ]
                            ),
                            'cancel_url' => route(
                                'property.get.payment.status',
                                [
                                    'invoice_id' => encrypt($invoiceID),
                                    $return_url_parameters('cancel'),
                                ]
                            ),
                        ]

                    );

                    $data = [
                        'amount' => $price,
                        'currency' => $this->currancy,
                        'stripe' => $comapany_stripe_data

                    ];
                    $request->session()->put('comapany_stripe_data', $data);

                    $comapany_stripe_data = $comapany_stripe_data ?? false;
                    return new RedirectResponse($comapany_stripe_data->url);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', $e);
                    \Log::debug($e->getMessage());
                }
            } else {
                return redirect()->route('property-invoice.index')->with('error', __('Invoice is deleted.'));
            }
        } else {
            return redirect()->back()->with('error', __('Please Enter Stripe Details.'));
        }
    }

    public function propertyGetStripeStatus(Request $request)
    {
        \Log::debug((array) $request->all());
        try {
            if ($request->return_type == 'success') {
                $invoice_id = $request->invoice_id;
                if (!empty($invoice_id)) {
                    $invoice_id = decrypt($invoice_id);
                    $invoice = \Modules\PropertyManagement\Entities\PropertyInvoice::find($invoice_id);
                    $session_data = $request->session()->get('comapany_stripe_data');
                    try {
                        $stripe = new \Stripe\StripeClient(!empty(company_setting('stripe_secret', $invoice->created_by, $invoice->workspace)) ? company_setting('stripe_secret', $invoice->created_by, $invoice->workspace) : '');
                        $paymentIntents = $stripe->paymentIntents->retrieve(
                            $session_data['stripe']->payment_intent,
                            []
                        );
                        $receipt_url = $paymentIntents->charges->data[0]->receipt_url;
                    } catch (\Exception $exception) {
                        $receipt_url = "";
                    }
                    Session::forget('comapany_stripe_data');
                    $request->session()->forget('comapany_stripe_data');
                    $get_data = $session_data;
                    // $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                    $tenant = \Modules\PropertyManagement\Entities\Tenant::where('user_id', Auth::user()->id)->first();
                    if ($invoice) {
                        try {
                            if ($request->return_type == 'success') {
                                $invoice_payment = new \Modules\PropertyManagement\Entities\PropertyInvoicePayment();
                                $invoice_payment->invoice_id = $invoice_id;
                                $invoice_payment->user_id = $tenant->id;
                                $invoice_payment->date = date('Y-m-d');
                                $invoice_payment->amount = isset($get_data['amount']) ? $get_data['amount'] : 0;
                                $invoice_payment->payment_type = __('STRIPE');
                                $invoice_payment->receipt = $receipt_url;
                                $invoice_payment->save();


                                $invoice->status = 'Paid';
                                $invoice->save();

                                $type = 'propertyinvoice';
                                event(new StripePaymentStatus($invoice, $type, $invoice_payment));

                                //Email notification
                                if (!empty(company_setting('Property Invoice Payment Create', $invoice->created_by, $invoice->workspace)) && company_setting('Property Invoice Payment Create', $invoice->created_by, $invoice->workspace) == true) {
                                    // $user = User::where('id',$invoice->created_by)->first();
                                    $user = User::find(Auth::user()->id);
                                    $uArr = [
                                        'payment_name' => isset(Auth::user()->name) ? Auth::user()->name : '',
                                        'invoice_number' => \Modules\PropertyManagement\Entities\PropertyInvoice::tenantNumberFormat($invoice_id),
                                        'payment_amount' => $invoice_payment->amount,
                                        'payment_date' => $invoice_payment->date,
                                    ];

                                    try {
                                        $resp = EmailTemplate::sendEmailTemplate('Property Invoice Payment Create', [$user->email], $uArr);
                                    } catch (\Exception $e) {
                                        $resp['error'] = $e->getMessage();
                                    }

                                    return redirect()->route('property-invoice.show', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('success', __('Invoice paid Successfully.') . ((isset($resp['error'])) ? '<br> <span class="text-danger" style="color:red">' . $resp['error'] . '</span>' : ''));
                                }

                                return redirect()->route('property-invoice.show', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('success', __('Invoice paid Successfully. email notification is off.'));
                            } else {
                                return redirect()->route('property-invoice.show', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('error', __('Transaction has been failed!'));
                            }
                        } catch (\Exception $e) {

                            return redirect()->route('property-invoice.show', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('error', __('Transaction has been failed!'));
                        }
                    } else {

                        return redirect()->route('property-invoice.show', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('error', __('Invoice not found.'));
                    }
                } else {

                    return redirect()->route('property-invoice.show', $invoice_id)->with('error', __('Invoice not found.'));
                }
            } else {

                return redirect()->back()->with('error', __('Transaction has been failed.'));
            }
        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function vehicleBookingWithStripe(Request $request, $slug, $id)
    {
        $workspace = WorkSpace::where('slug', $slug)->first();
        $price = $request->total_price;
        self::payment_setting($workspace->created_by, $workspace->id);

        if (isset($this->is_stripe_enabled) && $this->is_stripe_enabled == 'on' && !empty($this->stripe_key) && !empty($this->stripe_secret)) {
            // try {
            $stripe_formatted_price = in_array(
                company_setting('defult_currancy', $workspace->created_by, $workspace->id),
                [
                    'MGA',
                    'BIF',
                    'CLP',
                    'PYG',
                    'DJF',
                    'RWF',
                    'GNF',
                    'UGX',
                    'JPY',
                    'VND',
                    'VUV',
                    'XAF',
                    'KMF',
                    'KRW',
                    'XOF',
                    'XPF',
                ]
            ) ? number_format($price, 2, '.', '') : number_format($price, 2, '.', '') * 100;

            $return_url_parameters = function ($return_type) {
                return '&return_type=' . $return_type;
            };
            /* Initiate Stripe */
            \Stripe\Stripe::setApiKey(company_setting('stripe_secret', $workspace->created_by, $workspace->id));
            $code = '';

            $comapany_stripe_data = \Stripe\Checkout\Session::create(
                [
                    'payment_method_types' => ['card'],
                    'line_items' => [
                        [
                            'name' => 'booking',
                            'amount' => (int) $stripe_formatted_price,
                            'currency' => $this->currancy,
                            'quantity' => 1,
                        ],
                    ],
                    'metadata' => [
                        'user_id' => isset($user->name) ? '' : 0,
                        'package_id' => $slug,
                        'code' => $code,
                    ],
                    'success_url' => route(
                        'vehicle.booking.status',
                        [
                            'slug' => $slug,
                            'id' => $id,
                            'request_data' => $request->all(),
                            'route_id' => $request->route_id,
                            $return_url_parameters('success'),
                        ]
                    ),
                    'cancel_url' => route(
                        'vehicle.booking.create',
                        [
                            'slug' => $slug,
                            'id' => $id,
                            $return_url_parameters('cancel'),
                        ]
                    ),
                ]
            );

            $data = [
                'amount' => $price,
                'slug' => $slug,
                'vehicle_id' => $id,
                'currency' => $this->currancy,
                'request_data' => $request->all(),
                'stripe' => $comapany_stripe_data,
            ];

            $request->session()->put('comapany_stripe_data', $data);
            session()->put($request->all(), $slug, $id);
            $comapany_stripe_data = $comapany_stripe_data ?? false;
            return new RedirectResponse($comapany_stripe_data->url);
        }
    }

    public function vehicleBookingStatus(Request $request, $slug, $id)
    {
        if ($request->return_type == 'success') {
            $workspace = WorkSpace::where('slug', $slug)->first();
            if (!empty($workspace)) {
                $session_data = $request->session()->get('comapany_stripe_data');

                $amount = $session_data['amount'];
                try {
                    $stripe = new \Stripe\StripeClient(!empty(company_setting('stripe_secret', $workspace->created_by, $workspace->workspace)) ? company_setting('stripe_secret', $workspace->created_by, $workspace->workspace) : '');
                    $paymentIntents = $stripe->paymentIntents->retrieve(
                        $session_data['stripe']->payment_intent,
                        []
                    );
                    $receipt_url = $paymentIntents->charges->data[0]->receipt_url;
                } catch (\Exception $exception) {

                    $receipt_url = "";
                }

                if ($workspace) {
                    try {
                        if ($request->return_type == 'success') {
                            $request_data = $request->request_data;
                            $payment_data = [
                                'vehicle_route' => json_decode($request_data['vehicle_route'], true),
                                'total_price' => $request_data['total_price'],
                                'email' => $request_data['email'],
                                'users' => $request_data['users'],
                                'trip_date' => $request_data['trip_date'],
                                'selectedSeats' => $request_data['selectedSeats'],
                                'route_id' => $request->route_id
                            ];
                            $type = 'vehiclebookingpayment';

                            $event = event(new StripePaymentStatus($payment_data, $type, $slug));

                            return redirect()->route('vehicle.booking.checkout', [$slug, $event[3]])->with('success', __('Payment added Successfully'));
                        } else {

                            return redirect()->route('vehicle.booking.manage', $slug)->with('error', __('Transaction has been failed!'));
                        }
                    } catch (\Exception $e) {
                        return redirect()->route('vehicle.booking.manage', $slug)->with('error', __('Transaction has been failed!'));
                    }
                } else {
                    return redirect()->route('vehicle.booking.manage', $slug)->with('error', __('Recipt not found.'));
                }
            } else {
                return redirect()->route('vehicle.booking.manage', $slug)->with('error', __('Recipt not found.'));
            }
        } else {
            return redirect()->route('vehicle.booking.manage', $slug)->with('error', __('Payment Fail.'));
        }
    }

    public function memberplanPayWithStripe(Request $request)
    {
        $membershipplan = \Modules\GymManagement\Entities\AssignMembershipPlan::where('id', $request->membershipplan_id)->first();
        self::payment_setting($membershipplan->created_by, $membershipplan->workspace);
        if (isset($this->is_stripe_enabled) && $this->is_stripe_enabled == 'on' && !empty($this->stripe_key) && !empty($this->stripe_secret)) {

            $user      = User::find($membershipplan->user_id);
            $validator = Validator::make(
                $request->all(),
                [
                    'amount' => 'required|numeric',
                    'membershipplan_id' => 'required',
                ]
            );
            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }
            $comapany_stripe_data = '';
            $printID        = \Modules\GymManagement\Entities\GymMember::gymmemberNumberFormat($membershipplan->member_id, $membershipplan->created_by, $membershipplan->workspace);
            if ($membershipplan) {

                /* Check for code usage */
                $price = $request->amount;

                try {

                    $stripe_formatted_price = in_array(
                        company_setting('defult_currancy', $membershipplan->created_by, $membershipplan->workspace),
                        [
                            'MGA',
                            'BIF',
                            'CLP',
                            'PYG',
                            'DJF',
                            'RWF',
                            'GNF',
                            'UGX',
                            'JPY',
                            'VND',
                            'VUV',
                            'XAF',
                            'KMF',
                            'KRW',
                            'XOF',
                            'XPF',
                        ]
                    ) ? number_format($price, 2, '.', '') : number_format($price, 2, '.', '') * 100;

                    $return_url_parameters = function ($return_type) {
                        return '&return_type=' . $return_type;
                    };
                    /* Initiate Stripe */
                    \Stripe\Stripe::setApiKey(company_setting('stripe_secret', $membershipplan->created_by, $membershipplan->workspace));
                    $code = '';

                    $comapany_stripe_data = \Stripe\Checkout\Session::create(
                        [
                            'payment_method_types' => ['card'],
                            'line_items' => [
                                [
                                    'price_data' => [
                                        'currency' => $this->currancy,
                                        'unit_amount' => (int)$stripe_formatted_price,
                                        'product_data' => [
                                            'name' => $printID,
                                        ],
                                    ],
                                    'quantity' => 1,
                                ],
                            ],
                            'mode' => 'payment',
                            'metadata' => [
                                'user_id' => isset($user->name) ? $user->name : 0,
                                'package_id' => $membershipplan->id,
                                'code' => $code,
                            ],
                            'success_url' => route(
                                'memberplan.stripe',
                                [
                                    'membershipplan_id' => encrypt($membershipplan->id),
                                    $return_url_parameters('success'),
                                ]
                            ),
                            'cancel_url' => route(
                                'memberplan.stripe',
                                [
                                    'membershipplan_id' => encrypt($membershipplan->id),
                                    $return_url_parameters('cancel'),
                                ]
                            ),
                        ]

                    );

                    $data           = [
                        'amount' => $price,
                        'currency' => $this->currancy,
                        'stripe' => $comapany_stripe_data

                    ];
                    $request->session()->put('comapany_stripe_data', $data);

                    $comapany_stripe_data = $comapany_stripe_data ?? false;
                    return new RedirectResponse($comapany_stripe_data->url);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', $e);
                    \Log::debug($e->getMessage());
                }
            } else {

                return redirect()->route('pay.membership.plan', encrypt($membershipplan->user_id))->with('error', __('Membership Plan is deleted.'));
            }
        } else {
            return redirect()->back()->with('error', __('Please Enter Stripe Details.'));
        }
    }

    public function getMemberPlanPaymentStatus($membershipplan_id, Request $request)
    {
        try {
            $membershipplan_id = decrypt($membershipplan_id);
            $membershipplan = \Modules\GymManagement\Entities\AssignMembershipPlan::where('id', $membershipplan_id)->first();
            self::payment_setting($membershipplan->created_by, $membershipplan->workspace);
            if ($request->return_type == 'success') {
                if (!empty($membershipplan_id)) {
                    \Log::debug((array)$request->all());
                    $session_data = $request->session()->get('comapany_stripe_data');
                    try {
                        $stripe = new \Stripe\StripeClient(!empty(company_setting('stripe_secret', $membershipplan->created_by, $membershipplan->workspace)) ? company_setting('stripe_secret', $membershipplan->created_by, $membershipplan->workspace) : '');
                        $paymentIntents = $stripe->paymentIntents->retrieve(
                            $session_data['stripe']->payment_intent,
                            []
                        );
                        $receipt_url = $paymentIntents->charges->data[0]->receipt_url;
                    } catch (\Exception $exception) {
                        $receipt_url = "";
                    }
                    Session::forget('comapany_stripe_data');
                    $request->session()->forget('comapany_stripe_data');
                    $get_data   = $session_data;
                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                    if ($membershipplan) {
                        try {
                            if ($request->return_type == 'success') {
                                $membershipplan_payment                  = new \Modules\GymManagement\Entities\MembershipPlanPayment();
                                $membershipplan_payment->member_id       = !empty($membershipplan->member_id) ? $membershipplan->member_id : null;
                                $membershipplan_payment->user_id         = $membershipplan->user_id;
                                $membershipplan_payment->date            = date('Y-m-d');
                                $membershipplan_payment->amount          = isset($get_data['amount']) ? $get_data['amount'] : 0;
                                $membershipplan_payment->order_id        = $orderID;
                                $membershipplan_payment->currency        = isset($get_data['currency']) ? $get_data['currency'] : 'INR';
                                $membershipplan_payment->payment_type    = __('STRIPE');
                                $membershipplan_payment->receipt         = $receipt_url;
                                $membershipplan_payment->save();

                                $type = 'membershipplan';

                                event(new StripePaymentStatus($membershipplan, $type, $membershipplan_payment));


                                return redirect()->route('pay.membership.plan', encrypt($membershipplan->user_id))->with('success', __('Payment added Successfully'));
                            } else {
                                return redirect()->route('pay.membership.plan', encrypt($membershipplan->user_id))->with('error', __('Transaction has been failed!'));
                            }
                        } catch (\Exception $e) {
                            return redirect()->route('pay.membership.plan', encrypt($membershipplan->user_id))->with('error', __('Transaction has been failed!'));
                        }
                    } else {


                        return redirect()->route('pay.membership.plan', encrypt($membershipplan->user_id))->with('error', __('Membership Plan not found.'));
                    }
                } else {

                    return redirect()->route('pay.membership.plan', encrypt($membershipplan->user_id))->with('error', __('Membership Plan not found.'));
                }
            } else {

                return redirect()->back()->with('error', __('Transaction has been failed.'));
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    //Beauty Spa Payment
    public function BeautySpaPayWithStripe(Request $request, $slug, $lang = '')
    {
        $service = BeautyService::find($request->service_id);
        $price = $service->price * $request->person;
        $data = $request->all();
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        if ($service) {
            try {
                $payment_type = 'onetime';
                /* Payment details */
                $code = '';
                /* Final price */
                $stripe_formatted_price = in_array(
                    company_setting('defult_currancy', $service->created_by, $service->wokspace),
                    [
                        'MGA',
                        'BIF',
                        'CLP',
                        'PYG',
                        'DJF',
                        'RWF',
                        'GNF',
                        'UGX',
                        'JPY',
                        'VND',
                        'VUV',
                        'XAF',
                        'KMF',
                        'KRW',
                        'XOF',
                        'XPF',
                    ]
                ) ? number_format($price, 2, '.', '') : number_format($price, 2, '.', '') * 100;
                $return_url_parameters = function ($return_type) {
                    return '&return_type=' . $return_type;
                };
                /* Initiate Stripe */
                \Stripe\Stripe::setApiKey(company_setting('stripe_secret', $service->created_by, $service->wokspace));

                $stripe_session = \Stripe\Checkout\Session::create(
                    [
                        'payment_method_types' => ['card'],
                        'line_items' => [
                            [
                                'price_data' => [
                                    'currency' =>  company_setting('defult_currancy', $service->created_by, $service->wokspace),
                                    'unit_amount' => (int)$stripe_formatted_price,
                                    'product_data' => [
                                        'name' => !empty($service->name) ? $service->name : 'Basic Package',
                                        'description' => 'stripe payment',
                                    ],
                                ],
                                'quantity' => 1,
                            ],
                        ],
                        'mode' => 'payment',
                        'success_url' => route(
                            'beauty.spa.stripe',
                            [
                                'slug' => $slug,
                                'price' => $price,
                                $return_url_parameters('success'),
                            ]
                        ),
                        'cancel_url' => route(
                            'beauty.spa.stripe',
                            [
                                'slug' => $slug,
                                $return_url_parameters('cancel'),
                            ]
                        ),

                    ]
                );
                session()->put('beauty_spa_variable', $data);
                $request->session()->put('stripe_session', $stripe_session);
                $stripe_session = $stripe_session ?? false;
            } catch (\Exception $e) {
                \Log::debug($e->getMessage());
            }
            return view('beautyspamanagement::booking.style', compact('stripe_session', 'service'));
        } else {
            $msg = __('Plan is deleted.');
            return redirect()->back()->with('msg', $msg);
        }
    }

    public function getBeautySpaPaymentStatus(Request $request, $slug)
    {
        $workspace = WorkSpace::where('id', $slug)->first();

        try {
            $stripe = new \Stripe\StripeClient(!empty(company_setting('stripe_secret', $workspace->created_by, $workspace->id)) ? company_setting('stripe_secret', $workspace->created_by, $workspace->id) : '');
            $paymentIntents = $stripe->paymentIntents->retrieve(
                $request->session()->get('stripe_session')->payment_intent,
                []
            );
            $receipt_url = $paymentIntents->charges->data[0]->receipt_url;
        } catch (\Exception $exception) {
            $receipt_url = "";
        }
        try {
            if ($request->return_type == 'success' && !empty(session()->get('beauty_spa_variable'))) {
                $data = session()->get('beauty_spa_variable');


                $beautybooking                  = new BeautyBooking();
                $beautybooking->name            = $data['name'];
                $beautybooking->service         = $data['service_id'];
                $beautybooking->date            = $data['date'];
                $beautybooking->number          = $data['number'];
                $beautybooking->email           = $data['email'];
                $beautybooking->stage_id        = 2;
                $beautybooking->person          = $data['person'];
                $beautybooking->gender          = $data['gender'];
                $beautybooking->start_time      = $data['start_time'];
                $beautybooking->end_time        = $data['end_time'];
                $beautybooking->payment_option  = $data['payment_option'];
                $beautybooking->workspace       = $workspace->id;
                $beautybooking->created_by      = $workspace->created_by;
                $beautybooking->save();


                $beautyreceipt                  = new BeautyReceipt();
                $beautyreceipt->booking_id      = $beautybooking->id;
                $beautyreceipt->name            = $beautybooking->name;
                $beautyreceipt->service         = $beautybooking->service;
                $beautyreceipt->number          = $beautybooking->number;
                $beautyreceipt->gender          = $beautybooking->gender;
                $beautyreceipt->start_time      = $beautybooking->start_time;
                $beautyreceipt->end_time        = $beautybooking->end_time;
                $beautyreceipt->price           = $request->price;
                $beautyreceipt->payment_type    =  __('STRIPE');
                $beautyreceipt->workspace       = $workspace->id;
                $beautyreceipt->created_by      = $workspace->created_by;
                $beautyreceipt->save();

                $type = 'beautypayment';

                event(new StripePaymentStatus($beautybooking, $type, $beautyreceipt));
                $msg = __('Payment has been success.');
                return redirect()->route('beauty.home', [$slug],)->with('msg', $msg);
            } else {
                $msg = __('Transaction has been failed');
                return redirect()->back()->with('msg', $msg);
            }
        } catch (\Exception $exception) {
            $msg = __('Transaction has been failed');
            return redirect()->back()->with('msg', $msg);
        }
    }

    //Movie Show Booking Payment


    public function MovieShowBookingPayWithStripe(Request $request, $slug, $lang = '')
    {

        $seatsData = json_decode($request->input('seatsData'), true);
        $workspace = WorkSpace::where('slug', $slug)->first();
        $price = $request->totalPrice;
        $data = $request->all();

        self::payment_setting($workspace->created_by, $workspace->id);

        if (isset($this->is_stripe_enabled) && $this->is_stripe_enabled == 'on' && !empty($this->stripe_key) && !empty($this->stripe_secret)) {
            try {
                $stripe_formatted_price = in_array(
                    company_setting('defult_currancy', $workspace->created_by, $workspace->id),
                    [
                        'MGA',
                        'BIF',
                        'CLP',
                        'PYG',
                        'DJF',
                        'RWF',
                        'GNF',
                        'UGX',
                        'JPY',
                        'VND',
                        'VUV',
                        'XAF',
                        'KMF',
                        'KRW',
                        'XOF',
                        'XPF',
                    ]
                ) ? number_format($price, 2, '.', '') : number_format($price, 2, '.', '') * 100;

                $return_url_parameters = function ($return_type) {
                    return '&return_type=' . $return_type;
                };
                /* Initiate Stripe */

                \Stripe\Stripe::setApiKey(company_setting('stripe_secret', $workspace->created_by, $workspace->id));
                $code = '';

                $comapany_stripe_data = \Stripe\Checkout\Session::create(
                    [
                        'payment_method_types' => ['card'],
                        'line_items' => [
                            [
                                'price_data' => [
                                    'currency' =>  company_setting('defult_currancy', $workspace->created_by, $workspace->id),
                                    'unit_amount' => (int)$stripe_formatted_price,
                                    'product_data' => [
                                        'name' => !empty($workspace->name) ? $workspace->name :'Basic Package',
                                        'description' => 'stripe payment',
                                    ],
                                ],
                                'quantity' => 1,
                            ],
                        ],
                        'mode' => 'payment',
                        'success_url' => route(
                            'movie.show.booking.stripe',
                            [
                                'slug' => $slug,
                                'price'=> $price,
                                $return_url_parameters('success'),
                            ]
                        ),
                        'cancel_url' => route(
                            'movie.show.booking.stripe',
                            [
                                'slug' => $slug,
                                $return_url_parameters('cancel'),
                            ]
                        ),
                    ]
                );

                $data = [
                    'amount' => $price,
                    'slug' => $slug,
                    'currency' => $this->currancy,
                    'request_data' => $request->all(),
                    'stripe' => $comapany_stripe_data,
                ];
                $request->session()->put('movie_show_data', $data);
                session()->put($request->all(), $slug, $workspace->id);
                $comapany_stripe_data = $comapany_stripe_data ?? false;

                return new RedirectResponse($comapany_stripe_data->url);

            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e);
                \Log::debug($e->getMessage());
            }
        }
    }

    public function getMovieShowBookingPaymentStatus(Request $request, $slug)
    {
        $workspace = WorkSpace::where('slug', $slug)->first();
        try {
            $stripe = new \Stripe\StripeClient(!empty(company_setting('stripe_secret', $workspace->created_by, $workspace->id)) ? company_setting('stripe_secret', $workspace->created_by, $workspace->id) : '');
            $paymentIntents = $stripe->paymentIntents->retrieve(
                $request->session()->get('stripe_session')->payment_intent,
                []
            );
            $receipt_url = $paymentIntents->charges->data[0]->receipt_url;
        } catch (\Exception $exception) {
            $receipt_url = "";
        }

        try {
            if ($request->return_type == 'success' && !empty(session()->get('movie_show_data'))) {
                $data = session()->get('movie_show_data');
                $seatsData = json_decode($data['request_data']['seatsData'], true);
                $orderID = crc32(uniqid('', true));

                foreach ($seatsData as $seatData) {

                    //     // Create a new SeatBooking instance and set its properties
                        $seatBooking = new MovieSeatBooking();
                        $seatBooking->movie_id = $seatData['movieId'];
                        $seatBooking->seating_template_detail_id = $seatData['seatingTemplateDetailId'];
                        $seatBooking->row = $seatData['row'];
                        $seatBooking->column = $seatData['column'];
                        $seatBooking->booking_date = $data['request_data']['date'];
                        $seatBooking->booking_show_time = $data['request_data']['ShowTime'];
                        $seatBooking->workspace = $workspace->id;
                        $seatBooking->created_by = $workspace->created_by;
                        $seatBooking->save();
                }


                $movieseatbooking                  = new MovieSeatBookingOrder();
                $movieseatbooking->movie_id        = $data['request_data']['movieId'];
                $movieseatbooking->order_id        = $orderID;
                $movieseatbooking->seat_data       = $data['request_data']['seatsData'];
                $movieseatbooking->booking_show_time = $data['request_data']['ShowTime'];
                $movieseatbooking->name            = $data['request_data']['name'];
                $movieseatbooking->email           = $data['request_data']['email'];
                $movieseatbooking->mobile_number   = $data['request_data']['mobile_number'];
                $movieseatbooking->price           = $data['amount'];
                $movieseatbooking->payment_type    =  __('STRIPE');
                $movieseatbooking->payment_status       = 'successful';
                $movieseatbooking->workspace       = $workspace->id;
                $movieseatbooking->created_by      = $workspace->created_by;

                $movieseatbooking->save();

                $type = 'movieshowbookingpayment';

                event(new StripePaymentStatus($seatBooking, $type, $movieseatbooking));

                return redirect()->route('movie.print.ticket', [
                    'slug' => $slug,
                    'seatBooking' => Crypt::encrypt($seatBooking->id),
                    'movieseatbooking' => Crypt::encrypt($movieseatbooking->id),
                ])->with('success', __('Payment has been successful'));
            } else {
                $msg = __('Transaction has been failed');
                return redirect()->back()->with('msg', $msg);
            }
        } catch (\Exception $exception) {
            $msg = __('Transaction has been failed');
            return redirect()->back()->with('msg', $msg);
        }
    }

    //Parking Payment
    public function parkingPayWithStripe(Request $request, $slug, $lang = '')
    {
        if ($request->payment) {

            $parking = Parking::find($request->parking_id);
            if ($lang == '') {
                $lang = !empty(company_setting('defult_currancy', $parking->created_by, $parking->workspace)) ? company_setting('defult_currancy', $parking->created_by, $parking->workspace) : 'en';
            }
            \App::setLocale($lang);
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            self::payment_setting($parking->created_by, $parking->wokspace_id);
            $return_url_parameters = function ($return_type) {
                return '&return_type=' . $return_type;
            };
            if ($parking->amount <= 0) {
                $parking_payment                       = new Payment();
                $parking_payment->parking_id           = $parking->id;
                $parking_payment->amount               = isset($parking['amount']) ? $parking['amount'] : 0;
                $parking_payment->name                 = $parking->name;
                $parking_payment->order_id             = '#' . time();
                $parking_payment->amount_currency      = !empty(company_setting('defult_currancy', $parking->created_by, $parking->workspace)) ? company_setting('defult_currancy', $parking->created_by, $parking->workspace) : 'USD';
                $parking_payment->payment_type         = __('STRIPE');
                $parking_payment->payment_status       = 'successful';
                $parking_payment->receipt              = '';
                $parking_payment->workspace            = $parking->workspace;
                $parking_payment->created_by           = $parking->created_by;
                $parking_payment->save();
                //parking status update
                $parking->status = 2; //paid
                $parking->save();

                $type = 'parkingpayment';
                event(new StripePaymentStatus($parking, $type, $parking_payment));
                return redirect()->route('parking.home', $slug)->with('successs', __('Payment has been success'));
            }

            if ($parking) {
                try {

                    $stripe_formatted_price = in_array(
                        company_setting('defult_currancy', $parking->created_by, $parking->wokspace_id),
                        [
                            'MGA',
                            'BIF',
                            'CLP',
                            'PYG',
                            'DJF',
                            'RWF',
                            'GNF',
                            'UGX',
                            'JPY',
                            'VND',
                            'VUV',
                            'XAF',
                            'KMF',
                            'KRW',
                            'XOF',
                            'XPF',
                        ]
                    ) ? number_format($parking->amount, 2, '.', '') : number_format($parking->amount, 2, '.', '') * 100;


                    /* Initiate Stripe */
                    \Stripe\Stripe::setApiKey(company_setting('stripe_secret', $parking->created_by, $parking->wokspace_id));
                    $code = '';

                    $comapany_stripe_data = \Stripe\Checkout\Session::create(
                        [
                            'payment_method_types' => ['card'],
                            'line_items' => [
                                [
                                    'price_data' => [
                                        'currency' => $this->currancy,
                                        'unit_amount' => (int)$stripe_formatted_price,
                                        'product_data' => [
                                            'name' => '#' . time(),
                                        ],
                                    ],
                                    'quantity' => 1,
                                ],
                            ],
                            'mode' => 'payment',
                            'metadata' => [
                                'user_id' => isset($parking->name) ? $parking->name : 0,
                                'package_id' => '',
                                'code' => $code,
                            ],
                            'success_url' => route(
                                'parking.stripe',
                                [
                                    $slug,
                                    $parking->id,
                                    $parking->amount,
                                    $lang,
                                    $return_url_parameters('success'),
                                ]
                            ),
                            'cancel_url' => route(
                                'parking.stripe',
                                [
                                    $slug,
                                    $parking->id,
                                    $parking->amount,
                                    $lang,
                                    $return_url_parameters('cancel'),
                                ]
                            ),
                        ]
                    );
                    $data           = [
                        'amount' =>   $parking->amount,
                        'currency' => $this->currancy,
                        'stripe' => $comapany_stripe_data

                    ];
                    $request->session()->put('comapany_stripe_data', $data);

                    $comapany_stripe_data = $comapany_stripe_data ?? false;
                    return new RedirectResponse($comapany_stripe_data->url);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', $e);
                    \Log::debug($e->getMessage());
                }
            } else {
                return redirect()->back()->with('error', __('Plan is deleted.'));
            }
        } else {
            return redirect()->back()->with('error', __('Please select payment method'));
        }
    }

    public function getParkingPaymentStatus(Request $request, $slug, $parking_id, $amount = '', $lang = '')
    {
        try {
            if ($request->return_type == 'success') {
                if (!empty($parking_id)) {
                    $parking    = Parking::find($parking_id);
                    if ($lang == '') {
                        $lang = !empty(company_setting('defult_currancy', $parking->created_by, $parking->workspace)) ? company_setting('defult_currancy', $parking->created_by, $parking->workspace) : 'en';
                    }
                    \App::setLocale($lang);
                    \Log::debug((array)$request->all());
                    $session_data = $request->session()->get('comapany_stripe_data');
                    try {
                        $stripe = new \Stripe\StripeClient(!empty(company_setting('stripe_secret', $parking->created_by, $parking->workspace)) ? company_setting('stripe_secret', $parking->created_by, $parking->workspace) : '');
                        $paymentIntents = $stripe->paymentIntents->retrieve(
                            $session_data['stripe']->payment_intent,
                            []
                        );
                        $receipt_url = $paymentIntents->charges->data[0]->receipt_url;
                    } catch (\Exception $exception) {
                        $receipt_url = "";
                    }
                    Session::forget('comapany_stripe_data');
                    $request->session()->forget('comapany_stripe_data');
                    $get_data   = $session_data;
                    if ($parking) {
                        try {
                            if ($request->return_type == 'success') {
                                $parking_payment                       = new Payment();
                                $parking_payment->parking_id           = $parking_id;
                                $parking_payment->amount               = isset($get_data['amount']) ? $get_data['amount'] : 0;
                                $parking_payment->name                 = $parking->name;
                                $parking_payment->order_id             = '#' . time();
                                $parking_payment->amount_currency             = !empty(company_setting('defult_currancy', $parking->created_by, $parking->workspace)) ? company_setting('defult_currancy', $parking->created_by, $parking->workspace) : 'USD';
                                $parking_payment->payment_type         = __('STRIPE');
                                $parking_payment->payment_status       = 'successful';
                                $parking_payment->receipt              = $receipt_url;
                                $parking_payment->txn_id               = '';
                                $parking_payment->workspace            = $parking->workspace;
                                $parking_payment->created_by           = $parking->created_by;
                                $parking_payment->save();
                                //parking status update
                                $parking->status = 2; //paid
                                $parking->save();

                                $type = 'parkingpayment';
                                event(new StripePaymentStatus($parking, $type, $parking_payment));
                                return redirect()->route('parking.home', [$slug, $lang])->with('successs', __('Payment has been success'));
                            } else {
                                return redirect()->back()->with('error', __('Transaction has been failed.'));
                            }
                        } catch (\Exception $e) {
                            return redirect()->back()->with('error', __('Transaction has been failed.'));
                        }
                    } else {
                        return redirect()->back()->with('error', __('Parking not found.'));
                    }
                } else {
                    return redirect()->back()->with('error', __('Parking not found.'));
                }
            } else {
                return redirect()->back()->with('error', __('Transaction has been failed.'));
            }
        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
