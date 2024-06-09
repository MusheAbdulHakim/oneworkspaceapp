<?php

namespace Modules\Account\Http\Controllers;

use App\Events\ProductDestroyBill;
use App\Models\EmailTemplate;
use App\Models\Invoice;
use App\Models\Setting;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Modules\Account\Entities\AccountUtility;
use Modules\Account\Entities\BankAccount;
use Modules\Account\Entities\Bill;
use Modules\Account\Entities\BillAccount;
use Modules\Account\Entities\BillAttechment;
use Modules\Account\Entities\BillPayment;
use Modules\Account\Entities\BillProduct;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\Entities\StockReport;
use Modules\Account\Entities\Transaction;
use Modules\Account\Entities\Transfer;
use Modules\Account\Entities\Vender;
use Modules\Account\Events\CreateBill;
use Modules\Account\Events\CreatePaymentBill;
use Modules\Account\Events\DestroyBill;
use Modules\Account\Events\DuplicateBill;
use Modules\Account\Events\PaymentDestroyBill;
use Modules\Account\Events\ResentBill;
use Modules\Account\Events\SentBill;
use Modules\Account\Events\UpdateBill;


class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {

        if (Auth::user()->isAbleTo('bill manage')) {
            $vendor = Vender::where('workspace', '=', getActiveWorkSpace())->get()->pluck('name', 'id');

            $status = Bill::$statues;

            $query = Bill::select('bills.*', 'vendors.name as vendor_name')->where('bills.workspace', '=', getActiveWorkSpace());
            if (!empty($request->vendor)) {
                $query->where('bills.vendor_id', '=', $request->vendor);
            }

            if (!empty($request->bill_date)) {
                $date_range = explode(',', $request->bill_date);
                if (count($date_range) == 2) {
                    $query->whereBetween('bill_date', $date_range);
                } else {
                    $query->where('bill_date', $date_range[0]);
                }
            }

            if (!empty($request->status)) {
                $query->where('status', '=', $request->status);
            }
            $query = $query->join('vendors', 'bills.vendor_id', '=', 'vendors.id');
            $bills = $query->get();

            return view('account::bill.index', compact('bills', 'vendor', 'status'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($vendorId)
    {
        if (module_is_active('ProductService')) {
            $projects = [];
            $taxs = [];
            $category = [];
            if (Auth::user()->isAbleTo('bill create')) {
                $category = \Modules\ProductService\Entities\Category::where('created_by', '=', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 2)->get()->pluck('name', 'id');

                $bill_number = Bill::billNumberFormat($this->billNumber());

                $vendors = Vender::where('workspace', '=', getActiveWorkSpace())->get()->pluck('name', 'id');

                $product_services = \Modules\ProductService\Entities\ProductService::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
                $product_services->prepend('--', '');

                if (module_is_active('CustomField')) {
                    $customFields = \Modules\CustomField\Entities\CustomField::where('workspace_id', getActiveWorkSpace())->where('module', '=', 'Account')->where('sub_module', 'Bill')->get();
                } else {
                    $customFields = null;
                }
                $product_type = \Modules\ProductService\Entities\ProductService::$product_type;
                if (module_is_active('Taskly')) {
                    if (module_is_active('ProductService')) {
                        $taxs = \Modules\ProductService\Entities\Tax::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');

                    }
                    $projects = \Modules\Taskly\Entities\Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', Auth::user()->id)->where('workspace', getActiveWorkSpace())->projectonly()->get()->pluck('name', 'id');
                }

                return view('account::bill.create', compact('vendors', 'bill_number', 'product_services', 'category', 'vendorId', 'customFields', 'product_type', 'projects', 'taxs'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Please Enable Product & Service Module'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('bill create')) {
            if ($request->bill_type == "product") {

                $validator = \Validator::make(
                    $request->all(),
                    [
                        'vendor_id' => 'required',
                        'bill_date' => 'required',
                        'due_date' => 'required',
                        'category_id' => 'required',

                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
                $vendor = Vender::find($request->vendor_id);
                $bill = new Bill();
                $bill->bill_id = $this->billNumber();
                $bill->vendor_id = $request->vendor_id;
                $bill->user_id = !empty($vendor) ? $vendor->user_id : null;
                $bill->account_type = $request->account_type;
                $bill->bill_date = $request->bill_date;
                $bill->status = 0;
                $bill->bill_module = 'account';
                $bill->due_date = $request->due_date;
                $bill->category_id = $request->category_id;
                $bill->order_number = !empty($request->order_number) ? $request->order_number : 0;
                $bill->created_by = creatorId();
                $bill->workspace = getActiveWorkSpace();

                $bill->save();

                Invoice::starting_number($bill->bill_id + 1, 'bill');

                $products = $request->items;

                for ($i = 0; $i < count($products); $i++) {
                    if (!empty($products[$i]['item'])) {
                        $billProduct = new BillProduct();
                        $billProduct->bill_id = $bill->id;
                        $billProduct->product_type = $products[$i]['product_type'];
                        $billProduct->product_id = $products[$i]['item'];
                        $billProduct->quantity = $products[$i]['quantity'];
                        $billProduct->tax = $products[$i]['tax'];
                        $billProduct->discount = $products[$i]['discount'];
                        $billProduct->price = $products[$i]['price'];
                        $billProduct->description = str_replace("'", "", $products[$i]['description']);
                        $billProduct->save();
                    }

                    if (!empty($products[$i]['chart_account_id'])) {
                        $billAccount = new BillAccount();
                        $billAccount->chart_account_id = $products[$i]['chart_account_id'];
                        $billAccount->price = $products[$i]['price'];
                        $billAccount->description = $products[$i]['description'];
                        $billAccount->type = 'Bill';
                        $billAccount->ref_id = $bill->id;
                        $billAccount->created_by = creatorId();
                        $billAccount->workspace = getActiveWorkSpace();
                        $billAccount->save();
                    }

                    if (!empty($billProduct)) {
                        Invoice::total_quantity('plus', $billProduct->quantity, $billProduct->product_id);
                    }

                    //Product Stock Report
                    if (!empty($products[$i]['item'])) {
                        $type = 'bill';
                        $type_id = $bill->id;
                        $description = $products[$i]['quantity'] . '  ' . __(' quantity purchase in bill') . ' ' . Bill::billNumberFormat($bill->bill_id);
                        Bill::addProductStock($products[$i]['item'], $products[$i]['quantity'], $type, $description, $type_id);
                    }
                }

                if (module_is_active('CustomField')) {
                    \Modules\CustomField\Entities\CustomField::saveData($bill, $request->customField);
                }

                event(new CreateBill($request, $bill));
                return redirect()->route('bill.index', $bill->id)->with('success', __('Bill successfully created.'));

            } else if ($request->bill_type == "project") {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'vendor_id' => 'required',
                        'bill_date' => 'required',
                        'due_date' => 'required',
                        'project' => 'required',
                        'tax_project' => 'required',
                        'items' => 'required',

                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $vendor = Vender::find($request->vendor_id);
                $bill = new Bill();
                $bill->bill_id = $this->billNumber();
                $bill->vendor_id = $request->vendor_id;
                $bill->user_id = !empty($vendor) ? $vendor->user_id : null;
                $bill->account_type = $request->account_type;
                $bill->bill_date = $request->bill_date;
                $bill->status = 0;
                $bill->bill_module = 'taskly';
                $bill->due_date = $request->due_date;
                $bill->category_id = $request->project;
                $bill->order_number = !empty($request->order_number) ? $request->order_number : 0;
                $bill->created_by = creatorId();
                $bill->workspace = getActiveWorkSpace();
                $bill->save();

                $products = $request->items;

                Invoice::starting_number($bill->bill_id + 1, 'bill');

                if (module_is_active('CustomField')) {
                    \Modules\CustomField\Entities\CustomField::saveData($bill, $request->customField);
                }
                $project_tax = implode(',', $request->tax_project);

                for ($i = 0; $i < count($products); $i++) {
                    $billProduct = new BillProduct();
                    $billProduct->bill_id = $bill->id;
                    $billProduct->product_id = $products[$i]['item'];
                    $billProduct->quantity = 1;
                    $billProduct->tax = $project_tax;
                    $billProduct->discount = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
                    $billProduct->price = $products[$i]['price'];
                    $billProduct->description = str_replace("'", "", $products[$i]['description']);
                    $billProduct->save();
                }

                // first parameter request second parameter invoice
                event(new CreateBill($request, $bill));
                return redirect()->route('bill.index', $bill->id)->with('success', __('Bill successfully created.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($e_id)
    {
        if (Auth::user()->isAbleTo('bill show')) {
            try {
                $id = Crypt::decrypt($e_id);
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', __('Bill Not Found.'));
            }

            $bill = Bill::find($id);

            if ($bill->workspace == getActiveWorkSpace()) {
                $bill_attachment = BillAttechment::where('bill_id', $bill->id)->get();
                $billPayment = BillPayment::where('bill_id', $bill->id)->first();
                $vendor = Vender::where('id', $bill->vendor_id)->first();

                $item = $bill->items;
                $accounts = $bill->accounts;
                $iteams = [];
                if (!empty($item) && count($item) > 0) {
                    foreach ($item as $k => $val) {
                        if (!empty($accounts[$k])) {
                            $val['chart_account_id'] = $accounts[$k]['chart_account_id'];
                            $val['account_id'] = $accounts[$k]['id'];
                            $val['amount'] = $accounts[$k]['price'];
                        }
                        $iteams[] = $val;
                    }
                } else {

                    foreach ($accounts as $k => $val) {
                        $val1['chart_account_id'] = $accounts[$k]['chart_account_id'];
                        $val1['account_id'] = $accounts[$k]['id'];
                        $val1['amount'] = $accounts[$k]['price'];
                        $iteams[] = $val1;
                    }
                }

                if (module_is_active('CustomField')) {
                    $bill->customField = \Modules\CustomField\Entities\CustomField::getData($bill, 'Account', 'Bill');
                    $customFields = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'Account')->where('sub_module', 'Bill')->get();
                } else {
                    $customFields = null;
                }

                return view('account::bill.view', compact('bill', 'vendor', 'iteams', 'billPayment', 'customFields', 'bill_attachment'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($e_id)
    {
        if (module_is_active('ProductService')) {
            if (Auth::user()->isAbleTo('bill edit')) {
                try {
                    $id = Crypt::decrypt($e_id);
                } catch (\Throwable $th) {
                    return redirect()->back()->with('error', __('Bill Not Found.'));
                }

                $bill = Bill::where('workspace',getActiveWorkSpace())->where('id',$id)->first();
                if(!$bill){
                    return redirect()->back()->with('error',__('Bill Not Found!'));
                }
                $category = [];

                $category = \Modules\ProductService\Entities\Category::where('created_by', '=', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 2)->get()->pluck('name', 'id');

                $bill_number = Bill::billNumberFormat($bill->bill_id);

                $vendors = Vender::where('workspace', '=', getActiveWorkSpace())->get()->pluck('name', 'id');

                $product_services = \Modules\ProductService\Entities\ProductService::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');

                if (module_is_active('CustomField')) {
                    $bill->customField = \Modules\CustomField\Entities\CustomField::getData($bill, 'Account', 'Bill');
                    $customFields = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'Account')->where('sub_module', 'Bill')->get();
                } else {
                    $customFields = null;
                }
                $projects = [];
                $taxs = [];
                if (module_is_active('Taskly')) {
                    if (module_is_active('ProductService')) {
                        $taxs = \Modules\ProductService\Entities\Tax::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
                    }
                    $projects = \Modules\Taskly\Entities\Project::where('workspace', getActiveWorkSpace())->projectonly()->get()->pluck('name', 'id');
                }
                $product_type = \Modules\ProductService\Entities\ProductService::$product_type;




                return view('account::bill.edit', compact('vendors', 'product_services', 'bill', 'bill_number', 'category', 'customFields', 'product_type', 'projects', 'taxs'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Please Enable Product & Service Module'));
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Bill $bill)
    {

        if (Auth::user()->isAbleTo('bill edit')) {
            if ($bill->workspace == getActiveWorkSpace()) {

                if ($request->bill_type == "product") {

                    $validator = \Validator::make(
                        $request->all(),
                        [
                            'vendor_id' => 'required',
                            'bill_date' => 'required',
                            'due_date' => 'required',
                            'category_id' => 'required',
                            'items'        => 'required'
                        ]
                    );
                    if ($validator->fails()) {
                        $messages = $validator->getMessageBag();

                        return redirect()->back()->with('error', $messages->first());
                    }
                    if ($request->bill_type == "product") {
                        $request->bill_type = 'account';
                    } else if ($request->bill_type == "project") {
                        $request->bill_type = 'taskly';
                    }
                    if ($request->bill_type != $bill->bill_module) {
                        BillProduct::where('bill_id', '=', $bill->id)->delete();
                    }

                    $vendor = Vender::find($request->vendor_id);

                    $bill->vendor_id = $request->vendor_id;
                    $bill->user_id = !empty($vendor) ? $vendor->user_id : null;
                    $bill->bill_date = $request->bill_date;
                    $bill->account_type = $request->account_type;
                    $bill->due_date = $request->due_date;
                    $bill->bill_module = 'account';
                    $bill->order_number = $request->order_number;
                    $bill->category_id = $request->category_id;
                    $bill->save();

                    if (module_is_active('CustomField')) {
                        \Modules\CustomField\Entities\CustomField::saveData($bill, $request->customField);
                    }

                    $products = $request->items;
                    for ($i = 0; $i < count($products); $i++) {
                        $billProduct = BillProduct::find($products[$i]['id']);
                        if ($billProduct == null) {
                            $billProduct = new BillProduct();
                            $billProduct->bill_id = $bill->id;
                            Invoice::total_quantity('plus', $products[$i]['quantity'], $products[$i]['item']);
                            $updatePrice = ($products[$i]['price'] * $products[$i]['quantity']) + ($products[$i]['itemTaxPrice']) - ($products[$i]['discount']);
                            AccountUtility::updateUserBalance('vendor', $request->vendor_id, $updatePrice, 'debit');
                        } else {
                            Invoice::total_quantity('minus', $billProduct->quantity, $billProduct->product_id);
                        }

                        if (isset($products[$i]['item'])) {
                            $billProduct->product_id = $products[$i]['item'];
                        }
                        $billProduct->product_type = $products[$i]['product_type'];
                        $billProduct->quantity = $products[$i]['quantity'];
                        $billProduct->tax = $products[$i]['tax'];
                        $billProduct->discount = $products[$i]['discount'];
                        $billProduct->price = $products[$i]['price'];
                        $billProduct->description = str_replace("'", "", $products[$i]['description']);
                        $billProduct->save();

                        if (!empty($products[$i]['chart_account_id'])) {
                            $billAccount = BillAccount::find($products[$i]['id']);

                            if ($billAccount == null) {
                                $billAccount = new BillAccount();
                                $billAccount->chart_account_id = $products[$i]['chart_account_id'];
                            } else {
                                $billAccount->chart_account_id = $products[$i]['chart_account_id'];
                            }

                            $billAccount->price = $products[$i]['price'];
                            $billAccount->description = $products[$i]['description'];
                            $billAccount->type = 'Bill';
                            $billAccount->ref_id = $bill->id;
                            $billAccount->created_by = creatorId();
                            $billAccount->workspace = getActiveWorkSpace();
                            $billAccount->save();
                        }

                        if ($products[$i]['id'] > 0) {
                            Invoice::total_quantity('plus', $products[$i]['quantity'], $billProduct->product_id);
                        }
                        //Product Stock Report.
                        $type = 'bill';
                        $type_id = $bill->id;
                        StockReport::where('type', '=', 'bill')->where('type_id', '=', $bill->id)->delete();
                        $description = $products[$i]['quantity'] . '  ' . __(' quantity purchase in bill') . ' ' . Bill::billNumberFormat($bill->bill_id);

                        if (empty($products[$i]['id'])) {
                            Bill::addProductStock($products[$i]['item'], $products[$i]['quantity'], $type, $description, $type_id);
                        }
                    }

                } else if ($request->bill_type == "project") {

                    $validator = \Validator::make(
                        $request->all(),
                        [
                            'vendor_id' => 'required',
                            'bill_date' => 'required',
                            'due_date' => 'required',
                            'project' => 'required',
                            'tax_project' => 'required',
                            'items' => 'required',

                        ]
                    );
                    if ($validator->fails()) {
                        $messages = $validator->getMessageBag();

                        return redirect()->back()->with('error', $messages->first());
                    }

                    $vendor = Vender::find($request->vendor_id);
                    if ($request->bill_type != $bill->bill_module) {
                        BillProduct::where('bill_id', '=', $bill->id)->delete();
                    }
                    $bill->vendor_id = $request->vendor_id;
                    $bill->user_id = !empty($vendor) ? $vendor->user_id : null;
                    $bill->bill_date = $request->bill_date;
                    $bill->account_type = $request->account_type;
                    $bill->due_date = $request->due_date;
                    $bill->bill_module = 'taskly';
                    $bill->order_number = $request->order_number;
                    $bill->category_id = $request->category_id;
                    $bill->save();

                    $products = $request->items;
                    if (module_is_active('CustomField')) {
                        \Modules\CustomField\Entities\CustomField::saveData($bill, $request->customField);
                    }
                    $project_tax = implode(',', $request->tax_project);
                    for ($i = 0; $i < count($products); $i++) {
                        $billProduct = BillProduct::find($products[$i]['id']);
                        if ($billProduct == null) {
                            $billProduct = new BillProduct();
                            $billProduct->bill_id = $bill->id;
                        }
                        $billProduct->product_id = $products[$i]['item'];
                        $billProduct->quantity = 1;
                        $billProduct->tax = $project_tax;
                        $billProduct->discount = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
                        $billProduct->price = $products[$i]['price'];
                        $billProduct->description = str_replace("'", "", $products[$i]['description']);
                        $billProduct->save();
                    }
                }

                event(new UpdateBill($bill, $request));

                return redirect()->route('bill.index')->with('success', __('Bill successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Bill $bill)
    {
        if (Auth::user()->isAbleTo('bill delete')) {
            if ($bill->workspace == getActiveWorkSpace()) {
                if ($bill->vendor_id != 0 && $bill->status != 0) {
                    AccountUtility::updateUserBalance('vendor', $bill->vendor_id, $bill->getTotal(), 'credit');
                }
                BillProduct::where('bill_id', '=', $bill->id)->delete();
                BillAccount::where('ref_id', '=', $bill->id)->delete();
                $bill_payments = BillPayment::where('bill_id', $bill->id)->get();
                if (!empty($bill_payments)) {
                    foreach ($bill_payments as $bill_payment) {
                        delete_file($bill_payment->add_receipt);
                        $bill_payment->delete();
                    }
                }
                if (module_is_active('CustomField')) {
                    $customFields = \Modules\CustomField\Entities\CustomField::where('module', 'Account')->where('sub_module', 'Bill')->get();
                    foreach ($customFields as $customField) {
                        $value = \Modules\CustomField\Entities\CustomFieldValue::where('record_id', '=', $bill->id)->where('field_id', $customField->id)->first();
                        if (!empty($value)) {

                            $value->delete();
                        }
                    }
                }

                event(new DestroyBill($bill));

                $bill->delete();

                return redirect()->route('bill.index')->with('success', __('Bill successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    function billNumber()
    {
        $latest = company_setting('bill_starting_number');
        if ($latest == null) {
            return 1;
        } else {
            return $latest;
        }
    }
    public function vendor(Request $request)
    {
        $vendor = Vender::where('id', '=', $request->id)->first();

        return view('account::bill.vender_detail', compact('vendor'));
    }
    public function product(Request $request)
    {
        $data['product'] = $product = \Modules\ProductService\Entities\ProductService::find($request->product_id);
        $data['unit'] = !empty($product) ? ((!empty($product->unit())) ? $product->unit()->name : '') : '';
        $data['taxRate'] = $taxRate = !empty($product) ? (!empty($product->tax_id) ? $product->taxRate($product->tax_id) : 0) : 0;
        $data['taxes'] = !empty($product) ? (!empty($product->tax_id) ? $product->tax($product->tax_id) : 0) : 0;
        $salePrice = !empty($product) ? $product->purchase_price : 0;
        $quantity = 1;
        $taxPrice = !empty($product) ? (($taxRate / 100) * ($salePrice * $quantity)) : 0;
        $data['totalAmount'] = !empty($product) ? ($salePrice * $quantity) : 0;

        return json_encode($data);
    }
    public function duplicate($bill_id)
    {
        if (Auth::user()->isAbleTo('bill duplicate')) {
            $bill = Bill::where('id', $bill_id)->first();

            $duplicateBill = new Bill();
            $duplicateBill->bill_id = $this->billNumber();
            $duplicateBill->vendor_id = $bill['vendor_id'];
            $duplicateBill->user_id = $bill['user_id'];
            $duplicateBill->bill_date = date('Y-m-d');
            $duplicateBill->due_date = $bill['due_date'];
            $duplicateBill->send_date = null;
            $duplicateBill->category_id = $bill['category_id'];
            $duplicateBill->order_number = $bill['order_number'];
            $duplicateBill->status = 0;
            $duplicateBill->bill_shipping_display = $bill['bill_shipping_display'];
            $duplicateBill->bill_module = $bill['bill_module'];
            $duplicateBill->created_by = $bill['created_by'];
            $duplicateBill->workspace = $bill['workspace'];
            $duplicateBill->save();

            Invoice::starting_number($duplicateBill->bill_id + 1, 'bill');

            if ($duplicateBill) {
                $billProduct = BillProduct::where('bill_id', $bill_id)->get();
                foreach ($billProduct as $product) {
                    $duplicateProduct = new BillProduct();
                    $duplicateProduct->bill_id = $duplicateBill->id;
                    $duplicateProduct->product_type = $product->product_type;
                    $duplicateProduct->product_id = $product->product_id;
                    $duplicateProduct->quantity = $product->quantity;
                    $duplicateProduct->tax = $product->tax;
                    $duplicateProduct->discount = $product->discount;
                    $duplicateProduct->price = $product->price;
                    $duplicateProduct->save();

                    Invoice::total_quantity('plus', $duplicateProduct->quantity, $duplicateProduct->product_id);

                    //Product Stock Report
                    $type = 'bill';
                    $type_id = $bill->id;
                    $description = $duplicateProduct->quantity . '  ' . __(' quantity purchase in bill') . ' ' . Bill::billNumberFormat($bill->bill_id);
                    Bill::addProductStock($duplicateProduct->product_id, $duplicateProduct->quantity, $type, $description, $type_id);
                }
            }
            event(new DuplicateBill($duplicateProduct, $duplicateBill));

            return redirect()->back()->with('success', __('Bill duplicate successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function items(Request $request)
    {
        $items = BillProduct::where('bill_id', $request->bill_id)->where('product_id', $request->product_id)->first();

        return json_encode($items);
    }
    public function sent($id)
    {
        if (Auth::user()->isAbleTo('bill send')) {
            $bill = Bill::where('id', $id)->first();
            $bill->send_date = date('Y-m-d');
            $bill->status = 1;
            $bill->save();

            $vendor = Vender::where('id', $bill->vendor_id)->first();

            $bill->name = !empty($vendor) ? $vendor->name : '';
            $bill->bill = Bill::billNumberFormat($bill->bill_id);

            $billId = Crypt::encrypt($bill->id);
            $bill->url = route('bill.pdf', $billId);

            AccountUtility::updateUserBalance('vendor', $vendor->id, $bill->getTotal(), 'debit');

            event(new SentBill($bill));
            if (!empty(company_setting('Bill Send')) && company_setting('Bill Send') == true) {
                $uArr = [
                    'bill_name' => $bill->name,
                    'bill_number' => $bill->bill,
                    'bill_url' => $bill->url,
                ];
                try {
                    $resp = EmailTemplate::sendEmailTemplate('Bill Send', [$vendor->id => $vendor->email], $uArr);
                } catch (\Exception $e) {
                    $resp['error'] = $e->getMessage();
                }
                return redirect()->back()->with('success', __('Bill successfully sent.') . ((isset($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }
            return redirect()->back()->with('success', __('Bill sent email notification is off.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function resent($id)
    {
        if (Auth::user()->isAbleTo('bill send')) {
            $bill = Bill::where('id', $id)->first();

            $vendor = Vender::where('id', $bill->vendor_id)->first();

            $bill->name = !empty($vendor) ? $vendor->name : '';
            $bill->bill = Bill::billNumberFormat($bill->bill_id);

            $billId = Crypt::encrypt($bill->id);
            $bill->url = route('bill.pdf', $billId);

            event(new ResentBill($bill));
            if (!empty(company_setting('Bill Send')) && company_setting('Bill Send') == true) {
                $uArr = [
                    'bill_name' => $bill->name,
                    'bill_number' => $bill->bill,
                    'bill_url' => $bill->url,
                ];
                try {
                    $resp = EmailTemplate::sendEmailTemplate('Bill Send', [$vendor->id => $vendor->email], $uArr);
                } catch (\Exception $e) {
                    $resp['error'] = $e->getMessage();
                }
                return redirect()->back()->with('success', __('Bill successfully sent.') . ((isset($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }
            return redirect()->back()->with('success', __('Bill sent email notification is off.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function payment($bill_id)
    {
        if (Auth::user()->isAbleTo('bill payment create')) {
            $bill = Bill::where('id', $bill_id)->first();
            $vendors = Vender::where('workspace', '=', getActiveWorkSpace())->get()->pluck('name', 'id');
            $categories = \Modules\ProductService\Entities\Category::where('created_by', '=', creatorId())->where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
            $accounts = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');

            return view('account::bill.payment', compact('vendors', 'categories', 'accounts', 'bill'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function createPayment(Request $request, $bill_id)
    {
        if (Auth::user()->isAbleTo('bill payment create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'date' => 'required',
                    'amount' => 'required',
                    'account_id' => 'required',
                    'reference' => 'required',
                    'description' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $billPayment = new BillPayment();
            $billPayment->bill_id = $bill_id;
            $billPayment->date = $request->date;
            $billPayment->amount = $request->amount;
            $billPayment->account_id = $request->account_id;
            $billPayment->payment_method = 0;
            $billPayment->reference = $request->reference;
            $billPayment->description = $request->description;
            if (!empty($request->add_receipt)) {
                $fileName = time() . "_" . $request->add_receipt->getClientOriginalName();
                $uplaod = upload_file($request, 'add_receipt', $fileName, 'payment');
                if ($uplaod['flag'] == 1) {
                    $url = $uplaod['url'];
                } else {
                    return redirect()->back()->with('error', $uplaod['msg']);
                }
                $billPayment->add_receipt = $url;
            }
            $billPayment->save();

            $bill = Bill::where('id', $bill_id)->first();
            $due = $bill->getDue();
            $total = $bill->getTotal();

            if ($bill->status == 0) {
                $bill->send_date = date('Y-m-d');
                $bill->save();
            }

            if ($due <= 0) {
                $bill->status = 4;
                $bill->save();
            } else {
                $bill->status = 3;
                $bill->save();
            }
            $billPayment->user_id = $bill->vendor_id;
            $billPayment->user_type = 'Vendor';
            $billPayment->type = 'Partial';
            $billPayment->created_by = \Auth::user()->id;
            $billPayment->payment_id = $billPayment->id;
            $billPayment->category = 'Bill';
            $billPayment->account = $request->account_id;
            Transaction::addTransaction($billPayment);

            $vendor = Vender::where('id', $bill->vendor_id)->first();

            $payment = new BillPayment();
            $payment->name = $vendor['name'];
            $payment->method = '-';
            $payment->date = company_date_formate($request->date);
            $payment->amount = currency_format_with_sym($request->amount);
            $payment->bill = 'bill ' . Bill::billNumberFormat($billPayment->bill_id);

            AccountUtility::updateUserBalance('vendor', $bill->vendor_id, $request->amount, 'credit');

            Transfer::bankAccountBalance($request->account_id, $request->amount, 'debit');

            //merge id for transactionlines
            $request->merge(['id' => $billPayment->id]);

            event(new CreatePaymentBill($request, $bill));
            if (!empty(company_setting('Bill Payment Create')) && company_setting('Bill Payment Create') == true) {
                $uArr = [
                    'payment_name' => $payment->name,
                    'payment_bill' => $payment->bill,
                    'payment_amount' => $payment->amount,
                    'payment_date' => $payment->date,
                    'payment_method' => $payment->method

                ];
                try {
                    $resp = EmailTemplate::sendEmailTemplate('Bill Payment Create', [$vendor->id => $vendor->email], $uArr);
                } catch (\Exception $e) {
                    $resp['error'] = $e->getMessage();
                }
            }
            return redirect()->back()->with('success', __('Payment successfully added.') . ((isset($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
        }
    }

    public function paymentDestroy(Request $request, $bill_id, $payment_id)
    {
        if (\Auth::user()->isAbleTo('bill payment delete')) {
            $payment = BillPayment::find($payment_id);
            if (!empty($payment->add_receipt)) {
                try {
                    delete_file($payment->add_receipt);
                } catch (Exception $e) {

                }
            }
            $bill = Bill::where('id', $bill_id)->first();

            $due = $bill->getDue();
            $total = $bill->getTotal();

            if ($due > 0 && $total != $due) {
                $bill->status = 3;
            } else {
                $bill->status = 2;
            }

            AccountUtility::updateUserBalance('vendor', $bill->vendor_id, $payment->amount, 'debit');
            Transfer::bankAccountBalance($payment->account_id, $payment->amount, 'credit');

            $bill->save();
            $type = 'Partial';
            $user = 'Vendor';
            Transaction::destroyTransaction($payment_id, $type, $user);
            event(new PaymentDestroyBill($bill, $payment));
            $payment->delete();
            return redirect()->back()->with('success', __('Payment successfully deleted.'));

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function bill($bill_id)
    {
        $billId = Crypt::decrypt($bill_id);

        $bill = Bill::where('id', $billId)->first();

        $vendor = $bill->vendor;

        $totalTaxPrice = 0;
        $totalQuantity = 0;
        $totalRate = 0;
        $totalDiscount = 0;
        $taxesData = [];
        $items = [];

        foreach ($bill->items as $product) {

            $item = new \stdClass();
            if ($bill->bill_module == "taskly") {
                $item->name = !empty($product->product()) ? $product->product()->title : '';
            } elseif ($bill->bill_module == "account") {
                $item->name = !empty($product->product()) ? $product->product()->name : '';
                $item->product_type = !empty($product->product_type) ? $product->product_type : '';
            }
            $item->quantity = $product->quantity;
            $item->tax = $product->tax;
            $item->discount = $product->discount;
            $item->price = $product->price;
            $item->description = $product->description;

            $totalQuantity += $item->quantity;
            $totalRate += $item->price;
            $totalDiscount += $item->discount;


            $taxes = AccountUtility::tax($product->tax);

            $itemTaxes = [];
            if (!empty($item->tax)) {
                foreach ($taxes as $tax) {
                    $taxPrice = AccountUtility::taxRate($tax->rate, $item->price, $item->quantity, $item->discount);
                    $totalTaxPrice += $taxPrice;

                    $itemTax['name'] = $tax->name;
                    $itemTax['rate'] = $tax->rate . '%';
                    $itemTax['price'] = currency_format_with_sym($taxPrice, $bill->created_by, $bill->workspace);
                    $itemTax['tax_price'] = $taxPrice;
                    $itemTaxes[] = $itemTax;


                    if (array_key_exists($tax->name, $taxesData)) {
                        $taxesData[$tax->name] = $taxesData[$tax->name] + $taxPrice;
                    } else {
                        $taxesData[$tax->name] = $taxPrice;
                    }
                }
                $item->itemTax = $itemTaxes;
            } else {
                $item->itemTax = [];
            }
            $items[] = $item;
        }
        
        $bill->itemData = $items;
        $bill->totalTaxPrice = $totalTaxPrice;
        $bill->totalQuantity = $totalQuantity;
        $bill->totalRate = $totalRate;
        $bill->totalDiscount = $totalDiscount;
        $bill->taxesData = $taxesData;

        if (module_is_active('CustomField')) {
            $bill->customField = \Modules\CustomField\Entities\CustomField::getData($bill, 'Account', 'Bill');
            $customFields = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace($bill->created_by))->where('module', '=', 'Account')->where('sub_module', 'Bill')->get();
        } else {
            $customFields = null;
        }

        if ($bill) {
            $company_settings = getCompanyAllSetting($bill->created_by, $bill->workspace);

            $color      = '#'.(!empty($company_settings['bill_color']) ? $company_settings['bill_color'] : 'ffffff');
            $font_color = AccountUtility::getFontColor($color);

            $company_logo = get_file(sidebar_logo());

            $bill_logo = isset($company_settings['bill_logo']) ? $company_settings['bill_logo'] : '';

            if (isset($bill_logo) && !empty($bill_logo)) {
                $img = get_file($bill_logo);
            } else {
                $img = $company_logo;
            }
            $settings['company_name'] = isset($company_settings['company_name']) ? $company_settings['company_name'] : '';
            $settings['site_rtl'] = isset($company_settings['site_rtl']) ? $company_settings['site_rtl'] : '';
            $settings['company_email'] = isset($company_settings['company_email']) ? $company_settings['company_email'] : '';
            $settings['company_telephone'] = isset($company_settings['company_telephone']) ? $company_settings['company_telephone'] : '';
            $settings['company_address'] = isset($company_settings['company_address']) ? $company_settings['company_address'] : '';
            $settings['company_city'] = isset($company_settings['company_city']) ? $company_settings['company_city'] : '';
            $settings['company_state'] = isset($company_settings['company_state']) ? $company_settings['company_state'] : '';
            $settings['company_zipcode'] = isset($company_settings['company_zipcode']) ? $company_settings['company_zipcode'] : '';
            $settings['company_country'] = isset($company_settings['company_country']) ? $company_settings['company_country'] : '';
            $settings['registration_number'] = isset($company_settings['registration_number']) ? $company_settings['registration_number'] : '';
            $settings['tax_type'] = isset($company_settings['tax_type']) ? $company_settings['tax_type'] : '';
            $settings['vat_number'] = isset($company_settings['vat_number']) ? $company_settings['vat_number'] : '';
            $settings['bill_footer_title'] = isset($company_settings['bill_footer_title']) ? $company_settings['bill_footer_title'] : '';
            $settings['bill_footer_notes'] = isset($company_settings['bill_footer_notes']) ? $company_settings['bill_footer_notes'] : '';
            $settings['bill_shipping_display'] = isset($company_settings['bill_shipping_display']) ? $company_settings['bill_shipping_display'] : '';
            $settings['bill_template'] = isset($company_settings['bill_template']) ? $company_settings['bill_template'] : '';
            $settings['bill_qr_display'] = isset($company_settings['bill_qr_display']) ? $company_settings['bill_qr_display'] : '';

            return view('account::bill.templates.' . $settings['bill_template'], compact('bill', 'color', 'settings', 'vendor', 'img', 'font_color', 'customFields'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function paybill($bill_id)
    {
        if (!empty($bill_id)) {

            try {
                $id = \Illuminate\Support\Facades\Crypt::decrypt($bill_id);
            } catch (\Throwable $th) {
                return redirect('login');
            }
            $bill = bill::where('id', $id)->first();

            if (!is_null($bill)) {
                $items = [];
                $totalTaxPrice = 0;
                $totalQuantity = 0;
                $totalRate = 0;
                $totalDiscount = 0;
                $taxesData = [];

                foreach ($bill->items as $item) {
                    $totalQuantity += $item->quantity;
                    $totalRate += $item->price;
                    $totalDiscount += $item->discount;
                    $taxes = AccountUtility::tax($item->tax);
                    $itemTaxes = [];
                    foreach ($taxes as $tax) {
                        if (!empty($tax)) {
                            $taxPrice = AccountUtility::taxRate($tax->rate, $item->price, $item->quantity, $item->discount);
                            $totalTaxPrice += $taxPrice;
                            $itemTax['tax_name'] = $tax->tax_name;
                            $itemTax['tax'] = $tax->rate . '%';
                            $itemTax['price'] = currency_format_with_sym($taxPrice, $bill->created_by);
                            $itemTax['tax_price'] = $taxPrice;
                            $itemTaxes[] = $itemTax;

                            if (array_key_exists($tax->name, $taxesData)) {
                                $taxesData[$itemTax['tax_name']] = $taxesData[$tax->tax_name] + $taxPrice;
                            } else {
                                $taxesData[$tax->tax_name] = $taxPrice;
                            }
                        } else {
                            $taxPrice = AccountUtility::taxRate(0, $item->price, $item->quantity, $item->discount);
                            $totalTaxPrice += $taxPrice;
                            $itemTax['tax_name'] = 'No Tax';
                            $itemTax['tax'] = '';
                            $itemTax['price'] = currency_format_with_sym($taxPrice, $bill->created_by);
                            $itemTax['tax_price'] = $taxPrice;
                            $itemTaxes[] = $itemTax;

                            if (array_key_exists('No Tax', $taxesData)) {
                                $taxesData[$tax->tax_name] = $taxesData['No Tax'] + $taxPrice;
                            } else {
                                $taxesData['No Tax'] = $taxPrice;
                            }
                        }
                    }
                    $item->itemTax = $itemTaxes;
                    $items[] = $item;
                }


                $bill->items = $items;
                $bill->totalTaxPrice = $totalTaxPrice;
                $bill->totalQuantity = $totalQuantity;
                $bill->totalRate = $totalRate;
                $bill->totalDiscount = $totalDiscount;
                $bill->taxesData = $taxesData;
                $ownerId = $bill->created_by;

                $users = User::where('id', $bill->created_by)->first();

                if (!is_null($users)) {
                    \App::setLocale($users->lang);
                } else {
                    $users = User::where('type', 'super admin')->first();
                    \App::setLocale($users->lang);
                }

                $bill = bill::where('id', $id)->first();
                $customer = $bill->customer;
                $iteams = $bill->items;
                $company_id = $bill->created_by;
                $workspace_id = $bill->workspace;
                if (module_is_active('CustomField')) {
                    $bill->customField = \Modules\CustomField\Entities\CustomField::getData($bill, 'Account', 'Bill');
                    $customFields = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace($bill->created_by))->where('module', '=', 'Account')->where('sub_module', 'Bill')->get();
                } else {
                    $customFields = null;
                }
                return view('account::bill.billpay', compact('bill', 'iteams', 'users', 'company_id', 'customFields', 'workspace_id'));
            } else {
                return abort('404', 'The Link You Followed Has Expired');
            }
        } else {
            return abort('404', 'The Link You Followed Has Expired');
        }
    }
    public function previewBill($template, $color)
    {
        $objUser = \Auth::user();
        $bill = new Bill();

        $vendor = new \stdClass();
        $vendor->email = '<Email>';
        $vendor->shipping_name = '<Vendor Name>';
        $vendor->shipping_country = '<Country>';
        $vendor->shipping_state = '<State>';
        $vendor->shipping_city = '<City>';
        $vendor->shipping_phone = '<Vendor Phone Number>';
        $vendor->shipping_zip = '<Zip>';
        $vendor->shipping_address = '<Address>';
        $vendor->billing_name = '<Vendor Name>';
        $vendor->billing_country = '<Country>';
        $vendor->billing_state = '<State>';
        $vendor->billing_city = '<City>';
        $vendor->billing_phone = '<Vendor Phone Number>';
        $vendor->billing_zip = '<Zip>';
        $vendor->billing_address = '<Address>';
        $vendor->sku = 'Test123';

        $totalTaxPrice = 0;
        $taxesData = [];
        $items = [];
        for ($i = 1; $i <= 3; $i++) {
            $item = new \stdClass();
            $item->name = 'Item ' . $i;
            $item->quantity = 1;
            $item->tax = 5;
            $item->discount = 50;
            $item->price = 100;
            $item->price = 100;
            $item->description = 'In publishing and graphic design, Lorem ipsum is a placeholder';

            $taxes = [
                'Tax 1',
                'Tax 2',
            ];

            $itemTaxes = [];
            foreach ($taxes as $k => $tax) {
                $taxPrice = 10;
                $totalTaxPrice += $taxPrice;
                $itemTax['name'] = 'Tax ' . $k;
                $itemTax['rate'] = '10 %';
                $itemTax['price'] = '$10';
                $itemTaxes[] = $itemTax;
                if (array_key_exists('Tax ' . $k, $taxesData)) {
                    $taxesData['Tax ' . $k] = $taxesData['Tax 1'] + $taxPrice;
                } else {
                    $taxesData['Tax ' . $k] = $taxPrice;
                }
            }
            $item->itemTax = $itemTaxes;
            $items[] = $item;
        }

        $bill->bill_id = 1;
        $bill->issue_date = date('Y-m-d H:i:s');
        $bill->due_date = date('Y-m-d H:i:s');
        $bill->itemData = $items;

        $bill->totalTaxPrice = 60;
        $bill->totalQuantity = 3;
        $bill->totalRate = 300;
        $bill->totalDiscount = 10;
        $bill->taxesData = $taxesData;
        $bill->customField = [];
        $customFields = [];

        $preview = 1;
        $color = '#' . $color;

        $font_color = AccountUtility::getFontColor($color);

        $company_logo = get_file(sidebar_logo());
        $company_settings = getCompanyAllSetting();

        $bill_logo = isset($company_settings['bill_logo']) ? $company_settings['bill_logo'] : '';

        if (isset($bill_logo) && !empty($bill_logo)) {
            $img = get_file($bill_logo);
        } else {
            $img = $company_logo;
        }
        $company_id = $bill->created_by;
        $settings['company_name'] = isset($company_settings['company_name']) ? $company_settings['company_name'] : '';
        $settings['site_rtl'] = isset($company_settings['site_rtl']) ? $company_settings['site_rtl'] : '';
        $settings['company_email'] = isset($company_settings['company_email']) ? $company_settings['company_email'] : '';
        $settings['company_telephone'] = isset($company_settings['company_telephone']) ? $company_settings['company_telephone'] : '';
        $settings['company_address'] = isset($company_settings['company_address']) ? $company_settings['company_address'] : '';
        $settings['company_city'] = isset($company_settings['company_city']) ? $company_settings['company_city'] : '';
        $settings['company_state'] = isset($company_settings['company_state']) ? $company_settings['company_state'] : '';
        $settings['company_zipcode'] = isset($company_settings['company_zipcode']) ? $company_settings['company_zipcode'] : '';
        $settings['company_country'] = isset($company_settings['company_country']) ? $company_settings['company_country'] : '';
        $settings['registration_number'] = isset($company_settings['registration_number']) ? $company_settings['registration_number'] : '';
        $settings['tax_type'] = isset($company_settings['tax_type']) ? $company_settings['tax_type'] : '';
        $settings['vat_number'] = isset($company_settings['vat_number']) ? $company_settings['vat_number'] : '';
        $settings['bill_footer_title'] = isset($company_settings['bill_footer_title']) ? $company_settings['bill_footer_title'] : '';
        $settings['bill_footer_notes'] = isset($company_settings['bill_footer_notes']) ? $company_settings['bill_footer_notes'] : '';
        $settings['bill_shipping_display'] = isset($company_settings['bill_shipping_display']) ? $company_settings['bill_shipping_display'] : '';
        $settings['bill_qr_display'] = isset($company_settings['bill_qr_display']) ? $company_settings['bill_qr_display'] : '';

        return view('account::bill.templates.' . $template, compact('bill', 'preview', 'color', 'settings', 'img', 'vendor', 'font_color', 'customFields'));
    }

    public function saveBillTemplateSettings(Request $request)
    {
        $user = \Auth::user();
        $validator = \Validator::make(
            $request->all(),
            [
                'bill_template' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        if ($request->bill_logo) {
            $request->validate(
                [
                    'bill_logo' => 'image|mimes:png',
                ]
            );

            $bill_logo = $user->id . '_bill_logo' . time() . '.png';
            $uplaod = upload_file($request, 'bill_logo', $bill_logo, 'bill_logo');
            if ($uplaod['flag'] == 1) {
                $url = $uplaod['url'];
                $old_bill_logo = company_setting('bill_logo');
                if (!empty($old_bill_logo) && check_file($old_bill_logo)) {
                    delete_file($old_bill_logo);
                }
            } else {
                return redirect()->back()->with('error', $uplaod['msg']);
            }
        }
        $post = $request->all();
        unset($post['_token']);

        if (isset($post['bill_template']) && (!isset($post['bill_color']) || empty($post['bill_color']))) {
            $post['bill_color'] = "ffffff";
        }
        if (isset($post['bill_logo'])) {
            $post['bill_logo'] = $url;
        }
        if (!isset($post['bill_qr_display'])) {
            $post['bill_qr_display'] = 'off';
        }
        foreach ($post as $key => $value) {
            // Define the data to be updated or inserted
            $data = [
                'key' => $key,
                'workspace' => getActiveWorkSpace(),
                'created_by' => Auth::user()->id,
            ];
            // Check if the record exists, and update or insert accordingly
            Setting::updateOrInsert($data, ['value' => $value]);
        }
        // Settings Cache forget
        comapnySettingCacheForget();
        return redirect()->back()->with('success', 'Bill Print setting save sucessfully.');
    }

    public function productDestroy(Request $request)
    {
        if (Auth::user()->isAbleTo('bill payment delete')) {

            $billProduct = BillProduct::find($request->id);
            $bill = Bill::find($billProduct->bill_id);

            AccountUtility::updateUserBalance('vendor', $bill->vendor_id, $request->amount, 'credit');

            // first parameter request second parameter bill
            event(new \Modules\Account\Events\ProductDestroyBill($billProduct, $request));

            BillProduct::where('id', '=', $request->id)->delete();

            return response()->json(['success' => __('Bill product successfully deleted.')]);

        } else {
            return response()->json(['error' => __('Permission denied.')]);
        }
    }

    public function grid(Request $request)
    {
        if (\Auth::user()->isAbleTo('bill manage')) {
            $vendor = Vender::where('workspace', '=', getActiveWorkSpace())->get()->pluck('name', 'id');

            $status = Bill::$statues;

            $query = Bill::select('bills.*', 'vendors.name as vendor_name', 'users.avatar as avatar')->where('bills.workspace', '=', getActiveWorkSpace());
            if (!empty($request->vendor)) {
                $query->where('bills.vendor_id', '=', $request->vendor);
            }
            if (!empty($request->bill_date)) {
                $date_range = explode(',', $request->bill_date);
                if (count($date_range) == 2) {
                    $query->whereBetween('bill_date', $date_range);
                } else {
                    $query->where('bill_date', $date_range[0]);
                }
            }

            if (!empty($request->status)) {
                $query->where('status', '=', $request->status);
            }
            $query = $query->join('vendors', 'bills.vendor_id', '=', 'vendors.id')->join('users', 'bills.user_id', '=', 'users.id');
            $bills = $query->get();

            return view('account::bill.grid', compact('bills', 'vendor', 'status'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function venderBillSend($bill_id)
    {
        return view('account::vendor.bill_send', compact('bill_id'));
    }
    public function venderBillSendMail(Request $request, $bill_id)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $email = $request->email;
        $bill = Bill::where('id', $bill_id)->first();

        $vender = Vender::where('id', $bill->vendor_id)->first();
        $bill->name = !empty($vender) ? $vender->name : '';
        $bill->bill = Bill::billNumberFormat($bill->bill_id, $bill->created_by, $bill->workspace);
        $billId = Crypt::encrypt($bill->id);
        $bill->url = route('bill.pdf', $billId);

        if (!empty(company_setting('Bill Send', $bill->created_by, $bill->workspace)) && company_setting('Bill Send', $bill->created_by, $bill->workspace) == true) {
            $uArr = [
                'bill_name' => $bill->name,
                'bill_number' => $bill->bill,
                'bill_url' => $bill->url,
            ];
            try {
                $resp = EmailTemplate::sendEmailTemplate('Bill Send', [$email], $uArr, $bill->created_by, $bill->workspace);
            } catch (\Exception $e) {
                $resp['error'] = $e->getMessage();
            }
            return redirect()->back()->with('success', __('Bill successfully sent.') . ((isset($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
        }
        return redirect()->back()->with('success', __('Bill sent email notification is off.'));

    }


    public function BillSectionGet(Request $request)
    {
        $type = $request->type;
        $acction = $request->acction;
        $bill = [];
        if ($acction == 'edit') {
            $bill = Bill::find($request->bill_id);
        }

        if ($request->type == "product") {
            $product_services = \Modules\ProductService\Entities\ProductService::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
            $product_services_count = $product_services->count();

            $chartAccounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
                ->where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()
                ->pluck('code_name', 'id');
            $chartAccounts->prepend('Select Account', '');

            $subAccounts = ChartOfAccount::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name', 'chart_of_account_parents.account');
            $subAccounts->leftjoin('chart_of_account_parents', 'chart_of_accounts.parent', 'chart_of_account_parents.id');
            $subAccounts->where('chart_of_accounts.parent', '!=', 0);
            $subAccounts->where('chart_of_accounts.created_by', creatorId());
            $subAccounts->where('chart_of_accounts.workspace', getActiveWorkSpace());
            $subAccounts = $subAccounts->get()->toArray();

            if ($acction != 'edit') {
                $product_services->prepend('--', '');
            }
            $product_type = \Modules\ProductService\Entities\ProductService::$product_type;

            if ($acction == 'edit') {


                //for item and account show in repeater
                $item = $bill->items;

                $accounts = $bill->accounts;
                $items = [];
                if (!empty($item) && count($item) > 0) {
                    foreach ($item as $k => $val) {
                        if (!empty($accounts[$k])) {
                            $val['chart_account_id'] = $accounts[$k]['chart_account_id'];
                            $val['account_id'] = $accounts[$k]['id'];
                            $val['amount'] = $accounts[$k]['price'];
                        }
                        $items[] = $val;
                    }
                } else {
                    foreach ($accounts as $k => $val) {
                        $val1['chart_account_id'] = $accounts[$k]['chart_account_id'];
                        $val1['account_id'] = $accounts[$k]['id'];
                        $val1['amount'] = $accounts[$k]['price'];
                        $items[] = $val1;

                    }
                }
            }

            $returnHTML = view('account::bill.section', compact('product_services', 'type', 'acction', 'bill', 'product_services_count', 'product_type', 'chartAccounts', 'subAccounts'))->render();
            $response = [
                'is_success' => true,
                'message' => '',
                'html' => $returnHTML,
            ];
            return response()->json($response);
        } elseif ($request->type == "project" && module_is_active('Taskly')) {
            $projects = \Modules\Taskly\Entities\Project::where('workspace', getActiveWorkSpace())->projectonly();
            if ($request->project_id != 0) {
                $projects = $projects->where('id', $request->project_id);
            }
            $projects = $projects->first();
            $tasks = [];
            if (!empty($projects)) {
                $tasks = \Modules\Taskly\Entities\Task::where('project_id', $projects->id)->get()->pluck('title', 'id');
                if ($acction != 'edit') {
                    $tasks->prepend('--', '');
                }
            }
            $returnHTML = view('account::bill.section', compact('tasks', 'type', 'acction', 'bill'))->render();

            $response = [
                'is_success' => true,
                'message' => '',
                'html' => $returnHTML,
            ];
            return response()->json($response);
        } else {
            return [];
        }
    }

    public function billAttechment(Request $request, $id)
    {

        $bill = bill::find($id);
        $file_name = time() . "_" . $request->file->getClientOriginalName();

        $upload = upload_file($request, 'file', $file_name, 'bill_attachment', []);

        $fileSizeInBytes = \File::size($upload['url']);
        $fileSizeInKB = round($fileSizeInBytes / 1024, 2);

        if ($fileSizeInKB < 1024) {
            $fileSizeFormatted = $fileSizeInKB . " KB";
        } else {
            $fileSizeInMB = round($fileSizeInKB / 1024, 2);
            $fileSizeFormatted = $fileSizeInMB . " MB";
        }

        if ($upload['flag'] == 1) {
            $file = BillAttechment::create(
                [
                    'bill_id' => $bill->id,
                    'file_name' => $file_name,
                    'file_path' => $upload['url'],
                    'file_size' => $fileSizeFormatted,
                ]
            );
            $return = [];
            $return['is_success'] = true;


            return response()->json($return);
        } else {

            return response()->json(
                [
                    'is_success' => false,
                    'error' => $upload['msg'],
                ],
                401
            );
        }
    }

    public function billAttechmentDestroy($id)
    {

        $file = BillAttechment::find($id);

        if (!empty($file->file_path)) {
            delete_file($file->file_path);
        }
        $file->delete();
        return redirect()->back()->with('success', __('File successfully deleted.'));
    }
}
