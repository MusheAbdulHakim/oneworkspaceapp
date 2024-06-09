<?php

namespace Modules\Account\Http\Controllers;

use App\Models\Setting;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Account\Entities\AccountUtility;


class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function __construct()
    {
        if(module_is_active('GoogleAuthentication'))
        {
            $this->middleware('2fa');
        }
    }
    public function index()
    {
        if(Auth::check())
        {
            if (Auth::user()->isAbleTo('account dashboard manage'))
            {
                $data['latestIncome']  = \Modules\Account\Entities\Revenue::where('workspace', '=', getActiveWorkSpace())->orderBy('id', 'desc')->limit(5)->get();
                $data['latestExpense'] = \Modules\Account\Entities\Payment::with('vendor')->where('workspace', '=', getActiveWorkSpace())->orderBy('id', 'desc')->limit(5)->get();

                $inColor        = array();
                $inCategory     = array();
                $inAmount       = array();

                $exColor         = array();
                $exCategory      = array();
                $exAmount        = array();
                if(module_is_active('ProductService'))
                {
                    $incomeCategory = \Modules\ProductService\Entities\Category::where('created_by', '=', creatorId())->where('workspace_id', '=', getActiveWorkSpace())->where('type', '=', 1)->get();

                    for($i = 0; $i < count($incomeCategory); $i++)
                    {
                        $inColor[]    = $incomeCategory[$i]->color;
                        $inCategory[] = $incomeCategory[$i]->name;
                        $inAmount[]   = \Modules\Account\Entities\AccountUtility::incomeCategoryRevenueAmount($incomeCategory[$i]['id']);
                    }
                    $expenseCategory = \Modules\ProductService\Entities\Category::where('created_by', '=', creatorId())->where('workspace_id', '=', getActiveWorkSpace())->where('type', '=', 2)->get();

                    for($i = 0; $i < count($expenseCategory); $i++)
                    {
                        $exColor[]    = $expenseCategory[$i]->color;
                        $exCategory[] = $expenseCategory[$i]->name;
                        $exAmount[]   = \Modules\Account\Entities\AccountUtility::expenseCategoryAmount($expenseCategory[$i]['id']);
                    }

                }
                $data['incomeCategoryColor'] = $inColor;
                $data['incomeCategory']      = $inCategory;
                $data['incomeCatAmount']     = $inAmount;

                $data['expenseCategoryColor'] = $exColor;
                $data['expenseCategory']      = $exCategory;
                $data['expenseCatAmount']     = $exAmount;

                $data['incExpBarChartData']  = \Modules\Account\Entities\AccountUtility::getincExpBarChartData();
                $data['incExpLineChartData'] = \Modules\Account\Entities\AccountUtility::getIncExpLineChartDate();

                $data['currentYear']  = date('Y');
                $data['currentMonth'] = date('M');

                $constant['taxes'] = 0;
                $constant['category'] = 0;
                $constant['units'] = 0;
                if(module_is_active('ProductService'))
                {
                    $constant['taxes']         = \Modules\ProductService\Entities\Tax::where('workspace_id', getActiveWorkSpace())->count();
                    $constant['category']      = \Modules\ProductService\Entities\Category::where('created_by', '=', creatorId())->where('workspace_id', getActiveWorkSpace())->count();
                    $constant['units']         = \Modules\ProductService\Entities\Unit::where('workspace_id', getActiveWorkSpace())->count();
                }
                $constant['bankAccount']   = \Modules\Account\Entities\BankAccount::where('workspace', getActiveWorkSpace())->count();

                $data['constant']          = $constant;

                $data['bankAccountDetail'] = \Modules\Account\Entities\BankAccount::where('workspace', '=', getActiveWorkSpace())->get();

                $data['recentInvoice']     = \App\Models\Invoice::where('workspace', '=', getActiveWorkSpace())->where('invoice_module','account')->orderBy('id', 'desc')->limit(5)->get();

                $data['weeklyInvoice']     = \App\Models\Invoice::weeklyInvoice();

                $data['monthlyInvoice']    = \App\Models\Invoice::monthlyInvoice();
                $data['recentBill'] = \Modules\Account\Entities\Bill::with('items')->select('bills.*', 'vendors.name as vendor_name')->where('bills.workspace', '=', getActiveWorkSpace())->join('vendors', 'bills.vendor_id', '=', 'vendors.id')->orderBy('id', 'desc')->limit(5)->get();

                $data['weeklyBill']        =\Modules\Account\Entities\Bill::weeklyBill();
                $data['monthlyBill']       =\Modules\Account\Entities\Bill::monthlyBill();

                if(module_is_active('Goal'))
                {
                    $data['goals']  = \Modules\Goal\Entities\Goal::where('created_by', '=',creatorId())->where('workspace', '=', getActiveWorkSpace())->where('is_display', 1)->get();
                }

                return view('account::dashboard.dashboard', $data);

            }
            else
            {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
        else
        {
            return redirect()->route('login');
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
        return redirect()->back();
        return view('account::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('account::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
    public function setting(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'customer_prefix' => 'required',
            'vendor_prefix' => 'required',
        ]);
        if($validator->fails()){
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        else
        {
            $getActiveWorkSpace = getActiveWorkSpace();
            $creatorId = creatorId();
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
            // Settings Cache forget
            comapnySettingCacheForget();
            return redirect()->back()->with('success','Account setting save sucessfully.');
        }
    }


}
