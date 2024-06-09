<?php

namespace Modules\Account\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Account\Entities\Bill;
use Modules\Account\Entities\BillPayment;
use Modules\Account\Entities\Vender;
use Illuminate\Support\Facades\Hash;
use Modules\Account\Events\CreateVendor;
use Modules\Account\Events\DestroyVendor;
use Modules\Account\Events\UpdateVendor;

class VenderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(Auth::user()->isAbleTo('vendor manage'))
        {
            $vendors = User::where('workspace_id',getActiveWorkSpace())
                        ->leftjoin('vendors', 'users.id', '=', 'vendors.user_id')
                        ->where('users.type', 'vendor')
                        ->select('users.*','vendors.*', 'users.name as name', 'users.email as email', 'users.id as id','users.mobile_no as contact')
                        ->get();
            return view('account::vendor.index', compact('vendors'));
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
        if (Auth::user()->isAbleTo('vendor create'))
        {
            if(module_is_active('CustomField')){
                $customFields =  \Modules\CustomField\Entities\CustomField::where('workspace_id',getActiveWorkSpace())->where('module', '=', 'Account')->where('sub_module','Vendor')->get();
            }else{
                $customFields = null;
            }

            return view('account::vendor.create',compact('customFields'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
        return view('account::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('vendor create'))
        {
            $canUse =  PlanCheck('User', Auth::user()->id);
            if ($canUse == false) {
                return redirect()->back()->with('error', 'You have maxed out the total number of vendor allowed on your current plan');
            }

            $rules = [
                'name' => 'required',
                'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
                'billing_name' => 'required',
                'billing_phone' => 'required',
                'billing_address' => 'required',
                'billing_city' => 'required',
                'billing_state' => 'required',
                'billing_country' => 'required',
                'billing_zip' => 'required',
            ];

            $validator = \Validator::make($request->all(), $rules);
            if(empty($request->user_id))
            {
                $rules = [
                    'email' => 'required|email|unique:users',
                    'password' => 'required',
                    'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',

                ];
                $validator = \Validator::make($request->all(), $rules);
            }

            if ($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->route('vendors.index')->with('error', $messages->first());
            }
            $roles = Role::where('name','vendor')->where('guard_name','web')->where('created_by',creatorId())->first();
            if(empty($roles))
            {
                return redirect()->back()->with('error', __('Vendor Role Not found !'));
            }
            if(!empty($request->user_id))
            {
                $user = User::find($request->user_id);

                if(empty($user))
                {
                    return redirect()->back()->with('error', __('Something went wrong please try again.'));
                }
                if($user->name != $request->name)
                {
                    $user->name = $request->name;
                    $user->save();
                }
                if($user->mobile_no != $request->contact)
                {
                    $user->mobile_no = $request->contact;
                    $user->save();
                }
            }
            else
            {
                $userpassword                = $request->input('password');
                $user['name']                = $request->input('name');
                $user['email']               = $request->input('email');
                $user['mobile_no']           = $request->input('contact');
                $user['password']            = \Hash::make($userpassword);
                $user['email_verified_at']   = date('Y-m-d h:i:s');
                $user['lang']                = 'en';
                $user['type']                = $roles->name;
                $user['created_by']          = creatorId();
                $user['workspace_id']        = getActiveWorkSpace();
                $user['active_workspace']    = getActiveWorkSpace();
                $user = User::create($user);
                $user->addRole($roles);
            }

            $vendor                   = new Vender();
            $vendor->vendor_id        = $this->vendorNumber();
            $vendor->user_id          = $user->id;
            $vendor->name             = $request->name;
            $vendor->contact          = $request->contact;
            $vendor->email            = $user->email;
            $vendor->tax_number       = $request->tax_number;
            $vendor->billing_name     = $request->billing_name;
            $vendor->billing_country  = $request->billing_country;
            $vendor->billing_state    = $request->billing_state;
            $vendor->billing_city     = $request->billing_city;
            $vendor->billing_phone    = $request->billing_phone;
            $vendor->billing_zip      = $request->billing_zip;
            $vendor->billing_address  = $request->billing_address;
            if(company_setting('bill_shipping_display')=='on')
            {
                $vendor->shipping_name    = $request->shipping_name;
                $vendor->shipping_country = $request->shipping_country;
                $vendor->shipping_state   = $request->shipping_state;
                $vendor->shipping_city    = $request->shipping_city;
                $vendor->shipping_phone   = $request->shipping_phone;
                $vendor->shipping_zip     = $request->shipping_zip;
                $vendor->shipping_address = $request->shipping_address;
            }
            $vendor->lang             = $user->lang;
            $vendor->created_by       = creatorId();
            $vendor->workspace        = getActiveWorkSpace();
            $vendor->save();
            if(module_is_active('CustomField'))
            {
                \Modules\CustomField\Entities\CustomField::saveData($vendor, $request->customField);
            }

            event(new CreateVendor($request,$vendor));

            return redirect()->back()->with('success', __('Vendor successfully created.'));
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
    public function show($e_id)
    {
        if (Auth::user()->isAbleTo('vendor show'))
        {
            $id         = \Crypt::decrypt($e_id);
            $user       = User::where('id',$id)->where('workspace_id',getActiveWorkSpace())->first();
            $vendor     = Vender::where('user_id',$id)->where('workspace',getActiveWorkSpace())->first();
            if(module_is_active('CustomField')){
                $vendor->customField = \Modules\CustomField\Entities\CustomField::getData($vendor, 'Account','Vendor');
                $customFields             = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'Account')->where('sub_module','Vendor')->get();
            }else{
                $customFields = null;
            }

            $bill= Bill::where('workspace','=',getActiveWorkSpace())->where('vendor_id','=',$vendor->id)->get()->pluck('id');

            $bill_payment =BillPayment::whereIn('bill_id',$bill);
            $data['from_date']  = date('Y-m-1');
            $data['until_date'] = date('Y-m-t');
            $bill_payment->whereBetween('date',  [$data['from_date'], $data['until_date']]);
            $bill_payment=$bill_payment->get();
            $company_settings = getCompanyAllSetting();
            $settings['company_address'] = isset($company_settings['company_address']) ? $company_settings['company_address'] : '';
            $settings['company_city'] = isset($company_settings['company_city']) ? $company_settings['company_city'] : '';
            $settings['company_state'] = isset($company_settings['company_state']) ? $company_settings['company_state'] : '';
            $settings['company_zipcode'] = isset($company_settings['company_zipcode']) ? $company_settings['company_zipcode'] : '';
            $settings['company_country'] = isset($company_settings['company_country']) ? $company_settings['company_country'] : '';
            return view('account::vendor.show', compact('vendor','user','customFields','user','bill_payment','data','settings'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('vendor edit'))
        {
            $user         = User::where('id',$id)->where('workspace_id',getActiveWorkSpace())->first();
            $vendor     = Vender::where('user_id',$id)->where('workspace',getActiveWorkSpace())->first();
            if(!empty($vendor)){

                if(module_is_active('CustomField')){
                    $vendor->customField = \Modules\CustomField\Entities\CustomField::getData($vendor, 'Account','Vendor');
                    $customFields             = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'Account')->where('sub_module','Vendor')->get();
                }else{
                    $customFields = null;
                }
            }else{
                if(module_is_active('CustomField')){
                    $customFields =  \Modules\CustomField\Entities\CustomField::where('workspace_id',getActiveWorkSpace())->where('module', '=', 'Account')->where('sub_module','Vendor')->get();
                }else{
                    $customFields = null;
                }
            }

            return view('account::vendor.edit', compact('vendor', 'user','customFields'));
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

    public function update(Request $request, Vender $vendor)
    {
        if (Auth::user()->isAbleTo('vendor edit'))
        {
            $rules = [
                'name' => 'required',
                'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
                'billing_name' => 'required',
                'billing_phone' => 'required',
                'billing_address' => 'required',
                'billing_city' => 'required',
                'billing_state' => 'required',
                'billing_country' => 'required',
                'billing_zip' => 'required',
            ];


            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('vendor.index')->with('error', $messages->first());
            }

            $user = User::where('id',$request->user_id)->first();
            if(empty($user))
            {
                return redirect()->back()->with('error', __('Something went wrong please try again.'));
            }
            if($user->name != $request->name)
            {
                $user->name = $request->name;
                $user->save();
            }
            if($user->mobile_no != $request->contact)
            {
                $user->mobile_no = $request->contact;
                $user->save();
            }

            $vendor->name             = $request->name;
            $vendor->contact          = $request->contact;
            $vendor->tax_number       = $request->tax_number;
            $vendor->billing_name     = $request->billing_name;
            $vendor->billing_country  = $request->billing_country;
            $vendor->billing_state    = $request->billing_state;
            $vendor->billing_city     = $request->billing_city;
            $vendor->billing_phone    = $request->billing_phone;
            $vendor->billing_zip      = $request->billing_zip;
            $vendor->billing_address  = $request->billing_address;
            $vendor->shipping_name    = $request->shipping_name;
            $vendor->shipping_country = $request->shipping_country;
            $vendor->shipping_state   = $request->shipping_state;
            $vendor->shipping_city    = $request->shipping_city;
            $vendor->shipping_phone   = $request->shipping_phone;
            $vendor->shipping_zip     = $request->shipping_zip;
            $vendor->shipping_address = $request->shipping_address;
            $vendor->save();
            if(module_is_active('CustomField'))
            {
                \Modules\CustomField\Entities\CustomField::saveData($vendor, $request->customField);
            }
            event(new UpdateVendor($request,$vendor));
            return redirect()->back()->with('success', __('Vendor successfully updated.'));
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
        $vendor     = Vender::where('user_id',$id)->where('workspace',getActiveWorkSpace())->first();
        if (Auth::user()->isAbleTo('vendor delete'))
        {
            if($vendor->workspace == getActiveWorkSpace())
            {
                if(module_is_active('CustomField')){
                    $customFields = \Modules\CustomField\Entities\CustomField::where('module','Account')->where('sub_module','Vendor')->get();
                    foreach($customFields as $customField)
                    {
                        $value = \Modules\CustomField\Entities\CustomFieldValue::where('record_id', '=', $vendor->id)->where('field_id',$customField->id)->first();
                        if(!empty($value)){

                            $value->delete();
                        }
                    }
                }
                event(new DestroyVendor($vendor));
                $vendor->delete();
                return redirect()->route('vendors.index')->with('success', __('Vendor successfully deleted.'));
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
    function vendorNumber()
    {
        $latest = Vender::where('workspace',getActiveWorkSpace())->latest()->first();
        if (!$latest)
        {
            return 1;
        }
        return $latest->vendor_id + 1;
    }
    public function statement(Request $request,$id)
    {
        $vendor = Vender::find($id);
        $bill= Bill::where('workspace','=',getActiveWorkSpace())->where('vendor_id','=',$vendor->id)->get()->pluck('id');
        $bill_payment =BillPayment::whereIn('bill_id',$bill);
        if(!empty($request->from_date)&& !empty($request->until_date))
        {
            $bill_payment->whereBetween('date',  [$request->from_date, $request->until_date]);
            $data['from_date']  = $request->from_date;
            $data['until_date'] = $request->until_date;
        }
        else
        {
            $data['from_date']  = date('Y-m-1');
            $data['until_date'] = date('Y-m-t');
            $bill_payment->whereBetween('date',  [$data['from_date'], $data['until_date']]);
        }
        $bill_payment=$bill_payment->get();
        $currencySymbol = !empty(company_setting('defult_currancy_symbol')) ? company_setting('defult_currancy_symbol') : '$' ;
        $responseData = [
            'data' => $bill_payment,
            'currencySymbol' => $currencySymbol,
        ];

        return response()->json($responseData);
    }
    public function grid()
    {
        if(Auth::user()->isAbleTo('vendor manage'))
        {
            $vendors = User::where('workspace_id',getActiveWorkSpace())
                        ->leftjoin('vendors', 'users.id', '=', 'vendors.user_id')
                        ->where('users.type', 'vendor')
                        ->select('users.*','vendors.*', 'users.name as name', 'users.email as email', 'users.id as id')
                        ->get();
            return view('account::vendor.grid', compact('vendors'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function fileImportExport()
    {
        if(Auth::user()->isAbleTo('vendor import'))
        {
            return view('account::vendor.import');
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function fileImport(Request $request)
    {
        if(Auth::user()->isAbleTo('vendor import'))
        {
            session_start();

            $error = '';

            $html = '';

            if ($request->file->getClientOriginalName() != '') {
                $file_array = explode(".", $request->file->getClientOriginalName());

                $extension = end($file_array);

                if ($extension == 'csv') {
                    $file_data = fopen($request->file->getRealPath(), 'r');

                    $file_header = fgetcsv($file_data);
                    $html .= '<table class="table table-bordered"><tr>';

                    for ($count = 0; $count < count($file_header); $count++) {
                        $html .= '
                                <th>
                                    <select name="set_column_data" class="form-control set_column_data" data-column_number="' . $count . '">
                                    <option value="">Set Count Data</option>
                                    <option value="name">Name</option>
                                    <option value="email">Email</option>
                                    <option value="password">Password</option>
                                    <option value="contact">Contact</option>
                                    <option value="tax_number">Tax Number</option>
                                    <option value="billing_name">Billing Name</option>
                                    <option value="billing_country">Billing Country</option>
                                    <option value="billing_state">Billing State</option>
                                    <option value="billing_city">Billing City</option>
                                    <option value="billing_phone">Billing Phone</option>
                                    <option value="billing_zip">Billing Zip</option>
                                    <option value="billing_address">Billing Address</option>
                                    <option value="shipping_name">Shipping Name</option>
                                    <option value="shipping_country">Shipping Country</option>
                                    <option value="shipping_state">Shipping State</option>
                                    <option value="shipping_city">Shipping City</option>
                                    <option value="shipping_phone">Shipping Phone</option>
                                    <option value="shipping_zip">Shipping Zip</option>
                                    <option value="shipping_address">Shipping Address</option>
                                    </select>
                                </th>
                                ';

                    }
                    $html .= '</tr>';
                    $limit = 0;

                    while (($row = fgetcsv($file_data)) !== false) {
                        $limit++;

                        $html .= '<tr>';

                        for ($count = 0; $count < count($row); $count++) {
                            $html .= '<td>' . $row[$count] . '</td>';
                        }

                        $html .= '</tr>';

                        $temp_data[] = $row;

                    }
                    $_SESSION['file_data'] = $temp_data;
                } else {
                    $error = 'Only <b>.csv</b> file allowed';
                }
            } else {

                $error = 'Please Select CSV File';
            }
            $output = array(
                'error' => $error,
                'output' => $html,
            );
            echo json_encode($output);
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }

    }

    public function fileImportModal()
    {
        if(Auth::user()->isAbleTo('vendor import'))
        {
            return view('account::vendor.import_modal');
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function vendorImportdata(Request $request)
    {
        if(Auth::user()->isAbleTo('vendor import'))
        {
            session_start();
            $html = '<h3 class="text-danger text-center">Below data is not inserted</h3></br>';
            $flag = 0;
            $html .= '<table class="table table-bordered"><tr>';
            $file_data = isset($_SESSION['file_data']) ? $_SESSION['file_data'] : null;
            if($file_data){
                unset($_SESSION['file_data']);

                $user = \Auth::user();

                $roles            = Role::where('created_by',creatorId())->where('name','vendor')->first();
                foreach ($file_data as $row) {

                    $vendor = Vender::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->Where('email', 'like',$row[$request->email])->get();

                    if($vendor->isEmpty()){

                        try {
                            $user = User::create(
                                [
                                    'name' =>$row[$request->name],
                                    'email' => $row[$request->email],
                                    'mobile_no' => $row[$request->contact],
                                    'password' => Hash::make($row[$request->password]),
                                    'email_verified_at' => date('Y-m-d h:i:s'),
                                    'type' => !empty($roles->name)?$roles->name:'vendor',
                                    'lang' => 'en',
                                    'workspace_id' => getActiveWorkSpace(),
                                    'active_workspace' =>getActiveWorkSpace(),
                                    'created_by' => creatorId(),
                                    ]
                                );
                                $user->addRole($roles->id);

                            Vender::create([
                                'vendor_id' => $this->vendorNumber(),
                                'user_id' => $user->id,
                                'name' => $row[$request->name],
                                'email' => $row[$request->email],
                                'password' => $row[$request->password],
                                'contact' => $row[$request->contact],
                                'tax_number' => $row[$request->tax_number],
                                'billing_name' => $row[$request->billing_name],
                                'billing_country' => $row[$request->billing_country],
                                'billing_state' => $row[$request->billing_state],
                                'billing_city' => $row[$request->billing_city],
                                'billing_phone' => $row[$request->billing_phone],
                                'billing_zip' => $row[$request->billing_zip],
                                'billing_address' => $row[$request->billing_address],
                                'shipping_name' => $row[$request->shipping_name],
                                'shipping_country' => $row[$request->shipping_country],
                                'shipping_state' => $row[$request->shipping_state],
                                'shipping_city' => $row[$request->shipping_city],
                                'shipping_phone' => $row[$request->shipping_phone],
                                'shipping_zip' => $row[$request->shipping_zip],
                                'shipping_address' => $row[$request->shipping_address],
                                'created_by' => creatorId(),
                                'workspace' => getActiveWorkSpace(),
                            ]);



                        }
                        catch (\Exception $e)
                        {
                            $flag = 1;
                            $html .= '<tr>';

                            $html .= '<td>' . $row[$request->name] . '</td>';
                            $html .= '<td>' . $row[$request->email] . '</td>';
                            $html .= '<td>' . $row[$request->password] . '</td>';
                            $html .= '<td>' . $row[$request->contact] . '</td>';
                            $html .= '<td>' . $row[$request->tax_number] . '</td>';
                            $html .= '<td>' . $row[$request->billing_name] . '</td>';
                            $html .= '<td>' . $row[$request->billing_country] . '</td>';
                            $html .= '<td>' . $row[$request->billing_state] . '</td>';
                            $html .= '<td>' . $row[$request->billing_city] . '</td>';
                            $html .= '<td>' . $row[$request->billing_phone] . '</td>';
                            $html .= '<td>' . $row[$request->billing_zip] . '</td>';
                            $html .= '<td>' . $row[$request->billing_address] . '</td>';
                            $html .= '<td>' . $row[$request->shipping_name] . '</td>';
                            $html .= '<td>' . $row[$request->shipping_country] . '</td>';
                            $html .= '<td>' . $row[$request->shipping_state] . '</td>';
                            $html .= '<td>' . $row[$request->shipping_city] . '</td>';
                            $html .= '<td>' . $row[$request->shipping_phone] . '</td>';
                            $html .= '<td>' . $row[$request->shipping_zip] . '</td>';
                            $html .= '<td>' . $row[$request->shipping_address] . '</td>';

                            $html .= '</tr>';
                        }
                    }
                    else
                    {
                        $flag = 1;
                        $html .= '<tr>';

                        $html .= '<td>' . $row[$request->name] . '</td>';
                        $html .= '<td>' . $row[$request->email] . '</td>';
                        $html .= '<td>' . $row[$request->password] . '</td>';
                        $html .= '<td>' . $row[$request->contact] . '</td>';
                        $html .= '<td>' . $row[$request->tax_number] . '</td>';
                        $html .= '<td>' . $row[$request->billing_name] . '</td>';
                        $html .= '<td>' . $row[$request->billing_country] . '</td>';
                        $html .= '<td>' . $row[$request->billing_state] . '</td>';
                        $html .= '<td>' . $row[$request->billing_city] . '</td>';
                        $html .= '<td>' . $row[$request->billing_phone] . '</td>';
                        $html .= '<td>' . $row[$request->billing_zip] . '</td>';
                        $html .= '<td>' . $row[$request->billing_address] . '</td>';
                        $html .= '<td>' . $row[$request->shipping_name] . '</td>';
                        $html .= '<td>' . $row[$request->shipping_country] . '</td>';
                        $html .= '<td>' . $row[$request->shipping_state] . '</td>';
                        $html .= '<td>' . $row[$request->shipping_city] . '</td>';
                        $html .= '<td>' . $row[$request->shipping_phone] . '</td>';
                        $html .= '<td>' . $row[$request->shipping_zip] . '</td>';
                        $html .= '<td>' . $row[$request->shipping_address] . '</td>';

                        $html .= '</tr>';
                    }
                }

                $html .= '
                                </table>
                                <br />
                                ';
                if ($flag == 1)
                {

                    return response()->json([
                                'html' => true,
                        'response' => $html,
                    ]);
                } else {
                    return response()->json([
                        'html' => false,
                        'response' => 'Data Imported Successfully',
                    ]);
                }
            }
            else{
                return redirect()->back()->with('Data not import please try again.');
            }
        }

        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }
}
