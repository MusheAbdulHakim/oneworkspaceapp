<?php

namespace App\Http\Controllers;

use App\Events\CreateInvoice;
use App\Events\CreatePaymentInvoice;
use App\Events\DestroyInvoice;
use App\Events\DuplicateInvoice;
use App\Events\PaymentDestroyInvoice;
use App\Events\PaymentReminderInvoice;
use App\Events\ProductDestroyInvoice;
use App\Events\ResentInvoice;
use App\Events\SentInvoice;
use App\Events\UpdateInvoice;
use App\Models\BankTransferPayment;
use App\Models\EmailTemplate;
use App\Models\Invoice;
use App\Models\InvoiceAttechment;
use App\Models\InvoicePayment;
use App\Models\InvoiceProduct;
use App\Models\Proposal;
use App\Models\Setting;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Modules\Account\Entities\BankAccount;
use Modules\CMMS\Entities\Workorder;
use Modules\LMS\Entities\Store;
use Modules\LMS\Entities\Student;
use Modules\Fleet\Entities\FleetCustomer;
use Modules\ProductService\Entities\ProductService;
use Modules\RentalManagement\Entities\Rental;
use Modules\Account\Entities\AccountUtility;
use Modules\Account\Entities\Customer;
use Modules\Account\Entities\Transfer;
use Modules\CarDealership\Entities\CarDealershipUtility;
use Modules\CarDealership\Entities\DealershipProduct;
use Modules\ChildcareManagement\Entities\Child;
use Modules\ChildcareManagement\Entities\ChildFee;
use Modules\MachineRepairManagement\Entities\Machine;
use Modules\MachineRepairManagement\Entities\MachineInvoice;
use Modules\MachineRepairManagement\Entities\MachineRepairRequest;
use Modules\MobileServiceManagement\Entities\MobileServiceRequest;
use Modules\Newspaper\Entities\Newspaper;
use Modules\RestaurantMenu\Entities\RestaurantCustomer;
use Modules\RestaurantMenu\Entities\RestaurantInvoice;
use Modules\Quotation\Entities\Quotation;
use Modules\VehicleInspectionManagement\Entities\InspectionDefectsAndRepairs;
use Modules\VehicleInspectionManagement\Entities\InspectionRequest;
use Modules\VehicleInspectionManagement\Entities\InspectionVehicle;
use Modules\Fleet\Entities\Vehicle;
use Modules\Fleet\Entities\VehicleInvoice;
use Modules\ProductService\Entities\Tax;
use Modules\RestaurantMenu\Entities\RestaurantOrder;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->isAbleTo('invoice manage')) {
            $customer = User::where('workspace_id', '=', getActiveWorkSpace())->where('type', 'Client')->get()->pluck('name', 'id');

            $status = Invoice::$statues;

            if (Auth::user()->type != 'company') {
                $query = Invoice::join('users', 'invoices.user_id', '=', 'users.id')
                    ->where('users.id', Auth::user()->id)->select('invoices.*')
                    ->where('invoices.workspace', getActiveWorkSpace());
            } else {
                $query = Invoice::where('workspace', getActiveWorkSpace());
            }

            if (!empty($request->customer)) {

                $query->where('user_id', '=', $request->customer);
            }
            if (!empty($request->issue_date)) {
                $date_range = explode('to', $request->issue_date);
                if (count($date_range) == 2) {
                    $query->whereBetween('issue_date', $date_range);
                } else {
                    $query->where('issue_date', $date_range[0]);
                }
            }
            if (!empty($request->status)) {
                $query->where('status', $request->status);
            }

            if (!empty($request->account_type) && $request->account_type != 'all') {
                $query->where('account_type', $request->account_type);
            } else {
                $query->whereIn('account_type', ActivatedModule());
            }

            $invoices = $query->with('customers')->orderBy('id', 'desc')->get();

            return view('invoice.index', compact('invoices', 'customer', 'status'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    public function Grid(Request $request)
    {
        if (Auth::user()->isAbleTo('invoice manage')) {
            $customer = User::where('workspace_id', '=', getActiveWorkSpace())->where('type', 'Client')->get()->pluck('name', 'id');

            $status = Invoice::$statues;

            if (Auth::user()->type != 'company') {
                $query = Invoice::join('users', 'invoices.user_id', '=', 'users.id')
                    ->where('users.id', Auth::user()->id)->select('invoices.*')
                    ->where('invoices.workspace', getActiveWorkSpace());
            } else {
                $query = Invoice::where('workspace', getActiveWorkSpace());
            }

            if (!empty($request->customer)) {

                $query->where('user_id', '=', $request->customer);
            }
            if (!empty($request->issue_date)) {
                $date_range = explode('to', $request->issue_date);
                if (count($date_range) == 2) {
                    $query->whereBetween('issue_date', $date_range);
                } else {
                    $query->where('issue_date', $date_range[0]);
                }
            }
            if (!empty($request->status)) {
                $query->where('status', $request->status);
            }
            $invoices = $query->with('customers')->get();
            return view('invoice.grid', compact('invoices', 'customer', 'status'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create($customerId = 0)
    {

        if (module_is_active('ProductService')) {
            if (Auth::user()->isAbleTo('invoice create')) {
                $invoice_number = Invoice::invoiceNumberFormat($this->invoiceNumber());
                $customers = User::where('workspace_id', '=', getActiveWorkSpace())->where('type', 'Client')->get()->pluck('name', 'id');
                $category = [];
                $projects = [];
                $taxs = [];
                $isQuotation = false;

                if (module_is_active('Account')) {

                    if ($customerId > 0 && is_numeric($customerId)) {
                        $temp_cm = \Modules\Account\Entities\Customer::where('id', $customerId)->first();
                        if ($temp_cm) {
                            $customerId = $temp_cm->user_id;
                        } else {
                            return redirect()->back()->with('error', __('Something went wrong please try again!'));
                        }
                    } elseif ($customerId != 0) {
                        $isQuotation = true;
                    }

                    $category = \Modules\ProductService\Entities\Category::where('created_by', '=', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 1)->get()->pluck('name', 'id');
                }
                if (module_is_active('Taskly')) {
                    if (module_is_active('ProductService')) {
                        $taxs = \Modules\ProductService\Entities\Tax::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
                    }
                    $projects = \Modules\Taskly\Entities\Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', Auth::user()->id)->where('workspace', getActiveWorkSpace())->projectonly()->get()->pluck('name', 'id');
                }
                if (module_is_active('LMS')) {
                    if (module_is_active('ProductService')) {
                        $taxs = \Modules\ProductService\Entities\Tax::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
                    }
                }

                $work_order = [];

                if (module_is_active('CMMS')) {
                    $work_order = Workorder::with('getLocation')->where(['company_id' => creatorId(), 'workspace' => getActiveWorkSpace(),  'status' => 1])->get()->pluck('wo_name', 'id');
                }
                $rent_type = [];

                if (module_is_active('RentalManagement')) {
                    $rent_type = Rental::$types;
                }
                if (module_is_active('CustomField')) {
                    $customFields =  \Modules\CustomField\Entities\CustomField::where('workspace_id', getActiveWorkSpace())->where('module', '=', 'Base')->where('sub_module', 'Invoice')->get();
                } else {
                    $customFields = null;
                }

                $students = [];
                if (module_is_active('LMS')) {
                    $store = Store::where('workspace_id', getActiveWorkSpace())->where('created_by', creatorId())->first();
                    if ($store) {
                        $students = Student::where('store_id', $store->id)->get()->pluck('name', 'id');
                    }
                }

                $sale_invoice = [];
                if (module_is_active('Sales')) {
                    $sale_invoice = \Modules\Sales\Entities\SalesInvoice::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->pluck('invoice_id', 'id');
                }

                $inspectionRequests = [];
                if (module_is_active('VehicleInspectionManagement')) {
                    $inspectionRequests = InspectionRequest::where('staff_id', '!=', null)
                        ->where('created_by', creatorId())
                        ->where('workspace', getActiveWorkSpace())
                        ->whereNotIn('id', function ($query) {
                            $query->select('customer_id')->from('invoices')->where('account_type', 'VehicleInspectionManagement');
                        })
                        ->get()
                        ->pluck('id', 'id');
                }

                $machineRequests = [];
                if (module_is_active('MachineRepairManagement')) {
                    $machineRequests = MachineRepairRequest::where('staff_id', '!=', null)
                        ->where('created_by', creatorId())
                        ->where('workspace', getActiveWorkSpace())
                        ->whereNotIn('id', function ($query) {
                            $query->select('customer_id')->from('invoices')->where('account_type', 'MachineRepairManagement');
                        })
                        ->get()
                        ->pluck('id', 'id');
                }

                $music_students = [];

                if (module_is_active('MusicInstitute')) {
                    $music_students = \Modules\MusicInstitute\Entities\MusicStudent::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                }

                $restaurants = [];
                if (module_is_active('RestaurantMenu')) {
                    $restaurants = \Modules\RestaurantMenu\Entities\RestaurantCustomer::where('workspace', getActiveWorkSpace())->get()->pluck('first_name', 'id');
                    $restaurantOrder = '';
                    $restaurantOrder = RestaurantOrder::where('status', '3')->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->first();
                }
                $quotation = null;
                if ($isQuotation) {
                    try {
                        $id       = Crypt::decrypt($customerId);
                    } catch (\Throwable $th) {
                        return redirect()->back()->with('error', __('Quotation Not Found.'));
                    }
                    $quotation        = Quotation::find($id);

                    if (!$quotation) {
                        $isQuotation = false;
                    }
                }

                return view('invoice.create', compact('customers', 'invoice_number', 'projects', 'taxs', 'category', 'customerId', 'customFields', 'work_order', 'rent_type', 'students', 'sale_invoice', 'inspectionRequests', 'machineRequests', 'music_students', 'isQuotation', 'quotation', 'restaurants'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->route('invoice.index')->with('error', __('Please Enable Product & Service Module'));
        }
    }

    public function store(Request $request)
    {

        if (Auth::user()->isAbleTo('invoice create')) {
            switch ($request->invoice_type) {
                case "product":
                    return self::storeProductInvoice($request);
                    break;
                case "project":
                    return self::storeProjectInvoice($request);
                    break;
                case "parts":
                    return self::storePartsInvoice($request);
                    break;
                case "rent":
                    return self::storeRentInvoice($request);
                    break;
                case "course":
                    return self::storeCourseInvoice($request);
                    break;
                case "case":
                    return self::storeCaseInvoice($request);
                    break;
                case "sales":
                    return self::storeSalesInvoice($request);
                    break;
                case "newspaper":
                    return self::storeNewsPaperInvoice($request);
                    break;
                case "childcare":
                    return self::storeChildInvoice($request);
                    break;
                case "mobileservice":
                    return self::storeMobileInvoice($request);
                    break;
                case "vehicleinspection":
                    return self::storeVehicleInvoice($request);
                    break;
                case "machinerepair":
                    return self::storeMachineInvoice($request);
                    break;
                case "cardealership":
                    return self::storeCarDealInvoice($request);
                    break;
                case "musicinstitute":
                    return self::storeMusicInvoice($request);
                    break;
                case "restaurantmenu":
                    return self::storeRestaurantInvoice($request);
                    break;
                case "fleet":
                    return self::storeFleetInvoice($request);
                    break;
                default:
                    return redirect()->back()->with('error', __('Invalid invoice type.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show($e_id)
    {
        if (Auth::user()->isAbleTo('invoice show')) {
            try {
                $id       = Crypt::decrypt($e_id);
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', __('Invoice Not Found.'));
            }
            $invoice = Invoice::find($id);
            if ($invoice) {
                $company_settings = getCompanyAllSetting();
                $bank_transfer_payments = BankTransferPayment::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->where('type', 'invoice')->where('request', $invoice->id)->get();
                if ($invoice->workspace == getActiveWorkSpace()) {
                    $invoicePayment = InvoicePayment::where('invoice_id', $invoice->id)->first();
                    $invoice_attachment = InvoiceAttechment::where('invoice_id', $invoice->id)->get();
                    if (module_is_active('Account')) {
                        $customer = \Modules\Account\Entities\Customer::where('user_id', $invoice->user_id)->where('workspace', getActiveWorkSpace())->first();
                    }

                    if (!empty($customer)) {
                        $customer->model = 'Customer';
                    } else {
                        $customer = $invoice->customer;
                        if (!empty($customer)) {
                            $customer->model = 'User';
                        }
                    }

                    if (module_is_active('CustomField')) {
                        $invoice->customField = \Modules\CustomField\Entities\CustomField::getData($invoice, 'Base', 'Invoice');
                        $customFields      = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'Base')->where('sub_module', 'Invoice')->get();
                    } else {
                        $customFields = null;
                    }
                    $iteams   = $invoice->items;
                    $mobileCustomer = [];
                    if ($invoice->invoice_module == 'mobileservice') {
                        $mobileCustomer = MobileServiceRequest::find($invoice->customer_id);
                    }
                    $childCustomer = [];
                    if ($invoice->invoice_module == 'childcare') {
                        $childCustomer['child'] = Child::find($invoice->customer_id);
                        $childCustomer['parent'] = $childCustomer['child']->parent;
                    }
                    $commonCustomer = [];
                    if ($invoice->invoice_module == 'Fleet') {
                        $user =  User::find($invoice->user_id);

                        $commonCustomer['name'] = $user->name;
                        $commonCustomer['email'] = $user->email;
                    }

                    if ($invoice->invoice_module == 'legalcase' || $invoice->invoice_module == 'sales' || $invoice->invoice_module == 'newspaper') {
                        $user = User::where('id', $invoice->user_id)->where('workspace_id', getActiveWorkSpace())->where('created_by', creatorId())->first();
                        $commonCustomer['name'] = !empty($user->name) ? $user->name : '';
                        $commonCustomer['email'] = !empty($user->email) ? $user->email : '';
                    }
                    if ($invoice->invoice_module == 'lms') {

                        $store = Store::where('workspace_id', getActiveWorkSpace())->where('created_by', creatorId())->first();
                        $customers = Student::where('store_id', $store->id)->where('id', $invoice->customer_id)->first();
                        $commonCustomer['name'] = !empty($customers->name) ? $customers->name : '';
                        $commonCustomer['email'] = !empty($customers->email) ? $customers->email : '';
                    }

                    if ($invoice->invoice_module == 'RestaurantMenu') {
                        $customers = RestaurantCustomer::where('id', $invoice->customer_id)->first();
                        $commonCustomer['name'] = !empty($customers->first_name) ? $customers->first_name : '';
                        $commonCustomer['email'] = !empty($customers->email) ? $customers->email : '';
                    }

                    return view('invoice.view', compact('invoice', 'customer', 'iteams', 'invoicePayment', 'customFields', 'bank_transfer_payments', 'invoice_attachment', 'mobileCustomer', 'commonCustomer', 'childCustomer','company_settings'));
                } else {
                    return redirect()->back()->with('error', __('Permission denied.'));
                }
            } else {
                return redirect()->back()->with('error', __('This invoice is deleted.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function edit($e_id)
    {

        if (module_is_active('ProductService')) {
            if (Auth::user()->isAbleTo('invoice edit')) {
                try {
                    $id       = Crypt::decrypt($e_id);
                } catch (\Throwable $th) {
                    return redirect()->back()->with('error', __('Invoice Not Found.'));
                }
                $invoice = Invoice::find($id);
                $invoice_number = Invoice::invoiceNumberFormat($invoice->invoice_id);

                $customers = User::where('workspace_id', '=', getActiveWorkSpace())->where('type', 'Client')->get()->pluck('name', 'id');

                $category = [];
                $projects = [];
                $taxs = [];
                if (module_is_active('Account')) {
                    $category = \Modules\ProductService\Entities\Category::where('created_by', '=', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 1)->get()->pluck('name', 'id');
                }
                if (module_is_active('Taskly')) {
                    if (module_is_active('ProductService')) {
                        $taxs = \Modules\ProductService\Entities\Tax::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
                    }
                    $projects = \Modules\Taskly\Entities\Project::where('workspace', getActiveWorkSpace())->projectonly()->get()->pluck('name', 'id');
                }

                if (module_is_active('LMS')) {
                    if (module_is_active('ProductService')) {
                        $taxs = \Modules\ProductService\Entities\Tax::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
                    }
                }

                $work_order = [];
                if (module_is_active('CMMS')) {
                    $work_order = WorkOrder::with('getLocation')->where(['company_id' => creatorId(), 'workspace' => getActiveWorkSpace(),  'status' => 1])->get()->pluck('wo_name', 'id');
                }

                $rent_type = [];

                if (module_is_active('RentalManagement')) {
                    $rent_type = Rental::$types;
                }


                if (module_is_active('CustomField')) {
                    $invoice->customField = \Modules\CustomField\Entities\CustomField::getData($invoice, 'Base', 'Invoice');
                    $customFields             = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'Base')->where('sub_module', 'Invoice')->get();
                } else {
                    $customFields = null;
                }

                $course_order = [];
                $students = [];
                if (module_is_active('LMS')) {
                    $store = Store::where('workspace_id', getActiveWorkSpace())->where('created_by', creatorId())->first();
                    if ($store) {
                        $students = Student::where('store_id', $store->id)->get()->pluck('name', 'id');
                    }
                }

                $sale_invoice = [];
                if (module_is_active('Sales')) {
                    $sale_invoice = \Modules\Sales\Entities\SalesInvoice::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->pluck('invoice_id', 'id');
                }
                $inspectionRequests = [];
                if (module_is_active('VehicleInspectionManagement')) {
                    $inspectionRequests = InspectionRequest::where('staff_id', '!=', null)
                        ->where('created_by', creatorId())
                        ->where('workspace', getActiveWorkSpace())
                        ->whereNotIn('id', function ($query) use ($id, $invoice) {
                            $query->select('customer_id')->from('invoices')->where('customer_id', '!=', $id)->where('customer_id', '!=', $invoice->customer_id)->where('account_type', 'VehicleInspectionManagement');
                        })
                        ->get()
                        ->pluck('id', 'id');
                }


                $machineRequests = [];
                if (module_is_active('MachineRepairManagement')) {
                    $machineRequests = MachineRepairRequest::where('staff_id', '!=', null)
                        ->where('created_by', creatorId())
                        ->where('workspace', getActiveWorkSpace())
                        ->whereNotIn('id', function ($query) use ($id, $invoice) {
                            $query->select('customer_id')->from('invoices')->where('customer_id', '!=', $id)->where('customer_id', '!=', $invoice->customer_id)->where('account_type', 'MachineRepairManagement');
                        })
                        ->get()
                        ->pluck('id', 'id');
                }
                $music_students = [];

                if (module_is_active('MusicInstitute')) {
                    $music_students = \Modules\MusicInstitute\Entities\MusicStudent::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                }
                return view('invoice.edit', compact('customers', 'projects', 'taxs', 'invoice', 'invoice_number', 'category', 'customFields', 'work_order', 'rent_type', 'course_order', 'students', 'sale_invoice', 'inspectionRequests', 'machineRequests', 'music_students'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return redirect()->route('invoice.index')->with('error', __('Please Enable Product & Service Module'));
        }
    }

    public function course(Request $request)
    {
        if (!empty($request->course)) {
            $courses = \Modules\LMS\Entities\Course::where('id', $request->course)->where('workspace_id', getActiveWorkSpace())->where('created_by', creatorId())->first();
            $course['price'] = $courses->price;
            $course['discount'] = !empty($courses->discount) ? $courses->discount : '0';
            $course['amount'] = $course['price'] - $course['discount'];
        }
        return response()->json($course);
    }

    public function update(Request $request, Invoice $invoice)
    {

        if (Auth::user()->isAbleTo('invoice edit')) {
            if ($invoice->workspace == getActiveWorkSpace()) {
                switch ($request->invoice_type) {
                    case "product":
                        return self::UpdateProductInvoice($request, $invoice);
                        break;
                    case "project":
                        return self::UpdateProjectInvoice($request, $invoice);
                        break;
                    case "parts":
                        return self::UpdatePartsInvoice($request, $invoice);
                        break;
                    case "rent":
                        return self::UpdateRentInvoice($request, $invoice);
                        break;
                    case "course":
                        return self::UpdateCourseInvoice($request, $invoice);
                        break;
                    case "case":
                        return self::UpdateCaseInvoice($request, $invoice);
                        break;
                    case "sales":
                        return self::UpdateSalesInvoice($request, $invoice);
                        break;
                    case "newspaper":
                        return self::UpdateNewspaperInvoice($request, $invoice);
                        break;
                    case "childcare":
                        return self::updateChildInvoice($request, $invoice);
                        break;
                    case "mobileservice":
                        return self::updateMobileInvoice($request, $invoice);
                        break;
                    case "vehicleinspection":
                        return self::updateVehicleInvoice($request, $invoice);
                        break;
                    case "machinerepair":
                        return self::updateMachineInvoice($request, $invoice);
                        break;
                    case "cardealership":
                        return self::updateCarDealInvoice($request, $invoice);
                        break;
                    case "fleet":
                        return self::updateFleetInvoice($request, $invoice);
                        break;
                    case "restaurantmenu":
                        return self::updateRestaurantInvoice($request, $invoice);
                        break;
                    case "musicinstitute":
                        return self::updateMusicInvoice($request, $invoice);
                        break;
                    default:
                        return redirect()->back()->with('error', __('Invalid invoice type.'));
                }
                // first parameter request second parameter invoice
                event(new UpdateInvoice($request, $invoice));
                return redirect()->route('invoice.index')->with('success', __('Invoice successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function duplicate($invoice_id)
    {
        if (Auth::user()->isAbleTo('invoice duplicate')) {
            $invoice                            = Invoice::where('id', $invoice_id)->first();
            $duplicateInvoice                   = new Invoice();
            $duplicateInvoice->invoice_id       = $this->invoiceNumber();
            $duplicateInvoice->account_type      = $invoice['account_type'];
            $duplicateInvoice->customer_id      = $invoice['customer_id'];
            $duplicateInvoice->user_id          = $invoice['user_id'];
            $duplicateInvoice->issue_date       = date('Y-m-d');
            $duplicateInvoice->due_date         = $invoice['due_date'];
            $duplicateInvoice->send_date        = null;
            $duplicateInvoice->category_id      = $invoice['category_id'];
            $duplicateInvoice->status           = 0;
            $duplicateInvoice->shipping_display = $invoice['shipping_display'];
            $duplicateInvoice->invoice_module   = $invoice['invoice_module'];
            $duplicateInvoice->invoice_template      = $invoice['invoice_template'];
            $duplicateInvoice->workspace        = $invoice['workspace'];
            $duplicateInvoice->created_by       = $invoice['created_by'];
            $duplicateInvoice->save();
            Invoice::starting_number($duplicateInvoice->invoice_id + 1, 'invoice');

            if ($duplicateInvoice) {
                $invoiceProduct = InvoiceProduct::where('invoice_id', $invoice_id)->get();
                foreach ($invoiceProduct as $product) {
                    $duplicateProduct                 = new InvoiceProduct();
                    $duplicateProduct->invoice_id     = $duplicateInvoice->id;
                    $duplicateProduct->product_type   = $product->product_type;
                    $duplicateProduct->product_id     = $product->product_id;
                    $duplicateProduct->quantity       = $product->quantity;
                    $duplicateProduct->tax            = $product->tax;
                    $duplicateProduct->discount       = $product->discount;
                    $duplicateProduct->price          = $product->price;
                    $duplicateProduct->save();


                    if ($duplicateInvoice->invoice_module == 'cardealership') {
                        CarDealershipUtility::total_quantity('minus', $duplicateProduct->quantity, $duplicateProduct->product_id);
                    } else {
                        if (module_is_active('ProductService')) {
                            Invoice::total_quantity('minus', $duplicateProduct->quantity, $duplicateProduct->product_id);
                        }
                    }
                }
            }
            event(new DuplicateInvoice($duplicateInvoice, $invoice));

            return redirect()->back()->with('success', __('Invoice duplicate successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    //

    public function sent($id)
    {
        if (Auth::user()->isAbleTo('invoice send')) {
            $invoice            = Invoice::where('id', $id)->first();
            $invoice->send_date = date('Y-m-d');
            $invoice->status    = 1;
            $invoice->save();
            if (module_is_active('Account')) {
                try {
                    $customer         = \Modules\Account\Entities\Customer::where('user_id', $invoice->user_id)->first();
                    if (empty($customer)) {
                        $customer         = User::where('id', $invoice->user_id)->first();
                    }
                } catch (Exception $e) {
                }
            } else {
                $customer         = User::where('id', $invoice->user_id)->first();
            }
            $invoice->name    = !empty($customer) ? $customer->name : '';
            $invoice->invoice = Invoice::invoiceNumberFormat($invoice->invoice_id);

            $invoiceId    = Crypt::encrypt($invoice->id);
            $invoice->url = route('invoice.pdf', $invoiceId);
            $invoice->payinvoice = route('pay.invoice', $invoiceId);
            // first parameter invoice
            event(new SentInvoice($invoice));

            //Email notification
            if (!empty(company_setting('Customer Invoice Send')) && company_setting('Customer Invoice Send')  == true) {
                $uArr = [
                    'invoice_name'    => $invoice->name,
                    'invoice_number'  => $invoice->invoice,
                    'invoice_url'     => $invoice->url,
                    'pay_invoice_url' => $invoice->payinvoice
                ];

                try {
                    $resp = EmailTemplate::sendEmailTemplate('Customer Invoice Send', [$customer->id => $customer->email], $uArr);
                } catch (\Exception $e) {
                    $resp['error'] = $e->getMessage();
                }

                return redirect()->back()->with('success', __('Invoice successfully sent.') . ((isset($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }
            return redirect()->back()->with('success', 'Invoice sent email notification is off.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function resent($id)
    {
        if (Auth::user()->isAbleTo('invoice send')) {
            $invoice = Invoice::where('id', $id)->first();
            if (module_is_active('Account')) {
                $customer         = \Modules\Account\Entities\Customer::where('user_id', $invoice->user_id)->first();
                if (empty($customer)) {
                    $customer         = User::where('id', $invoice->user_id)->first();
                }
            } else {
                $customer         = User::where('id', $invoice->user_id)->first();
            }

            $invoice->name    = !empty($customer) ? $customer->name : '';
            $invoice->invoice = Invoice::invoiceNumberFormat($invoice->invoice_id);

            $invoiceId    = Crypt::encrypt($invoice->id);
            $invoice->url = route('invoice.pdf', $invoiceId);
            $invoice->payinvoice = route('pay.invoice', $invoiceId);

            // first parameter invoice
            event(new ResentInvoice($invoice));

            if (!empty(company_setting('Customer Invoice Send')) && company_setting('Customer Invoice Send')  == true) {
                $uArr = [
                    'invoice_name' => $invoice->name,
                    'invoice_number' => $invoice->invoice,
                    'invoice_url' => $invoice->url,
                    'pay_invoice_url' => $invoice->payinvoice
                ];

                try {
                    $resp = EmailTemplate::sendEmailTemplate('Customer Invoice Send', [$customer->id => $customer->email], $uArr);
                } catch (\Exception $e) {
                    $resp['error'] = $e->getMessage();
                }
                return redirect()->back()->with('success', __('Invoice successfully sent.') . ((isset($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }
            return redirect()->back()->with('success', 'Invoice sent email notification is off.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function paymentReminder($invoice_id)
    {
        $invoice            = Invoice::find($invoice_id);
        if ($invoice) {
            if (module_is_active('Account')) {
                $customer         = \Modules\Account\Entities\Customer::where('user_id', $invoice->user_id)->first();
                if (empty($customer)) {
                    $customer         = User::where('id', $invoice->user_id)->first();
                }
            } else {
                $customer         = User::where('id', $invoice->user_id)->first();
            }

            $invoice->dueAmount = currency_format_with_sym($invoice->getDue());
            $invoice->name      = $customer['name'];
            $invoice->date      = company_date_formate($invoice->send_date);
            $invoice->invoice   = Invoice::invoiceNumberFormat($invoice->invoice_id);

            // first parameter invoice
            event(new PaymentReminderInvoice($invoice));

            //Email notification
            if (!empty(company_setting('Payment Reminder')) && company_setting('Payment Reminder')  == true) {
                $uArr = [
                    'payment_name' => $invoice->name,
                    'invoice_number' => $invoice->invoice,
                    'payment_dueAmount' => $invoice->dueAmount,
                    'payment_date' => $invoice->date,
                ];

                try {
                    $resp = EmailTemplate::sendEmailTemplate('Payment Reminder', [$customer->id => $customer->email], $uArr);
                } catch (\Exception $e) {
                    $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
                }
            }

            return redirect()->back()->with('success', __('Payment reminder successfully send.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        } else {
            return redirect()->back()->with('error', __('Invoice not found!'));
        }
    }

    public function invoice($invoice_id)
    {
        try {
            $invoiceId = Crypt::decrypt($invoice_id);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Invoice Not Found.'));
        }
        $invoice   = Invoice::where('id', $invoiceId)->first();
        $bank_accounts = InvoicePayment::where('invoice_id', $invoiceId)->get()->pluck('account_id');
        $bank_details = BankAccount::whereIn('id', $bank_accounts)->get();

        $bank_details_list = [];
        foreach ($bank_details as $bank_detail) {
            $bankDetail = new \stdClass();
            $bankDetail->holder_name = $bank_detail->holder_name;
            $bankDetail->bank_name = $bank_detail->bank_name;
            $bankDetail->account_number = $bank_detail->account_number;
            $bankDetail->opening_balance = $bank_detail->opening_balance;
            $bankDetail->contact_number = $bank_detail->contact_number;
            $bankDetail->bank_address = $bank_detail->bank_address;

            $bank_details_list[] = $bankDetail;
        }

        if (module_is_active('Account')) {
            $customer         = \Modules\Account\Entities\Customer::where('user_id', $invoice->user_id)->first();
        } else {
            $customer         = User::where('id', $invoice->user_id)->first();
        }
        $items         = [];
        $totalTaxPrice = 0;
        $totalQuantity = 0;
        $totalRate     = 0;
        $totalDiscount = 0;
        $taxesData     = [];
        foreach ($invoice->items as $product) {
            $item              = new \stdClass();

            if ($invoice->invoice_module == "taskly") {
                $item->name        = !empty($product->product()) ? $product->product()->title : '';
            } elseif ($invoice->invoice_module == "account" || $invoice->invoice_module == "sales" || $invoice->invoice_module == 'cardealership' || $invoice->invoice_module == 'musicinstitute' || $invoice->invoice_module == 'machinerepair' || $invoice->invoice_module == 'newspaper' || $invoice->invoice_module == 'mobileservice' || $invoice->invoice_module == 'vehicleinspection') {
                $item->name        = !empty($product->product()) ? $product->product()->name : '';
                $item->product_type   = !empty($product->product_type) ? $product->product_type : '';
            } elseif ($invoice->invoice_module == "cmms") {
                $item->name        = !empty($product->product()) ? $product->product()->name : '';
                $item->product_type   = !empty($product->product_type) ? $product->product_type : '';
            } elseif ($invoice->invoice_module == "rent") {
                $item->name        = !empty($product->product()) ? $product->product()->name : '';
                $item->product_type   = !empty($product->product_type) ? $product->product_type : '';
            } elseif ($invoice->invoice_module == "lms") {
                $item->name        = !empty($product->product()) ? $product->product()->title : '';
            } elseif ($invoice->invoice_module == 'childcare' || $invoice->invoice_module == 'legalcase') {
                $item->name        = !empty($product->product_name) ? $product->product_name : '';
            } elseif ($invoice->invoice_module == 'Fleet') {
                $item->name        = !empty($product->product()) ? $product->product()->distance : 0;
            } elseif ($invoice->invoice_module == "musicinstitute") {
                $item->name        = !empty($product->product()) ? $product->product()->name : '';
                $item->product_type   = !empty($product->product_type) ? $product->product_type : '';
            } elseif ($invoice->invoice_module == "RestaurantMenu") {
                $item->name        = !empty($product->product_name) ? $product->product_name : '';
            }
            $item->quantity    = $product->quantity;
            $item->tax         = $product->tax;
            $item->discount    = $product->discount;
            $item->price       = $product->price;
            $item->description = $product->description;
            $totalQuantity += $item->quantity;
            $totalRate     += $item->price;
            $totalDiscount += $item->discount;
            if (module_is_active('ProductService')) {
                $taxes = \Modules\ProductService\Entities\Tax::tax($product->tax);
                $itemTaxes = [];
                $tax_price = 0;
                if (!empty($item->tax)) {
                    foreach ($taxes as $tax) {
                        $taxPrice      = Invoice::taxRate($tax->rate, $item->price, $item->quantity, $item->discount);
                        $tax_price  += $taxPrice;
                        $totalTaxPrice += $taxPrice;

                        $itemTax['name']  = $tax->name;
                        $itemTax['rate']  = $tax->rate . '%';
                        $itemTax['price'] = currency_format_with_sym($taxPrice, $invoice->created_by);
                        $itemTaxes[]      = $itemTax;

                        if (array_key_exists($tax->name, $taxesData)) {
                            $taxesData[$tax->name] = $taxesData[$tax->name] + $taxPrice;
                        } else {
                            $taxesData[$tax->name] = $taxPrice;
                        }
                    }
                    $item->itemTax = $itemTaxes;
                    $item->tax_price = $tax_price;
                } else {
                    $item->itemTax = [];
                }
                $items[] = $item;
            }
        }
        $invoice->itemData      = $items;
        $invoice->totalTaxPrice = $totalTaxPrice;
        $invoice->totalQuantity = $totalQuantity;
        $invoice->totalRate     = $totalRate;
        $invoice->totalDiscount = $totalDiscount;
        $invoice->taxesData     = $taxesData;
        if (module_is_active('CustomField')) {
            $invoice->customField = \Modules\CustomField\Entities\CustomField::getData($invoice, 'Base', 'Invoice');
            $customFields             = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', $invoice->workspace)->where('module', '=', 'Base')->where('sub_module', 'Invoice')->get();
        } else {
            $customFields = null;
        }

        //Set your logo
        $company_logo = get_file(sidebar_logo());
        $company_settings = getCompanyAllSetting($invoice->created_by, $invoice->workspace);
        $invoice_logo = isset($company_settings['invoice_logo']) ? $company_settings['invoice_logo'] : '';
        if (isset($invoice_logo) && !empty($invoice_logo)) {
            $img  = get_file($invoice_logo);
        } else {
            $img  = $company_logo;
        }

        $commonCustomer = [];
        if ($invoice->invoice_module == 'Fleet') {
            $user =  User::find($invoice->user_id);

            $commonCustomer['name'] = $user->name;
            $commonCustomer['email'] = $user->email;
        }

        if ($invoice) {
            $color      = '#' . (!empty($company_settings['invoice_color']) ? $company_settings['invoice_color'] : 'ffffff');
            $font_color = User::getFontColor($color);
            if (!empty($invoice->invoice_template)) {
                $invoice_template = $invoice->invoice_template;
            } else {
                $invoice_template  = (!empty($company_settings['invoice_template']) ? $company_settings['invoice_template'] : 'template1');
            }
            $settings['site_rtl'] = isset($company_settings['site_rtl']) ? $company_settings['site_rtl'] : '';
            $settings['company_name'] = isset($company_settings['company_name']) ? $company_settings['company_name'] : '';
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
            $settings['footer_title'] = isset($company_settings['invoice_footer_title']) ? $company_settings['invoice_footer_title'] : '';
            $settings['footer_notes'] = isset($company_settings['invoice_footer_notes']) ? $company_settings['invoice_footer_notes'] : '';
            $settings['shipping_display'] = isset($company_settings['invoice_shipping_display']) ? $company_settings['invoice_shipping_display'] : '';
            $settings['invoice_template'] = isset($company_settings['invoice_template']) ? $company_settings['invoice_template'] : '';
            $settings['invoice_color'] = isset($company_settings['invoice_color']) ? $company_settings['invoice_color'] : '';
            $settings['invoice_qr_display'] = isset($company_settings['invoice_qr_display']) ? $company_settings['invoice_qr_display'] : '';

            return view('invoice.templates.' . $invoice_template, compact('invoice', 'commonCustomer','color', 'settings', 'customer', 'img', 'font_color', 'customFields', 'bank_details', 'bank_details_list'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function product(Request $request)
    {
        $data['product']     = $product = \Modules\ProductService\Entities\ProductService::find($request->product_id);
        $data['unit']        = !empty($product) ? ((!empty($product->unit())) ? $product->unit()->name : '') : '';
        $data['taxRate']     = $taxRate = !empty($product) ? (!empty($product->tax_id) ? $product->taxRate($product->tax_id) : 0) : 0;
        $data['taxes']       =  !empty($product) ? (!empty($product->tax_id) ? $product->tax($product->tax_id) : 0) : 0;
        $salePrice           = !empty($product) ?  $product->sale_price : 0;
        $quantity            = 1;
        $taxPrice            = !empty($product) ? (($taxRate / 100) * ($salePrice * $quantity)) : 0;
        $data['totalAmount'] = !empty($product) ?  ($salePrice * $quantity) : 0;

        return json_encode($data);
    }

    public function productDestroy(Request $request)
    {

        if (Auth::user()->isAbleTo('invoice product delete')) {
            $invoiceProduct = InvoiceProduct::where('id', '=', $request->id)->first();

            // first parameter request second parameter invoice
            event(new ProductDestroyInvoice($request, $invoiceProduct));

            $invoiceProduct->delete();

            return response()->json(['success' => __('Invoice product successfully deleted.')]);
        } else {
            return response()->json(['error' => __('Permission denied.')]);
        }
    }

    public function saveTemplateSettings(Request $request)
    {
        $user = Auth::user();
        if ($request->hasFile('invoice_logo')) {
            $invoice_logo = $user->id . '_invoice_logo' . time() . '.png';

            $uplaod = upload_file($request, 'invoice_logo', $invoice_logo, 'invoice_logo');
            if ($uplaod['flag'] == 1) {
                $url = $uplaod['url'];
                $old_invoice_logo = company_setting('invoice_logo');
                if (!empty($old_invoice_logo) && check_file($old_invoice_logo)) {
                    delete_file($old_invoice_logo);
                }
            } else {
                return redirect()->back()->with('error', $uplaod['msg']);
            }
        }
        $post = $request->all();
        unset($post['_token']);

        if (isset($post['invoice_template']) && (!isset($post['invoice_color']) || empty($post['invoice_color']))) {
            $post['invoice_color'] = "ffffff";
        }
        if (isset($post['invoice_logo'])) {
            $post['invoice_logo'] = $url;
        }
        if (!isset($post['invoice_shipping_display'])) {
            $post['invoice_shipping_display'] = 'off';
        }
        if (!isset($post['invoice_qr_display'])) {
            $post['invoice_qr_display'] = 'off';
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
        return redirect()->back()->with('success', __('Invoice Print setting save sucessfully.'));
    }

    public function previewInvoice($template, $color)
    {
        $invoice  = new Invoice();

        $customer                   = new \stdClass();

        $customer->email            = '<Email>';
        $customer->shipping_name    = '<Customer Name>';
        $customer->shipping_country = '<Country>';
        $customer->shipping_state   = '<State>';
        $customer->shipping_city    = '<City>';
        $customer->shipping_phone   = '<Customer Phone Number>';
        $customer->shipping_zip     = '<Zip>';
        $customer->shipping_address = '<Address>';
        $customer->billing_name     = '<Customer Name>';
        $customer->billing_country  = '<Country>';
        $customer->billing_state    = '<State>';
        $customer->billing_city     = '<City>';
        $customer->billing_phone    = '<Customer Phone Number>';
        $customer->billing_zip      = '<Zip>';
        $customer->billing_address  = '<Address>';

        $bank_details = [];
        for ($i = 1; $i <= 2; $i++) {
            $bank_details =  new \stdClass();
            $bank_details->holder_name  = 'Holder';
            $bank_details->bank_name    = 'Bank Name';
            $bank_details->account_number        = 'Account Number';
            $bank_details->opening_balance       = 0;
            $bank_details->bank_address          = 'Bank Address';
            $bank_details->contact_number        = 'Contact Number';
        }


        $totalTaxPrice = 0;
        $taxesData     = [];

        $items = [];
        for ($i = 1; $i <= 3; $i++) {
            $item           = new \stdClass();
            $item->name     = 'Item ' . $i;
            $item->quantity = 1;
            $item->tax      = 5;
            $item->discount = 50;
            $item->price    = 100;
            $item->description    = 'In publishing and graphic design, Lorem ipsum is a placeholder';

            $taxes = [
                'Tax 1',
                'Tax 2',
            ];

            $itemTaxes = [];
            foreach ($taxes as $k => $tax) {
                $taxPrice         = 10;
                $totalTaxPrice    += $taxPrice;
                $itemTax['name']  = 'Tax ' . $k;
                $itemTax['rate']  = '10 %';
                $itemTax['price'] = '$10';
                $itemTaxes[]      = $itemTax;
                if (array_key_exists('Tax ' . $k, $taxesData)) {
                    $taxesData['Tax ' . $k] = $taxesData['Tax 1'] + $taxPrice;
                } else {
                    $taxesData['Tax ' . $k] = $taxPrice;
                }
            }
            $item->itemTax = $itemTaxes;
            $item->tax_price = 10;
            $items[]       = $item;
        }


        $invoice->invoice_id = 1;
        $invoice->issue_date = date('Y-m-d H:i:s');
        $invoice->due_date   = date('Y-m-d H:i:s');
        $invoice->itemData   = $items;

        $invoice->totalTaxPrice = 60;
        $invoice->totalQuantity = 3;
        $invoice->totalRate     = 300;
        $invoice->totalDiscount = 10;
        $invoice->taxesData     = $taxesData;
        $invoice->customField   = [];
        $customFields           = [];

        $preview    = 1;
        $color      = '#' . $color;
        $font_color = User::getFontColor($color);

        $company_logo = get_file(sidebar_logo());

        $company_settings = getCompanyAllSetting();

        $invoice_logo =  isset($company_settings['invoice_logo']) ? $company_settings['invoice_logo'] : '';

        if (!empty($invoice_logo)) {
            $img = get_file($invoice_logo);
        } else {
            $img          =  $company_logo;
        }
        $settings['site_rtl'] = isset($company_settings['site_rtl']) ? $company_settings['site_rtl'] : '';
        $settings['company_name'] = isset($company_settings['company_name']) ? $company_settings['company_name'] : '';
        $settings['company_address'] = isset($company_settings['company_address']) ? $company_settings['company_address'] : '';
        $settings['company_email'] = isset($company_settings['company_email']) ? $company_settings['company_email'] : '';
        $settings['company_telephone'] = isset($company_settings['company_telephone']) ? $company_settings['company_telephone'] : '';
        $settings['company_city'] = isset($company_settings['company_city']) ? $company_settings['company_city'] : '';
        $settings['company_state'] = isset($company_settings['company_state']) ? $company_settings['company_state'] : '';
        $settings['company_zipcode'] = isset($company_settings['company_zipcode']) ? $company_settings['company_zipcode'] : '';
        $settings['company_country'] = isset($company_settings['company_country']) ? $company_settings['company_country'] : '';
        $settings['registration_number'] = isset($company_settings['registration_number']) ? $company_settings['registration_number'] : '';
        $settings['tax_type'] = isset($company_settings['tax_type']) ? $company_settings['tax_type'] : '';
        $settings['vat_number'] = isset($company_settings['vat_number']) ? $company_settings['vat_number'] : '';
        $settings['footer_title'] = isset($company_settings['invoice_footer_title']) ? $company_settings['invoice_footer_title'] : '';
        $settings['footer_notes'] = isset($company_settings['invoice_footer_notes']) ? $company_settings['invoice_footer_notes'] : '';
        $settings['shipping_display'] = isset($company_settings['invoice_shipping_display']) ? $company_settings['invoice_shipping_display'] : '';
        $settings['invoice_template'] = isset($company_settings['invoice_template']) ? $company_settings['invoice_template'] : '';
        $settings['invoice_color'] = isset($company_settings['invoice_color']) ? $company_settings['invoice_color'] : '';
        $settings['invoice_qr_display'] = isset($company_settings['invoice_qr_display']) ? $company_settings['invoice_qr_display'] : '';

        return view('invoice.templates.' . $template, compact('invoice', 'preview', 'color', 'img', 'settings', 'customer', 'font_color', 'customFields', 'bank_details'));
    }

    public function items(Request $request)
    {

        $data['items']       = InvoiceProduct::where('invoice_id', $request->invoice_id)->where('product_id', $request->product_id)->first();
        $data['product']     = $product = \Modules\ProductService\Entities\ProductService::find($request->product_id);
        $data['unit']        = !empty($product) ? ((!empty($product->unit())) ? $product->unit()->name : '') : '';
        $data['taxRate']     = $taxRate = !empty($product) ? (!empty($product->tax_id) ? $product->taxRate($product->tax_id) : 0) : 0;
        $data['taxes']       =  !empty($product) ? (!empty($product->tax_id) ? $product->tax($product->tax_id) : 0) : 0;
        $salePrice           = !empty($product) ?  $product->sale_price : 0;
        $quantity            = 1;
        $taxPrice            = !empty($product) ? (($taxRate / 100) * ($salePrice * $quantity)) : 0;
        $data['totalAmount'] = !empty($product) ?  ($salePrice * $quantity) : 0;

        return json_encode($data);
    }

    public function customer(Request $request)
    {
        $type = $request->type;

        if ($type == 'childcare') {
            return self::getChildDetail($request);
        } else if ($type == 'mobileservice') {
            $customer = MobileServiceRequest::find($request->id);
            return view('invoice.customer_detail', compact('customer', 'type'));
        } else if ($type == 'vehicleinspection') {

            $inspection_request = InspectionRequest::where('id', $request->id)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->first();
            $inspector['name'] = !empty($inspection_request->inspector_name) ? $inspection_request->inspector_name : '';
            $inspector['email'] = !empty($inspection_request->inspector_email) ? $inspection_request->inspector_email : '';
            $vehicle_details = InspectionVehicle::find($inspection_request->vehicle_id);
            return view('vehicleinspectionmanagement::defects-repairs.inspector_detail', compact('inspector', 'inspection_request', 'vehicle_details'));
        } else if ($type == 'machinerepair') {

            $repair_request = MachineRepairRequest::where('id', $request->id)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->first();
            $customer['name'] = !empty($repair_request->customer_name) ? $repair_request->customer_name : '';
            $customer['email'] = !empty($repair_request->customer_email) ? $repair_request->customer_email : '';
            $machine_details = Machine::find($repair_request->machine_id);

            return view('machinerepairmanagement::invoice.customer_detail', compact('customer', 'repair_request', 'machine_details'));
        } else if ($type == 'course') {

            $courseorder = [];
            if ($request->id) {
                $courseorder = \Modules\LMS\Entities\CourseOrderSummary::where(['student_id' => $request->id, 'status' => 'unpaid'])->get()->pluck('order_id', 'id');
            }

            return response()->json($courseorder);
        } else if ($type == 'restaurantmenu') {

            $restaurantOrder = [];
            if ($request->id) {
                $restaurantOrder = RestaurantOrder::where(['customer_id' => $request->id, 'status' => 3])->get()->pluck('order_id', 'id');
            }

            return response()->json($restaurantOrder);
        } else {

            if (module_is_active('Account')) {
                $customer = \Modules\Account\Entities\Customer::where('user_id', '=', $request->id)->first();
                if (empty($customer)) {
                    $user = User::where('id', $request->id)->where('workspace_id', getActiveWorkSpace())->where('created_by', creatorId())->first();
                    $customer['name'] = !empty($user->name) ? $user->name : '';
                    $customer['email'] = !empty($user->email) ? $user->email : '';
                }
            } else {
                $user = User::where('id', $request->id)->where('workspace_id', getActiveWorkSpace())->where('created_by', creatorId())->first();
                $customer['name'] = !empty($user->name) ? $user->name : '';
                $customer['email'] = !empty($user->email) ? $user->email : '';
            }
            return view('invoice.customer_detail', compact('customer', 'type'));
        }
    }

    public function getChildDetail($request)
    {

        $child = Child::find($request->id);

        $items = [];
        if ($request->childfee_id) {
            $childfee = ChildFee::where('id', $request->childfee_id)->where('child_id', $child->id)->first();
            if ($childfee) {

                $items = json_decode($childfee->items);
            }
        }

        $parent = $child->parent;

        $nutritions = json_decode($child->nutritions);

        if (!empty($child->class)) {

            $nutritionDetail = '';

            $tableData = '<tr>
                                    <input type="hidden" name="items[0][id]" class="form-control id">
                                    <td width="35%" class="form-group">
                                        <input type="text" name="items[0][name]" class="form-control" value="' . (!empty($child->class) ? $child->class->class_level : '') . '" placeholder="Item Name" required readonly>
                                    </td>
                                    <td width="35%" class="form-group">
                                        <div class="input-group">
                                            <input type="number" name="items[0][amount]" class="form-control amount" value="' . (!empty($items[0]) ? $items[0]->amount : 0) . '" placeholder="Amount" required>
                                            <span class="input-group-text bg-transparent">' . (isset($company_settings['defult_currancy_symbol']) ? $company_settings['defult_currancy_symbol'] : '$') . '</span>
                                        </div>
                                    </td>
                                    <td width="10%">&nbsp;</td>
                                </tr>';


            if (!empty($nutritions)) {

                foreach ($nutritions as $key => $nutrition) {
                    $index = ++$key;
                    $nutritionDetail .= '<span><b>Food Name : </b>' . $nutrition->food_name . ' (Qty : ' . $nutrition->quantity . ')</span><br>';
                    $tableData .= '<tr>
                                    <input type="hidden" name="items[' . $index . '][id]" class="form-control id">
                                        <td width="35%" class="form-group">
                                            <input type="text" name="items[' . $index . '][name]" class="form-control" value="' . $nutrition->food_name . '" placeholder="Amount" required readonly>
                                        </td>
                                        <td width="35%" class="form-group">
                                            <div class="input-group">
                                                <input type="number" name="items[' . $index . '][amount]" class="form-control amount" value="' . (!empty($items[$index]) ? $items[$index]->amount : 0) . '" placeholder="Amount" required>
                                                <span class="input-group-text bg-transparent">' . (isset($company_settings['defult_currancy_symbol']) ? $company_settings['defult_currancy_symbol'] : '$') . '</span>
                                            </div>
                                        </td>
                                        <td width="10%">&nbsp;</td>
                                    </tr>';
                }
            }

            $html = '<div class="row">
                        <div class="col-md-5 col-12">
                            <h6>Child Detail</h6>
                            <p>
                                <span><b>Name : </b>' . $child->first_name . ' ' . $child->last_name . '</span><br>
                                <span><b>Date Of Birth : </b>' . $child->dob . '</span><br>
                                <span><b>Gender : </b>' . $child->gender . '</span><br>
                                <span><b>Age : </b>' . $child->age . '</span><br>
                                <span><b>Class : </b>' . (!empty($child->class) ? $child->class->class_level : '') . '</span><br>
                            </p>
                        </div>
                        <div class="col-md-5 col-12">
                            <h6>Parent Detail</h6>
                            <p>
                                <span><b>Name : </b>' . $parent->first_name . ' ' . $parent->last_name . '</span><br>
                                <span><b>Email : </b>' . $parent->email . '</span><br>
                                <span><b>Contact Number : </b>' . $parent->contact_number . '</span><br>
                                <span><b>Address : </b>' . $parent->address . '</span><br>
                            </p>
                        </div>
                        <div class="col-md-2 ">
                            <a href="#" id="remove" class="text-sm"> Remove</a>
                        </div>
                        <div class="col-md-6 col-12">
                            <h6>Nutrition Detail</h6>
                            <p>
                                ' . $nutritionDetail . '
                            </p>
                        </div>
                    </div>';

            return response()->json(['status' => 'success', 'html' => $html, 'tableData' => $tableData, 'items' => $items]);
        }
    }
    public function payinvoice($invoice_id)
    {
        if (!empty($invoice_id)) {
            try {
                $id = \Illuminate\Support\Facades\Crypt::decrypt($invoice_id);
            } catch (\Throwable $th) {
                return redirect('login');
            }

            $invoice = Invoice::where('id', $id)->first();
            if (!is_null($invoice)) {
                $items         = [];
                $totalTaxPrice = 0;
                $totalQuantity = 0;
                $totalRate     = 0;
                $totalDiscount = 0;
                $taxesData     = [];

                foreach ($invoice->items as $item) {
                    $totalQuantity += $item->quantity;
                    $totalRate     += $item->price;
                    $totalDiscount += $item->discount;
                    $taxes         = Invoice::tax($item->tax);
                    $itemTaxes = [];
                    foreach ($taxes as $tax) {
                        if (!empty($tax)) {
                            $taxPrice            = Invoice::taxRate($tax->rate, $item->price, $item->quantity, $item->discount);
                            $totalTaxPrice       += $taxPrice;
                            $itemTax['tax_name'] = $tax->tax_name;
                            $itemTax['tax']      = $tax->rate . '%';
                            $itemTax['price']    = currency_format_with_sym($taxPrice, $invoice->created_by);
                            $itemTaxes[]         = $itemTax;

                            if (array_key_exists($tax->name, $taxesData)) {
                                $taxesData[$itemTax['tax_name']] = $taxesData[$tax->tax_name] + $taxPrice;
                            } else {
                                $taxesData[$tax->tax_name] = $taxPrice;
                            }
                        } else {
                            $taxPrice            = Invoice::taxRate(0, $item->price, $item->quantity, $item->discount);
                            $totalTaxPrice       += $taxPrice;
                            $itemTax['tax_name'] = 'No Tax';
                            $itemTax['tax']      = '';
                            $itemTax['price']    = currency_format_with_sym($taxPrice, $invoice->created_by);
                            $itemTaxes[]         = $itemTax;
                            if (!empty($tax)) {
                                if ($invoice->invoice_module != 'childcare' &&  $invoice->invoice_module != 'Fleet') {
                                    if (array_key_exists('No Tax', $taxesData)) {
                                        $taxesData[$tax->tax_name] = $taxesData['No Tax'] + $taxPrice;
                                    } else {
                                        $taxesData['No Tax'] = $taxPrice;
                                    }
                                }
                            }
                        }
                    }

                    $item->itemTax = $itemTaxes;
                    $items[]       = $item;
                }
                $invoice->items         = $items;
                $invoice->totalTaxPrice = $totalTaxPrice;
                $invoice->totalQuantity = $totalQuantity;
                $invoice->totalRate     = $totalRate;
                $invoice->totalDiscount = $totalDiscount;
                $invoice->taxesData     = $taxesData;
                $ownerId = $invoice->created_by;

                $users = User::where('id', $invoice->created_by)->first();

                if (!is_null($users)) {
                    \App::setLocale($users->lang);
                } else {
                    \App::setLocale('en');
                }

                $invoice    = Invoice::where('id', $id)->first();
                $customer = $invoice->customer;
                $iteams   = $invoice->items;

                $company_payment_setting = [];

                if (module_is_active('Account')) {
                    $customer = \Modules\Account\Entities\Customer::where('user_id', $invoice->user_id)->first();
                } else {
                    $customer = $invoice->customer;
                }
                if (module_is_active('CustomField')) {
                    $invoice->customField = \Modules\CustomField\Entities\CustomField::getData($invoice, 'Base', 'Invoice');
                    $customFields             = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', $invoice->workspace)->where('module', '=', 'Base')->where('sub_module', 'Invoice')->get();
                } else {
                    $customFields = null;
                }
                $company_id = $invoice->created_by;
                $workspace_id = $invoice->workspace;
                $mobileCustomer = [];
                if ($invoice->invoice_module == 'mobileservice') {
                    $mobileCustomer = MobileServiceRequest::find($invoice->customer_id);
                }
                $commonCustomer = [];
                if ($invoice->invoice_module == 'Fleet') {
                    $user =  User::find($invoice->user_id);

                    $commonCustomer['name'] = $user->name;
                    $commonCustomer['email'] = $user->email;
                }
                $commonCustomer = [];
                if ($invoice->invoice_module == 'legalcase' || $invoice->invoice_module == 'sales' || $invoice->invoice_module == 'newspaper') {
                    $user = User::where('id', $invoice->user_id)->where('workspace_id', $invoice->workspace)->where('created_by', $invoice->created_by)->first();
                    $commonCustomer['name'] = !empty($user->name) ? $user->name : '';
                    $commonCustomer['email'] = !empty($user->email) ? $user->email : '';
                }
                $childCustomer = [];
                if ($invoice->invoice_module == 'childcare') {
                    $childCustomer['child'] = Child::find($invoice->customer_id);
                    $childCustomer['parent'] = $childCustomer['child']->parent;
                }
                if ($invoice->invoice_module == 'lms') {


                    $store = Store::where('workspace_id', $invoice->workspace)->where('created_by', $invoice->created_by)->first();

                    $customers = Student::where('store_id', $store->id)->where('id', $invoice->customer_id)->first();
                    $commonCustomer['name'] = !empty($customers->name) ? $customers->name : '';
                    $commonCustomer['email'] = !empty($customers->email) ? $customers->email : '';
                }
                if ($invoice->invoice_module == 'RestaurantMenu') {
                    $customers = RestaurantCustomer::where('id', $invoice->customer_id)->first();
                    $commonCustomer['name'] = !empty($customers->first_name) ? $customers->first_name : '';
                    $commonCustomer['email'] = !empty($customers->email) ? $customers->email : '';
                }
                return view('invoice.invoicepay', compact('invoice', 'iteams', 'customer', 'users', 'company_payment_setting', 'customFields', 'company_id', 'workspace_id', 'mobileCustomer', 'commonCustomer', 'childCustomer'));
            } else {
                return abort('404', 'The Link You Followed Has Expired');
            }
        } else {
            return abort('404', 'The Link You Followed Has Expired');
        }
    }

    public function payment($invoice_id)
    {

        if (Auth::user()->isAbleTo('invoice payment create')) {
            $invoice = Invoice::where('id', $invoice_id)->first();

            if (module_is_active('Account')) {
                $accounts   = \Modules\Account\Entities\BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            } else {
                $accounts = [];
            }

            return view('invoice.payment', compact('accounts', 'invoice'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function createPayment(Request $request, $invoice_id)
    {
        if (Auth::user()->isAbleTo('invoice payment create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'date' => 'required',
                    'amount' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $invoicePayment                 = new InvoicePayment();

            if (module_is_active('Account')) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'account_id' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
                $invoicePayment->account_id     = $request->account_id;
            }
            $invoicePayment->invoice_id     = $invoice_id;
            $invoicePayment->date           = $request->date;
            $invoicePayment->amount         = $request->amount;
            $invoicePayment->payment_method = 0;
            $invoicePayment->reference      = $request->reference;
            $invoicePayment->description    = $request->description;
            if (!empty($request->add_receipt)) {
                $fileName = time() . "_" . $request->add_receipt->getClientOriginalName();
                $uplaod = upload_file($request, 'add_receipt', $fileName, 'payment');
                if ($uplaod['flag'] == 1) {
                    $url = $uplaod['url'];
                } else {
                    return redirect()->back()->with('error', $uplaod['msg']);
                }
                $invoicePayment->add_receipt = $url;
            }
            $invoicePayment->save();

            $invoice = Invoice::where('id', $invoice_id)->first();
            $due     = $invoice->getDue();
            $total   = $invoice->getTotal();
            if ($invoice->status == 0) {
                $invoice->send_date = date('Y-m-d');
                $invoice->save();
            }
            if ($due <= 0) {
                $invoice->status = 4;
                $invoice->save();
            } else {
                $invoice->status = 3;
                $invoice->save();
            }
            $invoicePayment->user_id    = $invoice->user_id;
            $invoicePayment->user_type  = 'Customer';
            $invoicePayment->type       = 'Partial';
            $invoicePayment->created_by = Auth::user()->id;
            $invoicePayment->payment_id = $invoicePayment->id;
            $invoicePayment->category   = 'Invoice';
            $invoicePayment->account    = $request->account_id;

            $customer = User::where('id', $invoice->user_id)->first();
            if (module_is_active('Account')) {
                \Modules\Account\Entities\Transaction::addTransaction($invoicePayment);
                $customer_acc =  \Modules\Account\Entities\Customer::where('id', $invoice->customer_id)->first();
                if (!empty($customer_acc)) {
                    $customer = $customer_acc;
                    $account = BankAccount::where(['created_by' => $invoice->created_by, 'workspace' => $invoice->workspace])->select('id')->first();

                    $customerInvoices = ['taskly', 'account', 'cmms', 'cardealership', 'musicinstitute', 'rent'];

                    if (in_array($invoice->invoice_module, $customerInvoices)) {
                        AccountUtility::updateUserBalance('customer', $invoice->customer_id, $invoicePayment->amount, 'credit');
                    }
                    $account_id = $invoicePayment->account_id == 0 ? $account->id : $invoicePayment->account_id;

                    Transfer::bankAccountBalance($account_id, $invoicePayment->amount, 'credit');
                }
            }

            // first parameter request second parameter invoice third parameter payment
            if (module_is_active('DoubleEntry')) {
                $request->merge(['id' => $invoicePayment->id]);
            }
            event(new CreatePaymentInvoice($request, $invoice, $invoicePayment));

            //Email notification
            if (!empty(company_setting('Invoice Payment Create')) && company_setting('Invoice Payment Create')  == true) {
                $uArr = [
                    'payment_name' => $customer['name'] ?? 'Customer',
                    'payment_amount' => currency_format_with_sym($request->amount),
                    'invoice_number' => 'invoice ' . Invoice::invoiceNumberFormat($invoice->invoice_id),
                    'payment_date' => company_date_formate($request->date),
                    'payment_dueAmount' => currency_format_with_sym($invoice->getDue())
                ];

                try {
                    $resp = EmailTemplate::sendEmailTemplate('Invoice Payment Create', [$customer->id => $customer->email], $uArr);
                } catch (\Exception $e) {
                    $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
                }
            }
            return redirect()->back()->with('success', __('Payment successfully added.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        }
    }

    public function paymentDestroy($invoice_id, $payment_id)
    {
        if (Auth::user()->isAbleTo('invoice payment delete')) {
            $payment = InvoicePayment::find($payment_id);
            if (!empty($payment->add_receipt)) {
                try {
                    delete_file($payment->add_receipt);
                } catch (\Exception $e) {
                }
            }
            $invoice = Invoice::where('id', $invoice_id)->first();
            $due     = $invoice->getDue();
            $total   = $invoice->getTotal();

            if ($due > 0 && $total != $due) {
                $invoice->status = 3;
            } else {
                $invoice->status = 2;
            }

            $invoice->save();

            if (module_is_active('Account')) {
                $type = 'Partial';
                $user = 'Customer';

                \Modules\Account\Entities\Transaction::destroyTransaction($payment_id, $type, $user);

                $account = BankAccount::where(['created_by' => $invoice->created_by, 'workspace' => $invoice->workspace])->select('id')->first();

                $customerInvoices = ['taskly', 'account', 'cmms', 'cardealership', 'musicinstitute', 'rent'];

                if (in_array($invoice->invoice_module, $customerInvoices)) {
                    AccountUtility::updateUserBalance('customer', $invoice->customer_id, $payment->amount, 'debit');
                }
                $account_id = $payment->account_id == 0 ? $account->id : $payment->account_id;
                Transfer::bankAccountBalance($account_id, $payment->amount, 'debit');
            }
            // first parameter invoice second parameter payment
            event(new PaymentDestroyInvoice($invoice, $payment));

            $payment->delete();
            return redirect()->back()->with('success', __('Payment successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    function invoiceNumber()
    {
        $latest = company_setting('invoice_starting_number');
        if ($latest == null) {
            return 1;
        } else {
            return $latest;
        }
    }

    public function destroy(Invoice $invoice)
    {
        if (Auth::user()->isAbleTo('invoice delete')) {
            if ($invoice->workspace == getActiveWorkSpace()) {
                if (module_is_active('Account')) {

                    foreach ($invoice->payments as $invoices) {
                        if (!empty($invoices->add_receipt)) {
                            try {
                                delete_file($invoices->add_receipt);
                            } catch (\Exception $e) {
                            }
                        }
                        $account = BankAccount::where(['created_by' => $invoice->created_by, 'workspace' => $invoice->workspace])->select('id')->first();
                        $account_id = $invoices->account_id == 0 ? $account->id : $invoices->account_id;
                        Transfer::bankAccountBalance($account_id, $invoices->amount, 'debit');
                        $invoices->delete();
                    }
                    if (!empty($invoice->user_id) && $invoice->user_id != 0) {
                        $customerInvoices = ['taskly', 'account', 'cmms', 'cardealership', 'musicinstitute', 'rent'];
                        $customer = Customer::where('user_id', $invoice->user_id)->where('workspace', getActiveWorkSpace())->first();
                        if (in_array($invoice->invoice_module, $customerInvoices) && !empty($customer)) {

                            AccountUtility::updateUserBalance('customer', $customer->id, $invoice->getTotal(), 'debit');
                        }
                    }
                }
                $proposal = Proposal::where('converted_invoice_id', $invoice->id)->first();
                if (!empty($proposal)) {
                    $proposal->converted_invoice_id = Null;
                    $proposal->is_convert           = 0;
                    $proposal->save();
                }

                // change ProductService qty
                $invoiceProduct = InvoiceProduct::where('invoice_id', '=', $invoice->id)->get();
                foreach ($invoiceProduct as $key => $value) {

                    if (module_is_active('ProductService')) {
                        Invoice::total_quantity('plus', $value->quantity, $value->product_id);
                    }
                    $value->delete();
                }


                if (module_is_active('CustomField')) {
                    $customFields = \Modules\CustomField\Entities\CustomField::where('module', 'Base')->where('sub_module', 'Invoice')->get();
                    foreach ($customFields as $customField) {
                        $value = \Modules\CustomField\Entities\CustomFieldValue::where('record_id', '=', $invoice->id)->where('field_id', $customField->id)->first();
                        if (!empty($value)) {
                            $value->delete();
                        }
                    }
                }


                // first parameter invoice
                event(new DestroyInvoice($invoice));
                $invoice->delete();

                return redirect()->route('invoice.index')->with('success', __('Invoice successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function InvoiceSectionGet(Request $request)
    {
        $type = $request->type;
        $acction = $request->acction;
        $invoice = [];
        if ($acction == 'edit') {
            $invoice = Invoice::find($request->invoice_id);
        }

        if (($type == "product" || $type == "salesagent") && module_is_active('Account')) {
            $product_services = \Modules\ProductService\Entities\ProductService::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
            $product_services_count = $product_services->count();
            if ($acction != 'edit') {
                $product_services->prepend('--', '');
            }
            $product_type = ProductService::$product_type;
            $returnHTML = view('account::invoice.main', compact('product_services', 'type', 'acction', 'invoice', 'product_services_count', 'product_type'))->render();
            $response = [
                'is_success' => true,
                'message' => '',
                'html' => $returnHTML,
            ];
            return response()->json($response);
        } elseif ($type == "project" && module_is_active('Taskly')) {

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
            $returnHTML = view('taskly::invoice.main', compact('tasks', 'type', 'acction', 'invoice'))->render();
            $response = [
                'is_success' => true,
                'message' => '',
                'html' => $returnHTML,
            ];
            return response()->json($response);
        } elseif ($type == "parts" && module_is_active('CMMS')) {
            $product_services = \Modules\ProductService\Entities\ProductService::where('workspace_id', getActiveWorkSpace())->where('type', 'parts')->get()->pluck('name', 'id');
            $product_services_count = $product_services->count();
            if ($acction != 'edit') {
                $product_services->prepend('--', '');
            }

            if (module_is_active('CMMS')) {
                $product_type['parts'] = 'Parts';
            }
            $returnHTML = view('cmms::invoice.main', compact('product_services', 'type', 'acction', 'invoice', 'product_services_count', 'product_type'))->render();
            $response = [
                'is_success' => true,
                'message' => '',
                'html' => $returnHTML,
            ];
            return response()->json($response);
        } elseif ($type == "rent" && module_is_active('RentalManagement')) {
            $product_services = \Modules\ProductService\Entities\ProductService::where('workspace_id', getActiveWorkSpace())->where('type', 'rent')->get()->pluck('name', 'id');
            $product_services_count = $product_services->count();
            if ($acction != 'edit') {
                $product_services->prepend('--', '');
            }

            if (module_is_active('RentalManagement')) {
                $product_type['rent'] = 'Rent';
            }
            $returnHTML = view('rentalmanagement::invoice.main', compact('product_services', 'type', 'acction', 'invoice', 'product_services_count', 'product_type'))->render();
            $response = [
                'is_success' => true,
                'message' => '',
                'html' => $returnHTML,
            ];
            return response()->json($response);
        } elseif ($type == "course" && module_is_active('LMS')) {
            $courseorder = '';
            $courseorder = \Modules\LMS\Entities\CourseOrderSummary::where('status', 'Unpaid')->where('id', $request->course_order)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->first();
            $course = \Modules\LMS\Entities\Course::where('workspace_id', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('title', 'id');
            $returnHTML = view('lms::invoice.main', compact('courseorder', 'type', 'acction', 'invoice', 'course'))->render();
            $response = [
                'is_success' => true,
                'message' => '',
                'html' => $returnHTML,
                'order' => !empty($courseorder->course) ? $courseorder->course : '',
            ];

            return response()->json($response);
        } elseif ($type == "case" && module_is_active('LegalCaseManagement')) {
            $taxes = [];
            if (module_is_active('ProductService')) {
                $taxes = \Modules\ProductService\Entities\Tax::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
            }
            $taxes->prepend("Select Tax", '');

            $returnHTML = view('legalcasemanagement::invoice.main', compact('taxes', 'acction', 'invoice'))->render();
            $response = [
                'is_success' => true,
                'message' => '',
                'html' => $returnHTML,
            ];

            return response()->json($response);
        } elseif ($type == "sales" && module_is_active('Sales')) {
            $taxes = [];
            if (module_is_active('ProductService')) {
                $taxes = \Modules\ProductService\Entities\Tax::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
            }
            $taxes->prepend("Select Tax", '');
            $items = \Modules\ProductService\Entities\ProductService::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
            $items->prepend('select', '');
            $returnHTML = view('sales::invoice.main', compact('taxes', 'acction', 'invoice', 'items', 'type'))->render();
            $response = [
                'is_success' => true,
                'message' => '',
                'html' => $returnHTML,
            ];

            return response()->json($response);
        } elseif ($type == "newspaper" && module_is_active('Newspaper')) {

            $newspapers = Newspaper::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get();
            $returnHTML = view('newspaper::invoice.main', compact('acction', 'invoice', 'type', 'newspapers'))->render();
            $response = [
                'is_success' => true,
                'message' => '',
                'html' => $returnHTML,
            ];

            return response()->json($response);
        } elseif ($type == "childcare" && module_is_active('ChildcareManagement')) {

            $children = Child::where('workspace', '=', getActiveWorkSpace())->where('created_by', '=', creatorId())->with('parent')->get()->pluck('first_name', 'id');
            $children->prepend("Select Child", "");

            $returnHTML = view('childcaremanagement::invoice.main', compact('acction', 'invoice', 'type', 'children'))->render();
            $response = [
                'is_success' => true,
                'message' => '',
                'html' => $returnHTML,
            ];

            return response()->json($response);
        } elseif ($type == "mobileservice" && module_is_active('MobileServiceManagement')) {

            $getAllParts = ProductService::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 'parts')->get();

            $returnHTML = view('mobileservicemanagement::invoice.main', compact('acction', 'invoice', 'type', 'getAllParts'))->render();
            $response = [
                'is_success' => true,
                'message' => '',
                'html' => $returnHTML,
            ];

            return response()->json($response);
        } elseif ($type == "vehicleinspection" && module_is_active('VehicleInspectionManagement')) {

            $product_services = \Modules\ProductService\Entities\ProductService::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
            $product_services_count = $product_services->count();
            if ($acction != 'edit') {
                $product_services->prepend('--', '');
            }
            $product_type = \Modules\ProductService\Entities\ProductService::$product_type;

            $returnHTML = view('vehicleinspectionmanagement::invoice.main', compact('product_services', 'acction', 'invoice', 'product_services_count', 'product_type'))->render();
            $response = [
                'is_success' => true,
                'message' => '',
                'html' => $returnHTML,
            ];
            return response()->json($response);
        } elseif ($type == "machinerepair" && module_is_active('MachineRepairManagement')) {

            $product_services = \Modules\ProductService\Entities\ProductService::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
            $product_services_count = $product_services->count();
            if ($acction != 'edit') {
                $product_services->prepend('--', '');
            }
            $product_type = \Modules\ProductService\Entities\ProductService::$product_type;
            $returnHTML = view('machinerepairmanagement::invoice.main', compact('product_services', 'acction', 'invoice', 'product_services_count', 'product_type'))->render();
            $response = [
                'is_success' => true,
                'message' => '',
                'html' => $returnHTML,
            ];
            return response()->json($response);
        } elseif ($type == "cardealership" && module_is_active('CarDealership')) {

            $dealershipProducts = DealershipProduct::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
            $dealershipProducts_count = $dealershipProducts->count();
            $dealershipProducts->prepend('--', '');

            $returnHTML = view('cardealership::invoice.main', compact('dealershipProducts', 'dealershipProducts_count', 'acction', 'invoice'))->render();
            $response = [
                'is_success' => true,
                'message' => '',
                'html' => $returnHTML,
            ];
            return response()->json($response);
        } elseif ($type == "musicinstitute" && module_is_active('MusicInstitute')) {

            $product_services = \Modules\ProductService\Entities\ProductService::where('workspace_id', getActiveWorkSpace())->where('type', 'music institute')->get()->pluck('name', 'id');
            $product_services_count = $product_services->count();
            if ($acction != 'edit') {
                $product_services->prepend('--', '');
            }

            if (module_is_active('MusicInstitute')) {
                $product_type['music institute'] = 'Music Institute';
            }
            $returnHTML = view('musicinstitute::invoice.main', compact('product_services', 'type', 'acction', 'invoice', 'product_services_count', 'product_type'))->render();
            $response = [
                'is_success' => true,
                'message' => '',
                'html' => $returnHTML,
            ];
            return response()->json($response);
        } elseif ($type == "restaurantmenu" && module_is_active('RestaurantMenu')) {
            $product_services = \Modules\ProductService\Entities\ProductService::where('workspace_id', getActiveWorkSpace())->where('type', 'restaurantmenu')->get()->pluck('name', 'id');
            $restaurantOrder = RestaurantOrder::where('status', '3')->where('id', $request->course_order)->where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->first();
            $transformedData = [];
            if (!empty($restaurantOrder->product)) {
                $productData = json_decode($restaurantOrder->product, true);
                $count = 0;
                foreach ($productData['products'] as $key => $value) {
                    $transformedData[] = $value;
                    $taxs_data = [];
                    $itemTaxPrice = 0;
                    $totalTaxRate = 0;
                    $taxes = explode(',', $value['tax']);
                    if (isset($taxes) && !empty($taxes)) {
                        if (module_is_active('ProductService')) {
                            $taxs_data = Tax::whereIn('id', $taxes)->where('workspace_id', getActiveWorkSpace())->select('id', 'name', 'rate')->get();
                        }
                    }
                    foreach ($taxs_data as $tax) {
                        $totalTaxRate += $tax->rate;
                        $itemTaxPrice += ($value['sales_price'] * $tax->rate) / 100;
                    }
                    $transformedData[$count]['itemTaxPrice'] = $itemTaxPrice;
                    $transformedData[$count]['tax_data'] = json_encode($taxs_data);
                    $transformedData[$count]['itemTaxRate'] = $totalTaxRate;

                    if ($acction == 'edit') {
                        $transformedData[$count]['id'] = $invoice->items[$count]['id'];
                        $transformedData[$count]['item_name'] = $invoice->items[$count]['product_name'];
                        $transformedData[$count]['description'] = $invoice->items[$count]['description'];
                        $transformedData[$count]['sales_price'] = $invoice->items[$count]['price'];
                        $transformedData[$count]['discount'] = $invoice->items[$count]['discount'];
                        $transformedData[$count]['tax'] = $invoice->items[$count]['tax'];
                    }
                    $count++;
                }
            }
            $returnHTML = view('restaurantmenu::invoice.main', compact('restaurantOrder', 'type', 'acction', 'invoice', 'product_services', 'transformedData'))->render();

            $response = [
                'is_success' => true,
                'message' => '',
                'html' => $returnHTML,
            ];

            return response()->json($response);
        } elseif ($type == "fleet" && module_is_active('Fleet')) {

            $fleetProducts = Vehicle::where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');

            $fleet_products_count = $fleetProducts->count();
            if ($acction != 'edit') {
                $fleetProducts->prepend('--', '');
            }
            $product_type['fleet'] = 'Fleet';

            $returnHTML = view('fleet::invoice.main', compact('fleetProducts', 'type', 'acction', 'invoice', 'fleet_products_count', 'product_type'))->render();
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
    public function pdf($id)
    {
        try {
            $id       = Crypt::decrypt($id);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Invoice Not Found.'));
        }
        $invoice = Invoice::find($id);
        if ($invoice) {
            if ($invoice->workspace == getActiveWorkSpace()) {
                $iteams   = $invoice->items;
                return view('invoice.pdf', compact('invoice', 'iteams'));
            } else {
                return response()->json(['error' => __('Permission denied.')]);
            }
        } else {
            return response()->json(['error' => __('This invoice is deleted.')]);
        }
    }

    public function invoiceAttechment(Request $request, $id)
    {
        $invoice = Invoice::find($id);
        $file_name = time() . "_" . $request->file->getClientOriginalName();

        $upload = upload_file($request, 'file', $file_name, 'invoice_attachment', []);

        $fileSizeInBytes = \File::size($upload['url']);
        $fileSizeInKB = round($fileSizeInBytes / 1024, 2);

        if ($fileSizeInKB < 1024) {
            $fileSizeFormatted = $fileSizeInKB . " KB";
        } else {
            $fileSizeInMB = round($fileSizeInKB / 1024, 2);
            $fileSizeFormatted = $fileSizeInMB . " MB";
        }

        if ($upload['flag'] == 1) {
            $file                 = InvoiceAttechment::create(
                [
                    'invoice_id' => $invoice->id,
                    'file_name' => $file_name,
                    'file_path' => $upload['url'],
                    'file_size' => $fileSizeFormatted,
                ]
            );
            $return               = [];
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

    public function invoiceAttechmentDestroy($id)
    {
        $file = InvoiceAttechment::find($id);

        if (!empty($file->file_path)) {
            delete_file($file->file_path);
        }
        $file->delete();
        return redirect()->back()->with('success', __('File successfully deleted.'));
    }


    public function getInvoiceCustomers(Request $request)
    {
        $customers = [];
        $label = 'Customer';

        if ($request->type == 'SalesAgent') {
            $customers = User::where('workspace_id', getActiveWorkSpace())
                ->leftjoin('customers', 'users.id', '=', 'customers.user_id')
                ->leftjoin('sales_agents', 'users.id', '=', 'sales_agents.user_id')
                ->where('users.type', 'salesagent')
                ->where('users.is_disable', '1')
                ->where('sales_agents.is_agent_active', '1')
                ->get()->pluck('name', 'id');
        }

        if ($request->type == 'Taskly' || $request->type == 'Account' || $request->type == 'RentalManagement' || $request->type == 'CMMS' || $request->type == 'MusicInstitute') {
            $customers = User::where('workspace_id', '=', getActiveWorkSpace())->where('type', 'Client')->get()->pluck('name', 'id');
        }

        if ($request->type == 'LMS') {
            $store = Store::where('workspace_id', getActiveWorkSpace())->where('created_by', creatorId())->first();
            $customers = Student::where('store_id', $store->id)->get()->pluck('name', 'id');
            $label = 'Student';
        }

        if ($request->type == 'LegalCaseManagement') {
            $customers = User::where('workspace_id', '=', getActiveWorkSpace())
                ->where('created_by', '=', creatorId())
                ->where('type', 'case initiator')
                ->get()
                ->pluck('name', 'id');
            $label = 'Case Initiator';
        }

        if ($request->type == 'Sales') {
            $label = 'User';
            $customers = User::where('workspace_id', getActiveWorkSpace())->emp()->get()->pluck('name', 'id');
        }
        if ($request->type == 'Newspaper') {
            $label = 'Agent';
            $customers = User::where('created_by', creatorId())->where('active_workspace', getActiveWorkSpace())->where('type', 'agent')->pluck('name', 'id');
        }
        if ($request->type == 'ChildcareManagement') {
            $label = 'Child';
            $customers = Child::where('workspace', '=', getActiveWorkSpace())->where('created_by', '=', creatorId())->with('parent')->get()->pluck('first_name', 'id');
        }
        if ($request->type == 'MobileServiceManagement') {
            $label = 'Services';
            $customers = MobileServiceRequest::where('is_approve', 1)
                ->where('workspace_id', getActiveWorkSpace())
                ->where('is_technician_asign', 1)
                ->pluck('service_id', 'id');
        }
        if ($request->type == 'VehicleInspectionManagement') {
            $label = 'Inspection Request';
        }
        if ($request->type == 'MachineRepairManagement') {
            $label = 'Repair Request';
        }
        if ($request->type == 'CarDealership' ||  $request->type == 'Fleet') {
            $label = 'Customer';
            $customers =  User::where('type', 'client')->where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
        }
        if ($request->type == 'RestaurantMenu') {
            $customers =  RestaurantCustomer::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('first_name', 'id');
        }

        return response()->json([
            'customers' => $customers,
            'label' => $label,
        ]);
    }

    private function storeProductInvoice($request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'issue_date' => 'required',
                'due_date' => 'required',
                'category_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        foreach ($request->items as $item) {
            if (empty($item['item']) && $item['item'] == 0) {
                return redirect()->back()->with('error', __('Please select an item'));
            }
        }

        $status = Invoice::$statues;
        $invoice                 = new Invoice();

        $customer = \Modules\Account\Entities\Customer::where('user_id', '=', $request->customer_id)->first();
        if (empty($customer)) {
            $user = User::find($request->customer_id);
        }
        $invoice->customer_id    = !empty($customer) ?  $customer->id : null;

        $invoice->invoice_id     = $this->invoiceNumber();
        $invoice->user_id        = $customer->user_id ?? $user->id;
        $invoice->status         = 0;
        $invoice->invoice_module = 'account';
        $invoice->account_type     = $request->account_type;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->category_id    = $request->category_id;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();

        $invoice->save();
        $products = $request->items;

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');
        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }

        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct                 = new InvoiceProduct();
            $invoiceProduct->invoice_id     = $invoice->id;
            $invoiceProduct->product_type   = $products[$i]['product_type'];
            $invoiceProduct->product_id     = $products[$i]['item'];
            $invoiceProduct->quantity       = $products[$i]['quantity'];
            $invoiceProduct->tax            = $products[$i]['tax'];
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->price          = $products[$i]['price'];
            $invoiceProduct->description    = str_replace(array('\'', '"', '`', '{', "\n"), ' ', $products[$i]['description']);
            $invoiceProduct->save();

            if (module_is_active('ProductService')) {
                Invoice::total_quantity('minus', $invoiceProduct->quantity, $invoiceProduct->product_id);
            }

            if (module_is_active('Account')) {
                //Product Stock Report
                $type = 'invoice';
                $type_id = $invoice->id;
                \Modules\Account\Entities\StockReport::where('type', '=', 'invoice')->where('type_id', '=', $invoice->id)->delete();
                $description = $invoiceProduct->quantity . '  ' . __(' quantity sold in invoice') . ' ' . Invoice::invoiceNumberFormat($invoice->invoice_id);
                \Modules\Account\Entities\AccountUtility::addProductStock($products[$i]['item'], $invoiceProduct->quantity, $type, $description, $type_id);
            }
        }

        event(new CreateInvoice($request, $invoice));


        if (isset($request->agentPurchaseOrderId)) {

            return redirect()->route('salesagents.purchase.order.show', \Crypt::encrypt($request->agentPurchaseOrderId))->with('success', __('Invoice successfully created.'));
        }

        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully created.'));
    }

    private function storeProjectInvoice($request)
    {

        $validator = \Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'issue_date' => 'required',
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

        $invoice                 = new Invoice();

        $customer = \Modules\Account\Entities\Customer::where('user_id', '=', $request->customer_id)->first();
        if (empty($customer)) {
            $user = User::find($request->customer_id);
        }
        $invoice->customer_id    = !empty($customer) ?  $customer->id : null;

        $status = Invoice::$statues;
        $invoice->invoice_id     = $this->invoiceNumber();
        $invoice->user_id        = $customer->user_id ?? $user->id;
        $invoice->status         = 0;
        $invoice->account_type     = $request->account_type;
        $invoice->invoice_module   = 'taskly';
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->category_id    = $request->project;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();

        $invoice->save();

        $products = $request->items;
        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');

        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }
        $project_tax = implode(',', $request->tax_project);

        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct                 = new InvoiceProduct();
            $invoiceProduct->invoice_id     = $invoice->id;
            $invoiceProduct->product_id     = $products[$i]['product_id'];
            $invoiceProduct->quantity       = 1;
            $invoiceProduct->tax            = $project_tax;
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->price          = $products[$i]['price'];
            $invoiceProduct->description    = $products[$i]['description'];
            $invoiceProduct->save();
        }

        // first parameter request second parameter invoice
        event(new CreateInvoice($request, $invoice));

        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully created.'));
    }

    private function storePartsInvoice($request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'work_order' => 'required',
                'issue_date' => 'required',
                'due_date' => 'required',
                'items' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $status = Invoice::$statues;
        $invoice                 = new Invoice();
        $customer = \Modules\Account\Entities\Customer::where('user_id', '=', $request->customer_id)->first();
        if (empty($customer)) {
            $user = User::find($request->customer_id);
        }
        $invoice->customer_id    = !empty($customer) ?  $customer->id : null;

        $invoice->invoice_id     = $this->invoiceNumber();
        $invoice->user_id        = $customer->user_id ?? $user->id;
        $invoice->status         = 0;
        $invoice->invoice_module = 'cmms';
        $invoice->account_type     = $request->account_type;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->category_id    = $request->work_order;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();

        $invoice->save();
        $products = $request->items;

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');
        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }

        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct                 = new InvoiceProduct();
            $invoiceProduct->invoice_id     = $invoice->id;
            $invoiceProduct->product_type   = $products[$i]['product_type'];
            $invoiceProduct->product_id     = $products[$i]['product_id'];
            $invoiceProduct->quantity       = $products[$i]['quantity'];
            $invoiceProduct->tax            = $products[$i]['tax'];
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->price          = $products[$i]['price'];
            $invoiceProduct->description    = str_replace(array('\'', '"', '`', '{', "\n"), ' ', $products[$i]['description']);
            $invoiceProduct->save();

            if (module_is_active('ProductService')) {
                Invoice::total_quantity('minus', $invoiceProduct->quantity, $invoiceProduct->product_id);
            }

            if (module_is_active('Account')) {
                //Product Stock Report
                $type = 'invoice';
                $type_id = $invoice->id;
                \Modules\Account\Entities\StockReport::where('type', '=', 'invoice')->where('type_id', '=', $invoice->id)->delete();
                $description = $invoiceProduct->quantity . '  ' . __(' quantity sold in invoice') . ' ' . Invoice::invoiceNumberFormat($invoice->invoice_id);
                \Modules\Account\Entities\AccountUtility::addProductStock($products[$i]['product_id'], $invoiceProduct->quantity, $type, $description, $type_id);
            }
        }
        event(new CreateInvoice($request, $invoice));


        if (isset($request->agentPurchaseOrderId)) {

            return redirect()->route('salesagents.purchase.order.show', \Crypt::encrypt($request->agentPurchaseOrderId))->with('success', __('Invoice successfully created.'));
        }

        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully created.'));
    }

    private function storeRentInvoice($request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'rent_type' => 'required',
                'issue_date' => 'required',
                'due_date' => 'required',
                'items' => 'required',
                'customer_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        foreach ($request->items as $item) {
            if (empty($item['item']) && $item['item'] == 0) {
                return redirect()->back()->with('error', __('Please select an item'));
            }
        }
        $status = Invoice::$statues;

        $invoice                 = new Invoice();
        $customer = \Modules\Account\Entities\Customer::where('user_id', '=', $request->customer_id)->first();
        if (empty($customer)) {
            $user = User::find($request->customer_id);
        }
        $invoice->customer_id    = !empty($customer) ?  $customer->id : null;
        $invoice->invoice_id     = $this->invoiceNumber();
        $invoice->user_id        = $customer->user_id ?? $user->id;
        $invoice->status         = 0;
        $invoice->invoice_module = 'rent';
        $invoice->account_type     = $request->account_type;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->category_id    = $request->rent_type;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();

        $invoice->save();
        $products = $request->items;

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');
        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }

        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct                 = new InvoiceProduct();
            $invoiceProduct->invoice_id     = $invoice->id;
            $invoiceProduct->product_type   = $products[$i]['product_type'];
            $invoiceProduct->product_id     = $products[$i]['item'];
            $invoiceProduct->quantity       = $products[$i]['quantity'];
            $invoiceProduct->tax            = $products[$i]['tax'];
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->price          = $products[$i]['price'];
            $invoiceProduct->description    = str_replace(array('\'', '"', '`', '{', "\n"), ' ', $products[$i]['description']);
            $invoiceProduct->save();

            if (module_is_active('ProductService')) {
                Invoice::total_quantity('minus', $invoiceProduct->quantity, $invoiceProduct->product_id);
            }

            if (module_is_active('Account')) {
                //Product Stock Report
                $type = 'invoice';
                $type_id = $invoice->id;
                \Modules\Account\Entities\StockReport::where('type', '=', 'invoice')->where('type_id', '=', $invoice->id)->delete();
                $description = $invoiceProduct->quantity . '  ' . __(' quantity sold in invoice') . ' ' . Invoice::invoiceNumberFormat($invoice->invoice_id);
                \Modules\Account\Entities\AccountUtility::addProductStock($products[$i]['item'], $invoiceProduct->quantity, $type, $description, $type_id);
            }
        }
        event(new CreateInvoice($request, $invoice));


        if (isset($request->agentPurchaseOrderId)) {

            return redirect()->route('salesagents.purchase.order.show', \Crypt::encrypt($request->agentPurchaseOrderId))->with('success', __('Invoice successfully created.'));
        }

        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully created.'));
    }

    private function storeCourseInvoice($request)
    {

        $validator = \Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'issue_date' => 'required',
                'due_date' => 'required',
                'course_order' => 'required',
                'tax_project' => 'required',
                'items' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $status = Invoice::$statues;
        $invoice                 = new Invoice();
        $invoice->invoice_id     = $this->invoiceNumber();
        $invoice->user_id        = $request->customer_id;
        $invoice->customer_id    = $request->customer_id;
        $invoice->status         = 0;
        $invoice->account_type   = $request->account_type;
        $invoice->invoice_module = 'lms';
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->category_id    = $request->course_order;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();

        $invoice->save();

        $products = $request->items;

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');

        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }
        $project_tax = implode(',', $request->tax_project);

        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct                 = new InvoiceProduct();
            $invoiceProduct->invoice_id     = $invoice->id;
            $invoiceProduct->product_id     = $products[$i]['product_id'];
            $invoiceProduct->quantity       = 1;
            $invoiceProduct->tax            = $project_tax;
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->price          = $products[$i]['price'];
            $invoiceProduct->description    = $products[$i]['description'];
            $invoiceProduct->save();
        }

        // first parameter request second parameter invoice
        event(new CreateInvoice($request, $invoice));

        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully created.'));
    }

    private function storeCaseInvoice($request)
    {

        $validator = \Validator::make(
            $request->all(),
            [
                'issue_date' => 'required',
                'due_date' => 'required',
                'items' => 'required',
                'customer_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $status = Invoice::$statues;
        $invoice                    = new Invoice();
        $invoice->invoice_id        = $this->invoiceNumber();
        $invoice->user_id           = $request->customer_id;
        $invoice->status            = 0;
        $invoice->account_type      = $request->account_type;
        $invoice->invoice_module    = 'legalcase';
        $invoice->issue_date        = $request->issue_date;
        $invoice->due_date          = $request->due_date;

        $invoice->invoice_template  = $request->invoice_template;
        $invoice->workspace         = getActiveWorkSpace();
        $invoice->created_by        = creatorId();

        $invoice->save();

        $products = $request->items;

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');

        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }

        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct                 = new InvoiceProduct();
            $invoiceProduct->invoice_id     = $invoice->id;
            $invoiceProduct->product_name     = $products[$i]['product_name'];
            $invoiceProduct->quantity       = $products[$i]['quantity'];
            $invoiceProduct->price          = $products[$i]['price'];
            $invoiceProduct->tax            = $products[$i]['tax'];
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->description    = $products[$i]['description'];
            $invoiceProduct->save();
        }

        // first parameter request second parameter invoice
        event(new CreateInvoice($request, $invoice));

        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully created.'));
    }

    private function storeSalesInvoice($request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'issue_date' => 'required',
                'due_date' => 'required',
                'sale_invoice' => 'required',
                'items' => 'required',

            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        if ($request->sale_invoice == 'Please Select') {
            return redirect()->back()->with('error', __('Please select sales invoice field'));
        }
        $status = Invoice::$statues;
        $invoice                 = new Invoice();
        if (module_is_active('Account')) {
            $customer = \Modules\Account\Entities\Customer::where('user_id', '=', $request->customer_id)->first();
            $invoice->customer_id    = !empty($customer) ?  $customer->id : null;
        } else if (module_is_active('SalesAgent')) {
            $customer = \Modules\SalesAgent\Entities\Customer::where('user_id', '=', $request->customer_id)->first();
            $invoice->customer_id    = !empty($customer) ?  $customer->id : null;
        }
        if (empty($customer)) {
            $user = User::find($request->customer_id);
        }
        $invoice->invoice_id     = $this->invoiceNumber();
        $invoice->user_id        = $request->customer_id ?? $user->id;
        $invoice->status         = 0;
        $invoice->invoice_module = 'sales';
        $invoice->account_type   = $request->account_type;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->category_id    = $request->sale_invoice;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();

        $invoice->save();
        $products = $request->items;

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');
        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }

        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct                 = new InvoiceProduct();
            $invoiceProduct->invoice_id     = $invoice->id;
            $invoiceProduct->product_id     = $products[$i]['product_id'];
            $invoiceProduct->quantity       = $products[$i]['quantity'];
            $invoiceProduct->tax            = $products[$i]['tax'];
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->price          = $products[$i]['price'];
            $invoiceProduct->description    = str_replace(array('\'', '"', '`', '{', "\n"), ' ', $products[$i]['description']);
            $invoiceProduct->save();

            if (module_is_active('ProductService')) {
                Invoice::total_quantity('minus', $invoiceProduct->quantity, $invoiceProduct->product_id);
            }
        }

        event(new CreateInvoice($request, $invoice));

        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully created.'));
    }

    private function storeNewsPaperInvoice($request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'issue_date' => 'required',
                'due_date' => 'required',
                'items' => 'required',

            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $status = Invoice::$statues;
        $invoice                 = new Invoice();

        if (module_is_active('Account')) {
            $customer = \Modules\Account\Entities\Customer::where('user_id', '=', $request->customer_id)->first();
            $invoice->customer_id    = !empty($customer) ?  $customer->id : null;
        } else if (module_is_active('SalesAgent')) {
            $customer = \Modules\SalesAgent\Entities\Customer::where('user_id', '=', $request->customer_id)->first();
            $invoice->customer_id    = !empty($customer) ?  $customer->id : null;
        }


        $invoice->invoice_id     = $this->invoiceNumber();
        $invoice->user_id        = $request->customer_id;
        $invoice->status         = 0;
        $invoice->invoice_module = 'newspaper';
        $invoice->account_type   = $request->account_type;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();

        $invoice->save();
        $products = $request->items;

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');
        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }

        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct                 = new InvoiceProduct();
            $invoiceProduct->invoice_id     = $invoice->id;
            $invoiceProduct->product_id     = $products[$i]['product_id'];
            $invoiceProduct->quantity       = $products[$i]['quantity'];
            $invoiceProduct->tax            = isset($products[$i]['tax_id']) ? $products[$i]['tax_id'] : 0;
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->price          = $products[$i]['price'];
            $invoiceProduct->save();
        }

        event(new CreateInvoice($request, $invoice));

        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully created.'));
    }
    private function storeChildInvoice($request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'issue_date' => 'required',
                'due_date' => 'required',
                'items.*.amount' => 'required|numeric|gt:0',

            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $status = Invoice::$statues;
        $invoice                 = new Invoice();
        $invoice->invoice_id     = $this->invoiceNumber();
        $invoice->user_id        = $request->customer_id;
        $invoice->customer_id        = $request->customer_id;
        $invoice->status         = 0;
        $invoice->invoice_module = 'childcare';
        $invoice->account_type   = $request->account_type;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();

        $invoice->save();
        $products = $request->items;

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');
        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }
        if (!empty($products)) {
            for ($i = 0; $i < count($products); $i++) {
                $invoiceProduct                 = new InvoiceProduct();
                $invoiceProduct->invoice_id     = $invoice->id;
                $invoiceProduct->product_name   = $products[$i]['name'];
                $invoiceProduct->price          = $products[$i]['amount'];
                $invoiceProduct->description          = $products[$i]['notes'] ?? '';
                $invoiceProduct->tax            = 0;

                $invoiceProduct->save();
            }
        } else {
            return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully created.'));
        }
        event(new CreateInvoice($request, $invoice));

        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully created.'));
    }
    private function storeMobileInvoice($request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'issue_date' => 'required',
                'due_date' => 'required',
                'items' => 'required',

            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $status = Invoice::$statues;
        $invoice                 = new Invoice();
        $invoice->invoice_id     = $this->invoiceNumber();
        $invoice->user_id        = $request->customer_id;
        $invoice->customer_id        = $request->customer_id;
        $invoice->status         = 0;
        $invoice->invoice_module = 'mobileservice';
        $invoice->account_type   = $request->account_type;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->category_id    = $request->repair_charge;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();

        $invoice->save();
        $products = $request->items;

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');
        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }

        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct                 = new InvoiceProduct();
            $invoiceProduct->invoice_id     = $invoice->id;
            $invoiceProduct->product_id     = $products[$i]['product_id'];
            $invoiceProduct->quantity       = $products[$i]['quantity'];
            $invoiceProduct->price          = $products[$i]['price'];
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->tax            = $products[$i]['tax'];
            $invoiceProduct->description    = str_replace(array('\'', '"', '`', '{', "\n"), ' ', $products[$i]['description']);
            $invoiceProduct->save();

            $updateServiceRequestStatus = MobileServiceRequest::where('id', $request->customer_id)->first();
            $updateServiceRequestStatus->is_parts_added = 1;
            $updateServiceRequestStatus->save();

            if (module_is_active('ProductService')) {
                Invoice::total_quantity('minus', $invoiceProduct->quantity, $invoiceProduct->product_id);
            }
        }
        event(new CreateInvoice($request, $invoice));


        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully created.'));
    }

    private function storeVehicleInvoice($request)
    {

        $validator = \Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'issue_date' => 'required',
                'due_date' => 'required',
                'items' => 'required',
                'service_charge' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        foreach ($request->items as $item) {
            if (empty($item['item']) && $item['item'] == 0) {
                return redirect()->back()->with('error', __('Please select an item'));
            }
        }
        $status = Invoice::$statues;
        $invoice                 = new Invoice();
        $invoice->invoice_id     = $this->invoiceNumber();
        $invoice->user_id        = $request->customer_id;
        $invoice->customer_id        = $request->customer_id;
        $invoice->status         = 0;
        $invoice->invoice_module = 'vehicleinspection';
        $invoice->account_type   = $request->account_type;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->category_id    = $request->service_charge;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();
        $invoice->save();

        $products = $request->items;

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');
        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }

        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct                 = new InvoiceProduct();
            $invoiceProduct->invoice_id     = $invoice->id;
            $invoiceProduct->product_type   = $products[$i]['product_type'];
            $invoiceProduct->product_id     = $products[$i]['item'];
            $invoiceProduct->quantity       = $products[$i]['quantity'];
            $invoiceProduct->tax            = $products[$i]['tax'];
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->price          = $products[$i]['price'];
            $invoiceProduct->description    = str_replace(array('\'', '"', '`', '{', "\n"), ' ', $products[$i]['description']);
            $invoiceProduct->save();

            if (module_is_active('ProductService')) {
                Invoice::total_quantity('minus', $invoiceProduct->quantity, $invoiceProduct->product_id);
            }
        }
        event(new CreateInvoice($request, $invoice));


        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully created.'));
    }

    private function storeMachineInvoice($request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'issue_date' => 'required',
                'due_date' => 'required',
                'items' => 'required',
                'service_charge' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        foreach ($request->items as $item) {
            if (empty($item['item']) && $item['item'] == 0) {
                return redirect()->back()->with('error', __('Please select an item'));
            }
        }
        $status = Invoice::$statues;
        $invoice                 = new Invoice();
        $invoice->invoice_id     = $this->invoiceNumber();
        $invoice->user_id        = $request->customer_id;
        $invoice->customer_id        = $request->customer_id;
        $invoice->status         = 0;
        $invoice->invoice_module = 'machinerepair';
        $invoice->account_type   = $request->account_type;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->category_id    = $request->service_charge;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();

        $invoice->save();
        $products = $request->items;

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');
        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }

        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct                 = new InvoiceProduct();
            $invoiceProduct->invoice_id     = $invoice->id;
            $invoiceProduct->product_type   = $products[$i]['product_type'];
            $invoiceProduct->product_id     = $products[$i]['item'];
            $invoiceProduct->quantity       = $products[$i]['quantity'];
            $invoiceProduct->tax            = $products[$i]['tax'];
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->price          = $products[$i]['price'];
            $invoiceProduct->description    = str_replace(array('\'', '"', '`', '{', "\n"), ' ', $products[$i]['description']);
            $invoiceProduct->save();

            if (module_is_active('ProductService')) {
                MachineInvoice::total_quantity('minus', $invoiceProduct->quantity, $invoiceProduct->product_id);
            }

            $repair_request = MachineRepairRequest::find($request->customer_id);
            $machine = Machine::find($repair_request->machine_id);
            $machine->last_maintenance_date = $request->issue_date;
            $machine->save();


            $repair_request = MachineRepairRequest::find($request->customer_id);
            $repair_request->status = 'Completed';
            $repair_request->save();
        }

        event(new CreateInvoice($request, $invoice));

        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully created.'));
    }

    private function storeCarDealInvoice($request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'issue_date' => 'required',
                'due_date' => 'required',
                'items' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $status = Invoice::$statues;
        $invoice                 = new Invoice();

        $customer = \Modules\Account\Entities\Customer::where('user_id', '=', $request->customer_id)->first();
        $invoice->customer_id    = !empty($customer) ?  $customer->id : null;
        if (empty($customer)) {
            $user = User::find($request->customer_id);
        }
        $invoice->invoice_id     = $this->invoiceNumber();
        $invoice->user_id        = $request->customer_id ?? $user->id;
        $invoice->status         = 0;
        $invoice->invoice_module = 'cardealership';
        $invoice->account_type   = $request->account_type;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();

        $invoice->save();
        $products = $request->items;

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');
        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }

        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct                 = new InvoiceProduct();
            $invoiceProduct->invoice_id     = $invoice->id;
            $invoiceProduct->product_id     = $products[$i]['item'];
            $invoiceProduct->quantity       = $products[$i]['quantity'];
            $invoiceProduct->tax            = $products[$i]['tax'];
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->price          = $products[$i]['price'];
            $invoiceProduct->description    = str_replace(array('\'', '"', '`', '{', "\n"), ' ', $products[$i]['description']);
            $invoiceProduct->save();

            if (!empty($invoiceProduct)) {
                CarDealershipUtility::total_quantity('minus', $invoiceProduct->quantity, $invoiceProduct->product_id);
            }
        }
        event(new CreateInvoice($request, $invoice));


        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully created.'));
    }

    private function storeMusicInvoice($request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'issue_date' => 'required',
                'due_date' => 'required',
                'customer_id' => 'required',
                'student' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        foreach ($request->items as $item) {
            if (empty($item['item']) && $item['item'] == 0) {
                return redirect()->back()->with('error', __('Please select an item'));
            }
        }
        $status = Invoice::$statues;



        $invoice                 = new Invoice();
        $customer = \Modules\Account\Entities\Customer::where('user_id', '=', $request->customer_id)->first();
        if (empty($customer)) {
            $user = User::find($request->customer_id);
        }
        $invoice->customer_id    = !empty($customer) ?  $customer->id : null;
        $invoice->invoice_id     = $this->invoiceNumber();
        $invoice->user_id        = $customer->user_id ?? $user->id;
        $invoice->status         = 0;
        $invoice->invoice_module = 'musicinstitute';
        $invoice->account_type   = $request->account_type;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->category_id    = $request->student;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();

        $invoice->save();
        $products = $request->items;

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');
        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }

        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct                 = new InvoiceProduct();
            $invoiceProduct->invoice_id     = $invoice->id;
            $invoiceProduct->product_type   = $products[$i]['product_type'];
            $invoiceProduct->product_id     = $products[$i]['item'];
            $invoiceProduct->quantity       = $products[$i]['quantity'];
            $invoiceProduct->tax            = $products[$i]['tax'];
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->price          = $products[$i]['price'];
            $invoiceProduct->description    = str_replace(array('\'', '"', '`', '{', "\n"), ' ', $products[$i]['description']);
            $invoiceProduct->save();

            if (module_is_active('ProductService')) {
                Invoice::total_quantity('minus', $invoiceProduct->quantity, $invoiceProduct->product_id);
            }

            if (module_is_active('Account')) {
                //Product Stock Report
                $type = 'invoice';
                $type_id = $invoice->id;
                \Modules\Account\Entities\StockReport::where('type', '=', 'invoice')->where('type_id', '=', $invoice->id)->delete();
                $description = $invoiceProduct->quantity . '  ' . __(' quantity sold in invoice') . ' ' . Invoice::invoiceNumberFormat($invoice->invoice_id);
                \Modules\Account\Entities\AccountUtility::addProductStock($products[$i]['item'], $invoiceProduct->quantity, $type, $description, $type_id);
            }
        }

        event(new CreateInvoice($request, $invoice));

        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully created.'));
    }
    private function storeRestaurantInvoice($request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'issue_date' => 'required',
                'due_date' => 'required',
                'customer_id' => 'required',
                'restaurant_order' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $invoice                 = new Invoice();
        $invoice->customer_id    = $request->customer_id;
        $invoice->invoice_id     = $this->invoiceNumber();
        $invoice->user_id        = $request->customer_id;
        $invoice->status         = 0;
        $invoice->invoice_module = 'RestaurantMenu';
        $invoice->account_type   = $request->account_type;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->category_id    = $request->restaurant_order;
        $invoice->invoice_template   = $request->invoice_template;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = Auth::user()->id;
        $invoice->created_by     = creatorId();

        $invoice->save();

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');
        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }

        $products = $request->items;

        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct                 = new InvoiceProduct();
            $invoiceProduct->invoice_id     = $invoice->id;
            $invoiceProduct->product_name     = $products[$i]['item_name'];
            $invoiceProduct->quantity       = isset($products[$i]['quantity']) ? $products[$i]['quantity'] : '';
            $invoiceProduct->tax            = isset($products[$i]['tax']) ? $products[$i]['tax'] : '';
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->price          = $products[$i]['sales_price'];
            $invoiceProduct->description    = $products[$i]['description'];
            $invoiceProduct->save();

            if (module_is_active('ProductService')) {
                RestaurantInvoice::total_quantity('minus', $invoiceProduct->quantity, $invoiceProduct->product_id);
            }
        }

        event(new CreateInvoice($request, $invoice));

        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully created.'));
    }


    private function storeFleetInvoice($request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                // 'customer_id' => 'required',
                'issue_date' => 'required',
                'due_date' => 'required',
                // 'items' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $status = Invoice::$statues;
        $invoice                 = new Invoice();

        $customer = \Modules\Account\Entities\Customer::where('user_id', '=', $request->customer_id)->first();
        $invoice->customer_id    = !empty($customer) ?  $customer->id : null;
        if (empty($customer)) {
            $user = User::find($request->customer_id);
        }
        $invoice->invoice_id     = $this->invoiceNumber();
        $invoice->user_id        = $request->customer_id ?? $user->id;
        $invoice->status         = 0;
        $invoice->invoice_module = 'Fleet';
        $invoice->account_type   = $request->account_type;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();
        $invoice->save();
        $products = $request->items;

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');
        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }

        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct                 = new InvoiceProduct();
            $invoiceProduct->invoice_id     = $invoice->id;
            $invoiceProduct->product_id     = $products[$i]['product_id'];
            $invoiceProduct->quantity       = '1';
            $invoiceProduct->tax            = null;
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->price          = isset($products[$i]['rate']) ? $products[$i]['rate'] : 0;
            $invoiceProduct->description    = str_replace(array('\'', '"', '`', '{', "\n"), ' ', $products[$i]['description']);
            $invoiceProduct->save();
        }
        event(new CreateInvoice($request, $invoice));


        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully created.'));
    }

    public function UpdateProductInvoice($request, $invoice)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'issue_date' => 'required',
                'due_date' => 'required',
                'category_id' => 'required',
                'items' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->route('invoice.index')->with('error', $messages->first());
        }
        foreach ($request->items as $item) {
            if (empty($item['item']) && $item['item'] == 0) {
                return redirect()->back()->with('error', __('Please select an item'));
            }
        }
        $customer = \Modules\Account\Entities\Customer::where('user_id', '=', $request->customer_id)->first();
        $invoice->customer_id    = !empty($customer) ?  $customer->id : null;
        if (empty($customer)) {
            $user = User::find($request->customer_id);
        }
        if ($request->invoice_type == "product") {
            $request->invoice_type = 'account';
        } else if ($request->invoice_type == "project") {
            $request->invoice_type = 'taskly';
        }
        if ($request->invoice_type != $invoice->invoice_module) {
            InvoiceProduct::where('invoice_id', '=', $invoice->id)->delete();
        }
        $invoice->user_id        = $customer->user_id ?? $user->id;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->account_type     = $request->account_type;
        $invoice->invoice_module = 'account';
        $invoice->category_id    = $request->category_id;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->save();
        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }
        $products = $request->items;

        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct = InvoiceProduct::find($products[$i]['id']);

            if ($invoiceProduct == null) {
                $invoiceProduct             = new InvoiceProduct();
                $invoiceProduct->invoice_id = $invoice->id;

                Invoice::total_quantity('minus', $products[$i]['quantity'], $products[$i]['item']);

                $updatePrice = ($products[$i]['price'] * $products[$i]['quantity']) + ($products[$i]['itemTaxPrice']) - ($products[$i]['discount']); //updateUserBalance

            } else {
                Invoice::total_quantity('plus', $invoiceProduct->quantity, $invoiceProduct->product_id);
            }

            if (isset($products[$i]['item'])) {
                $invoiceProduct->product_id = $products[$i]['item'];
            }
            $invoiceProduct->product_type   = $products[$i]['product_type'];
            $invoiceProduct->quantity       = $products[$i]['quantity'];
            $invoiceProduct->tax            = $products[$i]['tax'];
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->price          = $products[$i]['price'];
            $invoiceProduct->description    = str_replace(array('\'', '"', '`', '{', "\n"), ' ', $products[$i]['description']);
            $invoiceProduct->save();

            //inventory management (Quantity)
            if ($products[$i]['id'] > 0) {
                Invoice::total_quantity('minus', $products[$i]['quantity'], $invoiceProduct->product_id);
            }
            //Product Stock Report
            if (module_is_active('Account')) {
                $type = 'invoice';
                $type_id = $invoice->id;
                \Modules\Account\Entities\StockReport::where('type', '=', 'invoice')->where('type_id', '=', $invoice->id)->delete();
                $description = $products[$i]['quantity'] . '  ' . __(' quantity sold in invoice') . ' ' . Invoice::invoiceNumberFormat($invoice->invoice_id);
                if (empty($products[$i]['id'])) {
                    Invoice::addProductStock($products[$i]['item'], $products[$i]['quantity'], $type, $description, $type_id);
                }
            }
        }
        // first parameter request second parameter invoice
        event(new UpdateInvoice($request, $invoice));

        return redirect()->route('invoice.index')->with('success', __('Invoice successfully updated.'));
    }
    public function UpdateProjectInvoice($request, $invoice)
    {

        $validator = \Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'issue_date' => 'required',
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

        $customer = \Modules\Account\Entities\Customer::where('user_id', '=', $request->customer_id)->first();
        $invoice->customer_id    = !empty($customer) ?  $customer->id : null;
        if (empty($customer)) {
            $user = User::find($request->customer_id);
        }
        if ($request->invoice_type != $invoice->invoice_module) {
            InvoiceProduct::where('invoice_id', '=', $invoice->id)->delete();
        }

        $status = Invoice::$statues;
        $invoice->invoice_id     = $invoice->invoice_id;
        $invoice->user_id        = $customer->user_id ?? $user->id;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->account_type     = $request->account_type;
        $invoice->invoice_module = 'taskly';
        $invoice->category_id    = $request->project;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->save();

        $products = $request->items;
        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }

        $project_tax = implode(',', $request->tax_project);
        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct = InvoiceProduct::find($products[$i]['id']);
            if ($invoiceProduct == null) {
                $invoiceProduct             = new InvoiceProduct();
                $invoiceProduct->invoice_id = $invoice->id;
            }
            $invoiceProduct->product_id  = $products[$i]['product_id'];
            $invoiceProduct->quantity    = 1;
            $invoiceProduct->tax         = $project_tax;
            $invoiceProduct->discount    = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->price       = $products[$i]['price'];
            $invoiceProduct->description = $products[$i]['description'];
            $invoiceProduct->save();
        }
        event(new UpdateInvoice($request, $invoice));

        return redirect()->route('invoice.index')->with('success', __('Invoice successfully updated.'));
    }
    public function UpdatePartsInvoice($request, $invoice)
    {

        $validator = \Validator::make(
            $request->all(),
            [
                'work_order' => 'required',
                'issue_date' => 'required',
                'due_date' => 'required',
                'items' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->route('invoice.index')->with('error', $messages->first());
        }

        $customer = \Modules\Account\Entities\Customer::where('user_id', '=', $request->customer_id)->first();
        if (empty($customer)) {
            $user = User::find($request->customer_id);
        }
        $invoice->customer_id    = !empty($customer) ?  $customer->id : null;
        if ($request->invoice_type == "product") {
            $request->invoice_type = 'account';
        } else if ($request->invoice_type == "project") {
            $request->invoice_type = 'taskly';
        } else if ($request->invoice_type == "parts") {
            $request->invoice_type = 'cmms';
        }
        if ($request->invoice_type != $invoice->invoice_module) {
            InvoiceProduct::where('invoice_id', '=', $invoice->id)->delete();
        }
        $invoice->user_id        = $customer->user_id ?? $user->id;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->account_type     = $request->account_type;
        $invoice->invoice_module = 'cmms';
        $invoice->category_id    = $request->work_order;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->save();
        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }
        $products = $request->items;
        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct = InvoiceProduct::find($products[$i]['id']);

            if ($invoiceProduct == null) {
                $invoiceProduct             = new InvoiceProduct();
                $invoiceProduct->invoice_id = $invoice->id;

                Invoice::total_quantity('minus', $products[$i]['quantity'], $products[$i]['product_id']);

                $updatePrice = ($products[$i]['price'] * $products[$i]['quantity']) + ($products[$i]['itemTaxPrice']) - ($products[$i]['discount']);
            } else {
                Invoice::total_quantity('plus', $invoiceProduct->quantity, $invoiceProduct->product_id);
            }

            if (isset($products[$i]['product_id'])) {
                $invoiceProduct->product_id = $products[$i]['product_id'];
            }
            $invoiceProduct->product_type   = $products[$i]['product_type'];
            $invoiceProduct->quantity       = $products[$i]['quantity'];
            $invoiceProduct->tax            = $products[$i]['tax'];
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->price          = $products[$i]['price'];
            $invoiceProduct->description    = str_replace(array('\'', '"', '`', '{', "\n"), ' ', $products[$i]['description']);
            $invoiceProduct->save();

            //inventory management (Quantity)
            if ($products[$i]['id'] > 0) {
                Invoice::total_quantity('minus', $products[$i]['quantity'], $invoiceProduct->product_id);
            }
            //Product Stock Report
            if (module_is_active('Account')) {
                $type = 'invoice';
                $type_id = $invoice->id;
                \Modules\Account\Entities\StockReport::where('type', '=', 'invoice')->where('type_id', '=', $invoice->id)->delete();
                $description = $products[$i]['quantity'] . '  ' . __(' quantity sold in invoice') . ' ' . Invoice::invoiceNumberFormat($invoice->invoice_id);
                if (empty($products[$i]['id'])) {
                    Invoice::addProductStock($products[$i]['product_id'], $products[$i]['quantity'], $type, $description, $type_id);
                }
            }
        }

        // first parameter request second parameter invoice
        event(new UpdateInvoice($request, $invoice));

        return redirect()->route('invoice.index')->with('success', __('Invoice successfully updated.'));
    }
    public function UpdateRentInvoice($request, $invoice)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'rent_type' => 'required',
                'issue_date' => 'required',
                'due_date' => 'required',
                'items' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        foreach ($request->items as $item) {
            if (empty($item['item']) && $item['item'] == 0) {
                return redirect()->back()->with('error', __('Please select an item'));
            }
        }
        $status = Invoice::$statues;

        $customer = \Modules\Account\Entities\Customer::where('user_id', '=', $request->customer_id)->first();
        $invoice->customer_id    = !empty($customer) ?  $customer->id : null;
        if (empty($customer)) {
            $user = User::find($request->customer_id);
        }
        $invoice->user_id        = $customer->user_id ?? $user->id;
        $invoice->status         = 0;
        $invoice->invoice_module = 'rent';
        $invoice->account_type     = $request->account_type;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->category_id    = $request->rent_type;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();

        $invoice->save();
        $products = $request->items;

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');
        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }

        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct = InvoiceProduct::find($products[$i]['id']);
            if ($invoiceProduct == null) {
                $invoiceProduct             = new InvoiceProduct();
                $invoiceProduct->invoice_id = $invoice->id;
            }
            $invoiceProduct->product_type   = $products[$i]['product_type'];
            $invoiceProduct->product_id     = $products[$i]['item'];
            $invoiceProduct->quantity       = $products[$i]['quantity'];
            $invoiceProduct->tax            = $products[$i]['tax'];
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->price          = $products[$i]['price'];
            $invoiceProduct->description    = str_replace(array('\'', '"', '`', '{', "\n"), ' ', $products[$i]['description']);
            $invoiceProduct->save();

            if (module_is_active('ProductService')) {
                Invoice::total_quantity('minus', $invoiceProduct->quantity, $invoiceProduct->product_id);
            }

            if (module_is_active('Account')) {
                //Product Stock Report
                $type = 'invoice';
                $type_id = $invoice->id;
                \Modules\Account\Entities\StockReport::where('type', '=', 'invoice')->where('type_id', '=', $invoice->id)->delete();
                $description = $invoiceProduct->quantity . '  ' . __(' quantity sold in invoice') . ' ' . Invoice::invoiceNumberFormat($invoice->invoice_id);
                \Modules\Account\Entities\AccountUtility::addProductStock($products[$i]['item'], $invoiceProduct->quantity, $type, $description, $type_id);
            }
        }
        event(new UpdateInvoice($request, $invoice));


        if (isset($request->agentPurchaseOrderId)) {

            return redirect()->route('salesagents.purchase.order.show', \Crypt::encrypt($request->agentPurchaseOrderId))->with('success', __('Invoice successfully created.'));
        }

        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully created.'));
    }
    public function UpdateCourseInvoice($request, $invoice)
    {

        $validator = \Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'issue_date' => 'required',
                'due_date' => 'required',
                'course_order' => 'required',
                'tax_project' => 'required',
                'items' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $status = Invoice::$statues;
        $invoice->user_id        = $request->customer_id;
        $invoice->customer_id    = $request->customer_id;
        $invoice->status         = 0;
        $invoice->account_type   = $request->account_type;
        $invoice->invoice_module = 'lms';
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->category_id    = $request->course_order;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();

        $invoice->save();

        $products = $request->items;

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');

        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }
        $project_tax = implode(',', $request->tax_project);

        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct = InvoiceProduct::find($products[$i]['id']);

            if ($invoiceProduct == null) {
                $invoiceProduct             = new InvoiceProduct();
                $invoiceProduct->invoice_id = $invoice->id;
            }
            if (isset($products[$i]['product_id'])) {
                $invoiceProduct->product_id = $products[$i]['product_id'];
            }
            $invoiceProduct->invoice_id     = $invoice->id;
            $invoiceProduct->product_id     = $products[$i]['product_id'];
            $invoiceProduct->quantity       = 1;
            $invoiceProduct->tax            = $project_tax;
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->price          = $products[$i]['price'];
            $invoiceProduct->description    = $products[$i]['description'];
            $invoiceProduct->save();
        }

        // first parameter request second parameter invoice
        event(new UpdateInvoice($request, $invoice));

        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully Updated.'));
    }
    public function UpdateCaseInvoice($request, $invoice)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'issue_date' => 'required',
                'due_date' => 'required',
                'items' => 'required',
                'customer_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $status = Invoice::$statues;
        $invoice->user_id           = $request->customer_id;
        $invoice->status            = 0;
        $invoice->account_type      = $request->account_type;
        $invoice->invoice_module    = 'legalcase';
        $invoice->issue_date        = $request->issue_date;
        $invoice->due_date          = $request->due_date;

        $invoice->invoice_template  = $request->invoice_template;
        $invoice->workspace         = getActiveWorkSpace();
        $invoice->created_by        = creatorId();

        $invoice->save();

        $products = $request->items;

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');

        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }

        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct = InvoiceProduct::find($products[$i]['id']);

            if ($invoiceProduct == null) {
                $invoiceProduct             = new InvoiceProduct();
                $invoiceProduct->invoice_id = $invoice->id;
            }
            if (isset($products[$i]['item'])) {
                $invoiceProduct->product_id = $products[$i]['item'];
            }
            $invoiceProduct->invoice_id     = $invoice->id;
            $invoiceProduct->product_name     = $products[$i]['product_name'];
            $invoiceProduct->quantity       = $products[$i]['quantity'];
            $invoiceProduct->tax            = $products[$i]['tax'];
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->price          = $products[$i]['price'];
            $invoiceProduct->description    = $products[$i]['description'];
            $invoiceProduct->save();
        }

        // first parameter request second parameter invoice
        event(new UpdateInvoice($request, $invoice));

        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully Updated.'));
    }
    public function UpdateSalesInvoice($request, $invoice)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'issue_date' => 'required',
                'due_date' => 'required',
                'sale_invoice' => 'required',
                'items' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $status = Invoice::$statues;
        $invoice->user_id        = $request->customer_id;
        $invoice->status         = 0;
        $invoice->invoice_module = 'sales';
        $invoice->account_type   = $request->account_type;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->category_id    = $request->sale_invoice;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();
        $invoice->save();

        $products = $request->items;

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');

        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }

        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct = InvoiceProduct::find($products[$i]['id']);

            if ($invoiceProduct == null) {
                $invoiceProduct             = new InvoiceProduct();
                $invoiceProduct->invoice_id = $invoice->id;
            }
            if (isset($products[$i]['item'])) {
                $invoiceProduct->product_id = $products[$i]['product_id'];
            }
            $invoiceProduct->invoice_id     = $invoice->id;
            $invoiceProduct->product_id     = $products[$i]['product_id'];
            $invoiceProduct->quantity       = $products[$i]['quantity'];
            $invoiceProduct->tax            = $products[$i]['tax'];
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->price          = $products[$i]['price'];
            $invoiceProduct->description    = $products[$i]['description'];
            $invoiceProduct->save();

            if (module_is_active('ProductService')) {
                Invoice::total_quantity('minus', $invoiceProduct->quantity, $invoiceProduct->product_id);
            }
        }

        event(new UpdateInvoice($request, $invoice));

        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully Updated.'));
    }
    public function UpdateNewspaperInvoice($request, $invoice)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'issue_date' => 'required',
                'due_date' => 'required',
                'items' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $status = Invoice::$statues;
        $invoice->user_id        = $request->customer_id;
        $invoice->status         = 0;
        $invoice->invoice_module = 'newspaper';
        $invoice->account_type   = $request->account_type;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();
        $invoice->save();

        $products = $request->items;

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');

        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }

        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct = InvoiceProduct::find($products[$i]['id']);

            if ($invoiceProduct == null) {
                $invoiceProduct             = new InvoiceProduct();
                $invoiceProduct->invoice_id = $invoice->id;
            }
            if (isset($products[$i]['item'])) {
                $invoiceProduct->product_id = $products[$i]['product_id'];
            }
            $invoiceProduct->invoice_id     = $invoice->id;
            $invoiceProduct->product_id     = $products[$i]['product_id'];
            $invoiceProduct->quantity       = $products[$i]['quantity'];
            $invoiceProduct->tax            = isset($products[$i]['tax_id']) ? $products[$i]['tax_id'] : 0;
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->price          = $products[$i]['price'];
            $invoiceProduct->save();
        }

        event(new UpdateInvoice($request, $invoice));

        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully Updated.'));
    }
    private function updateChildInvoice($request, $invoice)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'issue_date' => 'required',
                'due_date' => 'required',
                'items.*.amount' => 'required|numeric|gt:0',

            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $status = Invoice::$statues;
        $invoice->user_id        = $request->customer_id;
        $invoice->customer_id        = $request->customer_id;
        $invoice->status         = 0;
        $invoice->invoice_module = 'childcare';
        $invoice->account_type   = $request->account_type;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();

        $invoice->save();
        $products = $request->items;

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');
        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }
        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct = InvoiceProduct::find($products[$i]['id']);

            if ($invoiceProduct == null) {
                $invoiceProduct             = new InvoiceProduct();
                $invoiceProduct->invoice_id = $invoice->id;
            }
            $invoiceProduct->invoice_id     = $invoice->id;
            $invoiceProduct->product_name   = $products[$i]['name'];
            $invoiceProduct->price          = $products[$i]['amount'];
            $invoiceProduct->description          = $products[$i]['notes'] ?? '';
            $invoiceProduct->tax            = 0;

            $invoiceProduct->save();
        }
        event(new UpdateInvoice($request, $invoice));


        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully updated.'));
    }
    private function updateMobileInvoice($request, $invoice)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'issue_date' => 'required',
                'due_date' => 'required',
                'items' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $status = Invoice::$statues;
        $invoice->user_id        = $request->customer_id;
        $invoice->customer_id        = $request->customer_id;
        $invoice->status         = 0;
        $invoice->invoice_module = 'mobileservice';
        $invoice->account_type   = $request->account_type;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->category_id    = $request->repair_charge;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();

        $invoice->save();
        $products = $request->items;

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');
        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }
        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct = InvoiceProduct::find($products[$i]['id']);

            if ($invoiceProduct == null) {
                $invoiceProduct             = new InvoiceProduct();
                $invoiceProduct->invoice_id = $invoice->id;
            }
            $invoiceProduct->invoice_id     = $invoice->id;
            $invoiceProduct->product_id     = $products[$i]['product_id'];
            $invoiceProduct->quantity       = $products[$i]['quantity'];
            $invoiceProduct->price          = $products[$i]['price'];
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->tax            = $products[$i]['tax'];
            $invoiceProduct->description    = str_replace(array('\'', '"', '`', '{', "\n"), ' ', $products[$i]['description']);
            $invoiceProduct->save();

            if (module_is_active('ProductService')) {
                Invoice::total_quantity('minus', $invoiceProduct->quantity, $invoiceProduct->product_id);
            }
        }
        event(new UpdateInvoice($request, $invoice));


        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully updated.'));
    }
    private function updateVehicleInvoice($request, $invoice)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'issue_date' => 'required',
                'due_date' => 'required',
                'items' => 'required',
                'service_charge' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        foreach ($request->items as $item) {
            if (empty($item['item']) && $item['item'] == 0) {
                return redirect()->back()->with('error', __('Please select an item'));
            }
        }
        $status = Invoice::$statues;
        $invoice->user_id        = $request->customer_id;
        $invoice->customer_id        = $request->customer_id;
        $invoice->status         = 0;
        $invoice->invoice_module = 'vehicleinspection';
        $invoice->account_type   = $request->account_type;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->category_id    = $request->service_charge;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();

        $invoice->save();
        $products = $request->items;

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');
        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }

        for ($i = 0; $i < count($products); $i++) {

            $invoiceProduct = InvoiceProduct::find($products[$i]['id']);

            if ($invoiceProduct == null) {
                $invoiceProduct             = new InvoiceProduct();
                $invoiceProduct->invoice_id = $invoice->id;

                InspectionDefectsAndRepairs::total_quantity('minus', $products[$i]['quantity'], $products[$i]['item']);
            } else {
                InspectionDefectsAndRepairs::total_quantity('plus', $invoiceProduct->quantity, $invoiceProduct->product_id);
            }

            if (isset($products[$i]['item'])) {
                $invoiceProduct->product_id = $products[$i]['item'];
            }
            $invoiceProduct->product_type   = $products[$i]['product_type'];
            $invoiceProduct->quantity       = $products[$i]['quantity'];
            $invoiceProduct->tax            = $products[$i]['tax'];
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->price          = $products[$i]['price'];
            $invoiceProduct->description    = str_replace(array('\'', '"', '`', '{', "\n"), ' ', $products[$i]['description']);
            $invoiceProduct->save();


            if (module_is_active('ProductService')) {
                Invoice::total_quantity('minus', $invoiceProduct->quantity, $invoiceProduct->product_id);
            }
        }
        event(new UpdateInvoice($request, $invoice));


        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully Updated.'));
    }
    private function updateMachineInvoice($request, $invoice)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'issue_date' => 'required',
                'due_date' => 'required',
                'items' => 'required',
                'service_charge' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        foreach ($request->items as $item) {
            if (empty($item['item']) && $item['item'] == 0) {
                return redirect()->back()->with('error', __('Please select an item'));
            }
        }
        $status = Invoice::$statues;
        $invoice->user_id        = $request->customer_id;
        $invoice->customer_id        = $request->customer_id;
        $invoice->status         = 0;
        $invoice->invoice_module = 'machinerepair';
        $invoice->account_type   = $request->account_type;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->category_id    = $request->service_charge;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();

        $invoice->save();
        $products = $request->items;

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');
        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }

        for ($i = 0; $i < count($products); $i++) {

            $invoiceProduct = InvoiceProduct::find($products[$i]['id']);

            if ($invoiceProduct == null) {
                $invoiceProduct             = new InvoiceProduct();
                $invoiceProduct->invoice_id = $invoice->id;

                MachineInvoice::total_quantity('minus', $products[$i]['quantity'], $products[$i]['item']);
            } else {
                MachineInvoice::total_quantity('plus', $invoiceProduct->quantity, $invoiceProduct->product_id);
            }

            if (isset($products[$i]['item'])) {
                $invoiceProduct->product_id = $products[$i]['item'];
            }
            $invoiceProduct->product_type   = $products[$i]['product_type'];
            $invoiceProduct->quantity       = $products[$i]['quantity'];
            $invoiceProduct->tax            = $products[$i]['tax'];
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->price          = $products[$i]['price'];
            $invoiceProduct->description    = str_replace(array('\'', '"', '`', '{', "\n"), ' ', $products[$i]['description']);
            $invoiceProduct->save();

            //inventory management (Quantity)
            if ($products[$i]['id'] > 0) {
                MachineInvoice::total_quantity('minus', $products[$i]['quantity'], $invoiceProduct->product_id);
            }
        }

        event(new UpdateInvoice($request, $invoice));

        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully Updated.'));
    }
    private function updateCarDealInvoice($request, $invoice)
    {

        $validator = \Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'issue_date' => 'required',
                'due_date' => 'required',
                'items' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $status = Invoice::$statues;
        $customer = \Modules\Account\Entities\Customer::where('user_id', '=', $request->customer_id)->first();
        if (empty($customer)) {
            $user = User::find($request->customer_id);
        }
        $invoice->customer_id    = !empty($customer) ?  $customer->id : null;
        $invoice->user_id        = $request->customer_id ?? $user->id;
        $invoice->status         = 0;
        $invoice->invoice_module = 'cardealership';
        $invoice->account_type   = $request->account_type;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->category_id    = $request->service_charge;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();

        $invoice->save();
        $products = $request->items;

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');
        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }

        for ($i = 0; $i < count($products); $i++) {


            $invoiceProduct = InvoiceProduct::find($products[$i]['id']);
            if ($invoiceProduct == null) {
                $invoiceProduct             = new InvoiceProduct();
                $invoiceProduct->invoice_id    = $invoice->id;
                CarDealershipUtility::total_quantity('minus', $products[$i]['quantity'], $products[$i]['item']);
                $updatePrice = ($products[$i]['price'] * $products[$i]['quantity']) + ($products[$i]['itemTaxPrice']) - ($products[$i]['discount']);
            } else {
                CarDealershipUtility::total_quantity('plus', $invoiceProduct->quantity, $invoiceProduct->product_id);
            }

            if (isset($products[$i]['item'])) {
                $invoiceProduct->product_id = $products[$i]['item'];
            }
            $invoiceProduct->quantity      = $products[$i]['quantity'];
            $invoiceProduct->tax           = $products[$i]['tax'];
            $invoiceProduct->discount      = $products[$i]['discount'];
            $invoiceProduct->price         = $products[$i]['price'];
            $invoiceProduct->description   = str_replace("'", "", $products[$i]['description']);
            $invoiceProduct->save();

            if ($products[$i]['id'] > 0) {
                CarDealershipUtility::total_quantity('minus', $products[$i]['quantity'], $invoiceProduct->product_id);
            }
        }

        event(new UpdateInvoice($request, $invoice));

        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully Updated.'));
    }
    private function updateFleetInvoice($request, $invoice)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'issue_date' => 'required',
                'due_date' => 'required',
                'items' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $status = Invoice::$statues;
        $customer = \Modules\Account\Entities\Customer::where('user_id', '=', $request->customer_id)->first();
        if (empty($customer)) {
            $user = User::find($request->customer_id);
        }
        $invoice->customer_id    = !empty($customer) ?  $customer->id : null;
        $invoice->user_id        = $request->customer_id ?? $user->id;
        $invoice->status         = 0;
        $invoice->invoice_module = 'Fleet';
        $invoice->account_type   = $request->account_type;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->category_id    = $request->service_charge;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();
        $invoice->save();
        $products = $request->items;

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');
        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }

        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct = InvoiceProduct::find($products[$i]['id']);
            if ($invoiceProduct == null) {
                $invoiceProduct             = new InvoiceProduct();
                $invoiceProduct->invoice_id    = $invoice->id;
            }
            if (isset($products[$i]['product_id'])) {
                $invoiceProduct->product_id = $products[$i]['product_id'];
            }
            $invoiceProduct->quantity      = $products[$i]['quantity'];
            $invoiceProduct->tax           = $products[$i]['tax'];
            $invoiceProduct->discount      = 0;
            $invoiceProduct->price         = $products[$i]['rate'];
            $invoiceProduct->description   = str_replace("'", "", $products[$i]['description']);

            $invoiceProduct->save();
        }
        event(new UpdateInvoice($request, $invoice));

        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully Updated.'));
    }
    private function updateRestaurantInvoice($request, $invoice)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'issue_date' => 'required',
                'due_date' => 'required',
                'customer_id' => 'required',
                'restaurant_order' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $invoice->customer_id    = $request->customer_id;
        $invoice->user_id        = $request->customer_id;
        $invoice->status         = 0;
        $invoice->invoice_module = 'RestaurantMenu';
        $invoice->account_type   = $request->account_type;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->category_id    = $request->restaurant_order;
        $invoice->invoice_template   = $request->invoice_template;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();
        $invoice->save();

        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');
        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }

        $products = $request->items;
        $project_tax = implode(',', $request->tax_project);

        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct = InvoiceProduct::find($products[$i]['id']);
            if ($invoiceProduct == null) {
                $invoiceProduct             = new InvoiceProduct();
                $invoiceProduct->invoice_id = $invoice->id;
            }
            $invoiceProduct->product_name     = $products[$i]['item_name'];
            $invoiceProduct->quantity       = isset($products[$i]['quantity']) ? $products[$i]['quantity'] : '';
            $invoiceProduct->tax            = $project_tax;
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->price          = $products[$i]['sales_price'];
            $invoiceProduct->description    = $products[$i]['description'];
            $invoiceProduct->save();

            if (module_is_active('ProductService')) {
                RestaurantInvoice::total_quantity('minus', $invoiceProduct->quantity, $invoiceProduct->product_id);
            }
        }

        event(new UpdateInvoice($request, $invoice));

        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully updated.'));
    }
    private function updateMusicInvoice($request, $invoice)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'customer_id' => 'required',
                'issue_date' => 'required',
                'due_date' => 'required',
                'student' => 'required',
                'items' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        foreach ($request->items as $item) {
            if (empty($item['item']) && $item['item'] == 0) {
                return redirect()->back()->with('error', __('Please select an item'));
            }
        }
        $customer = \Modules\Account\Entities\Customer::find($request->customer_id);
        if (empty($customer)) {
            $user = User::find($request->customer_id);
        }
        $status = Invoice::$statues;
        $customer = \Modules\Account\Entities\Customer::where('user_id', '=', $request->customer_id)->first();
        $invoice->customer_id    = !empty($customer) ?  $customer->id : null;
        $invoice->user_id        = $request->customer_id ?? $user->id;
        $invoice->status         = 0;
        $invoice->invoice_module = 'musicinstitute';
        $invoice->account_type   = $request->account_type;
        $invoice->issue_date     = $request->issue_date;
        $invoice->due_date       = $request->due_date;
        $invoice->invoice_template    = $request->invoice_template;
        $invoice->category_id    = $request->student;
        $invoice->workspace      = getActiveWorkSpace();
        $invoice->created_by     = creatorId();

        $invoice->save();


        Invoice::starting_number($invoice->invoice_id + 1, 'invoice');
        if (module_is_active('CustomField')) {
            \Modules\CustomField\Entities\CustomField::saveData($invoice, $request->customField);
        }

        $products = $request->items;
        for ($i = 0; $i < count($products); $i++) {
            $invoiceProduct = InvoiceProduct::find($products[$i]['id']);

            if ($invoiceProduct == null) {
                $invoiceProduct             = new InvoiceProduct();
                $invoiceProduct->invoice_id = $invoice->id;

                Invoice::total_quantity('minus', $products[$i]['quantity'], $products[$i]['item']);

                $updatePrice = ($products[$i]['price'] * $products[$i]['quantity']) + ($products[$i]['itemTaxPrice']) - ($products[$i]['discount']);
            } else {
                Invoice::total_quantity('plus', $invoiceProduct->quantity, $invoiceProduct->product_id);
            }

            if (isset($products[$i]['item'])) {
                $invoiceProduct->product_id = $products[$i]['item'];
            }
            $invoiceProduct->product_type   = $products[$i]['product_type'];
            $invoiceProduct->quantity       = $products[$i]['quantity'];
            $invoiceProduct->tax            = $products[$i]['tax'];
            $invoiceProduct->discount       = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $invoiceProduct->price          = $products[$i]['price'];
            $invoiceProduct->description    = str_replace(array('\'', '"', '`', '{', "\n"), ' ', $products[$i]['description']);
            $invoiceProduct->save();

            //inventory management (Quantity)
            if ($products[$i]['id'] > 0) {
                Invoice::total_quantity('minus', $products[$i]['quantity'], $invoiceProduct->product_id);
            }
            //Product Stock Report
            if (module_is_active('Account')) {
                $type = 'invoice';
                $type_id = $invoice->id;
                \Modules\Account\Entities\StockReport::where('type', '=', 'invoice')->where('type_id', '=', $invoice->id)->delete();
                $description = $products[$i]['quantity'] . '  ' . __(' quantity sold in invoice') . ' ' . Invoice::invoiceNumberFormat($invoice->invoice_id);
                if (empty($products[$i]['id'])) {
                    Invoice::addProductStock($products[$i]['item'], $products[$i]['quantity'], $type, $description, $type_id);
                }
            }
        }
        event(new UpdateInvoice($request, $invoice));

        return redirect()->route('invoice.index', $invoice->id)->with('success', __('Invoice successfully Updated.'));
    }
    public function getInvoicItemeDetail(Request $request)
    {
        $newspaper = Newspaper::find($request->product_id);
        $tax       = $newspaper->Tax ?? '';
        $totaltax  = ($newspaper->price * $tax->percentage) / 100;
        $Tax       = $totaltax * $request->quantity;
        $total     = ($newspaper->price * $request->quantity) + $Tax;

        return response()->json([
            'newspaper' => $newspaper->toArray() ?? '',
            'varient' => $newspaper->Varient->toArray() ?? '',
            'tax' => $tax->toArray() ?? '',
            'total' => $total ?? '',
        ]);
    }

    public function InvocieStatus(Request $request)
    {

        $invoices = Invoice::where('workspace', getActiveWorkSpace())->get();
        $totalDueAmount = 0;
        foreach ($invoices as $invoice) {
            $totalDueAmount += $invoice->getDue();
        }

        $statues = Invoice::$statues;
        return view('invoice.statusreport', compact('statues', 'invoices', 'totalDueAmount'));
    }
}
