<?php

namespace App\Http\Controllers;

use App\Events\ConvertToInvoice;
use App\Events\CreateProposal;
use App\Events\DestroyProposal;
use App\Events\DuplicateProposal;
use App\Events\ResentProposal;
use App\Events\SentProposal;
use App\Events\StatusChangeProposal;
use App\Events\UpdateProposal;
use App\Models\Invoice;
use App\Models\Proposal;
use App\Models\ProposalProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\InvoiceProduct;
use App\Models\EmailTemplate;
use App\Models\ProposalAttechment;
use App\Models\Setting;
use Modules\CMMS\Entities\Location;
use Modules\CMMS\Entities\Workorder;
use Modules\ProductService\Entities\ProductService;

class ProposalController extends Controller
{
     /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if (Auth::user()->isAbleTo('proposal manage'))
        {
            $status = Proposal::$statues;
            $customer  = User::where('workspace_id', '=', getActiveWorkSpace())->where('type','Client')->get()->pluck('name', 'id');

            if(Auth::user()->type != 'company')
            {
                $query = Proposal::join('users', 'proposals.customer_id', '=', 'users.id')
                        ->where('users.id',Auth::user()->id)->select('proposals.*')
                        ->where('proposals.workspace',getActiveWorkSpace());
            }
            else
            {
                $query = Proposal::where('workspace',getActiveWorkSpace());
            }

            if (!empty($request->customer))
            {
                $query->where('customer_id', '=', $request->customer);

            }
            if (!empty($request->issue_date))
            {
                $date_range = explode('to', $request->issue_date);
                if(count($date_range) == 2)
                {
                    $query->whereBetween('issue_date',$date_range);
                }
                else
                {
                    $query->where('issue_date',$date_range[0]);
                }
            }

            if (!empty($request->status)) {

                $query->where('status', $request->status);
            }
            $proposals = $query->with('customers')->get();
            return view('proposal.index', compact('proposals', 'customer', 'status'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    public function Grid(Request $request)
    {
        if (Auth::user()->isAbleTo('proposal manage'))
        {
            $status = Proposal::$statues;
            $customer  = User::where('workspace_id', '=', getActiveWorkSpace())->where('type','Client')->get()->pluck('name', 'id');
            if(Auth::user()->type != 'company')
            {
                $query = Proposal::join('users', 'proposals.customer_id', '=', 'users.id')
                        ->where('users.id',Auth::user()->id)->select('proposals.*')
                        ->where('proposals.workspace',getActiveWorkSpace());
            }
            else
            {
                $query = Proposal::where('workspace',getActiveWorkSpace());
            }
            if (!empty($request->customer))
            {
                $query->where('customer_id', '=', $request->customer);

            }
            if (!empty($request->issue_date))
            {
                $date_range = explode('to', $request->issue_date);
                if(count($date_range) == 2)
                {
                    $query->whereBetween('issue_date',$date_range);
                }
                else
                {
                    $query->where('issue_date',$date_range[0]);
                }
            }

            if (!empty($request->status)) {

                $query->where('status', $request->status);
            }
            $proposals = $query->get();
            return view('proposal.grid', compact('proposals', 'customer', 'status'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($customerId)
    {
        if(module_is_active('ProductService'))
        {
            if (Auth::user()->isAbleTo('proposal create'))
            {

                $proposal_number = Proposal::proposalNumberFormat($this->proposalNumber());

                $customers   = User::where('workspace_id', '=', getActiveWorkSpace())->where('type','Client')->get()->pluck('name', 'id');
                $category=[];
                $product_services=[];
                $projects=[];
                $taxs=[];
                if(module_is_active('Account'))
                {
                    if ($customerId > 0) {
                        $temp_cm = \Modules\Account\Entities\Customer::where('id',$customerId)->first();
                        if($temp_cm)
                        {
                            $customerId = $temp_cm->user_id;
                        }
                        else
                        {
                            return redirect()->back()->with('error', __('Something went wrong please try again!'));
                        }
                    }
                    $category = \Modules\ProductService\Entities\Category::where('created_by', '=', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 1)->get()->pluck
                    ('name', 'id');
                    $product_services = \Modules\ProductService\Entities\ProductService::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
                }
                if(module_is_active('Taskly'))
                {
                    if(module_is_active('ProductService'))
                    {
                        $taxs = \Modules\ProductService\Entities\Tax::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
                    }
                    $projects = \Modules\Taskly\Entities\Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', Auth::user()->id)->where('workspace', getActiveWorkSpace())->projectonly()->get()->pluck('name', 'id');
                }

                $work_order = [];

                if(module_is_active('CMMS'))
                {
                    $work_order = Workorder::with('getLocation')->where(['company_id' => creatorId(), 'workspace' => getActiveWorkSpace(),  'status' => 1])->get()->pluck('wo_name','id');
                }
                if(module_is_active('CustomField')){
                    $customFields =  \Modules\CustomField\Entities\CustomField::where('workspace_id',getActiveWorkSpace())->where('module', '=', 'Base')->where('sub_module','Proposal')->get();
                }else{
                    $customFields = null;
                }
                return view('proposal.create', compact('customers', 'proposal_number', 'product_services', 'category', 'customerId','projects','taxs','customFields','work_order'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
        else
        {
            return redirect()->route('proposal.index')->with('error', __('Please Enable Product & Service Module'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (\Auth::user()->isAbleTo('proposal create'))
        {
            if($request->proposal_type == "product")
            {

                $validator = \Validator::make(
                    $request->all(),
                    [
                        'customer_id' => 'required',
                        'issue_date' => 'required',
                        'category_id' => 'required',
                        'items' => 'required',

                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                foreach ($request->items as $item) {
                    if (empty($item['item']) && $item['item'] == 0 ) {
                        return redirect()->back()->with('error', __('Please select an item'));
                    }
                }
                $status = Proposal::$statues;
                $proposal                 = new Proposal();
                $proposal->proposal_id    = $this->proposalNumber();
                $proposal->customer_id    = $request->customer_id;
                $proposal->account_type   = $request->account_type;
                $proposal->status         = 0;
                $proposal->issue_date     = $request->issue_date;
                $proposal->category_id    = $request->category_id;
                $proposal->proposal_template    = $request->proposal_template;
                $proposal->workspace       = getActiveWorkSpace();
                $proposal->created_by      = creatorId();
                $proposal->save();
                Invoice::starting_number($proposal->proposal_id + 1, 'proposal');

                if(module_is_active('CustomField'))
                    {
                        \Modules\CustomField\Entities\CustomField::saveData($proposal, $request->customField);
                    }
                $products = $request->items;
                for ($i = 0; $i < count($products); $i++)
                {
                    $proposalProduct                = new ProposalProduct();
                    $proposalProduct->proposal_id   = $proposal->id;
                    $proposalProduct->product_type  = $products[$i]['product_type'];
                    $proposalProduct->product_id    = $products[$i]['item'];
                    $proposalProduct->quantity      = $products[$i]['quantity'];
                    $proposalProduct->tax           = $products[$i]['tax'];
                    $proposalProduct->discount      = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
                    $proposalProduct->price         = $products[$i]['price'];
                    $proposalProduct->description   = str_replace( array( '\'', '"', '`','{',"\n"), ' ', $products[$i]['description']);
                    $proposalProduct->save();
                }
                  // first parameter request second parameter proposal
                event(new CreateProposal($request, $proposal));
                return redirect()->route('proposal.index')->with('success', __('Proposal successfully created.'));
            }
            else if($request->proposal_type == "project")
            {
                $validator = \Validator::make(
                    $request->all(), [

                                    'customer_id' => 'required',
                                    'issue_date' => 'required',
                                    'project' => 'required',
                                    'tax_project' => 'required',
                                    'items' => 'required',

                                ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $proposal                 = new Proposal();
                if(module_is_active('Account'))
                {
                    $customer = \Modules\Account\Entities\Customer::where('user_id', '=', $request->customer_id)->first();
                    $proposal->customer_id    = !empty($customer) ?  $customer->id : null;
                }

                $status = Proposal::$statues;
                $proposal->proposal_id     = $this->proposalNumber();
                $proposal->customer_id         = $request->customer_id;
                $proposal->status          = 0;
                $proposal->account_type   = $request->account_type;
                $proposal->proposal_module = 'taskly';
                $proposal->issue_date      = $request->issue_date;
                $proposal->category_id     = $request->project;
                $proposal->proposal_template    = $request->proposal_template;
                $proposal->workspace       = getActiveWorkSpace();
                $proposal->created_by      = creatorId();

                $proposal->save();

                $products = $request->items;

                Invoice::starting_number( $proposal->proposal_id + 1, 'proposal');

                if(module_is_active('CustomField'))
                {
                    \Modules\CustomField\Entities\CustomField::saveData($proposal, $request->customField);
                }
                $project_tax = implode(',',$request->tax_project);

                for($i = 0; $i < count($products); $i++)
                {
                    $proposalProduct               = new ProposalProduct();
                    $proposalProduct->proposal_id  = $proposal->id;
                    $proposalProduct->product_id   = $products[$i]['item'];
                    $proposalProduct->quantity     = 1;
                    $proposalProduct->tax          = $project_tax;
                    $proposalProduct->discount     = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
                    $proposalProduct->price        = $products[$i]['price'];
                    $proposalProduct->description  = $products[$i]['description'];
                    $proposalProduct->save();
                }
                // first parameter request second parameter proposal
                event(new CreateProposal($request, $proposal));

                return redirect()->route('proposal.index', $proposal->id)->with('success', __('Proposal successfully created.'));
            }
            else if($request->proposal_type == "parts")
            {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'customer_id' => 'required',
                        'issue_date' => 'required',
                        'work_order' => 'required',
                        'items' => 'required',

                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
                $status = Proposal::$statues;
                $proposal                 = new Proposal();
                $proposal->proposal_id    = $this->proposalNumber();
                $proposal->customer_id    = $request->customer_id;
                $proposal->account_type   = $request->account_type;
                $proposal->proposal_module = 'cmms';
                $proposal->status         = 0;
                $proposal->issue_date     = $request->issue_date;
                $proposal->category_id    = $request->work_order;
                $proposal->proposal_template    = $request->proposal_template;
                $proposal->workspace       = getActiveWorkSpace();
                $proposal->created_by      = creatorId();
                $proposal->save();
                Invoice::starting_number($proposal->proposal_id + 1, 'proposal');

                if(module_is_active('CustomField'))
                    {
                        \Modules\CustomField\Entities\CustomField::saveData($proposal, $request->customField);
                    }
                $products = $request->items;
                for ($i = 0; $i < count($products); $i++)
                {
                    $proposalProduct                = new ProposalProduct();
                    $proposalProduct->proposal_id   = $proposal->id;
                    $proposalProduct->product_type  = $products[$i]['product_type'];
                    $proposalProduct->product_id    = $products[$i]['item'];
                    $proposalProduct->quantity      = $products[$i]['quantity'];
                    $proposalProduct->tax           = $products[$i]['tax'];
                    $proposalProduct->discount      = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
                    $proposalProduct->price         = $products[$i]['price'];
                    $proposalProduct->description   = str_replace( array( '\'', '"', '`','{',"\n"), ' ', $products[$i]['description']);
                    $proposalProduct->save();
                }
                  // first parameter request second parameter proposal
                event(new CreateProposal($request, $proposal));
                return redirect()->route('proposal.index')->with('success', __('Proposal successfully created.'));
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
        if (Auth::user()->isAbleTo('proposal show'))
        {
            try {
                $id       = Crypt::decrypt($e_id);
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', __('Proposal Not Found.'));
            }
            $proposal = Proposal::find($id);
            if(!empty($proposal) && $proposal->workspace == getActiveWorkSpace())
            {
                $company_settings = getCompanyAllSetting();
                $user = $proposal->customer;
                $proposal_attachment = ProposalAttechment::where('proposal_id', $proposal->id)->get();
                $customer = [];
                if(module_is_active('Account') && !empty($user->id))
                {
                    $customer    = \Modules\Account\Entities\Customer::where('user_id',$user->id)->where('workspace',getActiveWorkSpace())->first();
                }
                $iteams   = $proposal->items;
                $status   = Proposal::$statues;
                if(module_is_active('CustomField')){
                    $proposal->customField = \Modules\CustomField\Entities\CustomField::getData($proposal, 'Base','Proposal');
                    $customFields      = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'Base')->where('sub_module','Proposal')->get();
                }else{
                    $customFields = null;
                }

                return view('proposal.view', compact('proposal', 'customer', 'iteams', 'status','customFields','proposal_attachment','company_settings'));
            } else {
                return redirect()->route('proposal.index')->with('error', __('Proposal Not Found.'));
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
        if (Auth::user()->isAbleTo('proposal edit'))
        {
            try {
                $id       = Crypt::decrypt($e_id);
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', __('Proposal Not Found.'));
            }
            $proposal        = Proposal::find($id);
            if($proposal->workspace == getActiveWorkSpace())
            {
                $proposal_number = Proposal::proposalNumberFormat($proposal->proposal_id);
                $customers       = User::where('workspace_id', '=', getActiveWorkSpace())->where('type','Client')->get()->pluck('name', 'id');
                if(module_is_active('ProductService'))
                {
                    $category = \Modules\ProductService\Entities\Category::where('created_by', '=', creatorId())->where('workspace_id', getActiveWorkSpace())->where('type', 1)->get()->pluck('name', 'id');

                }
                else
                {
                    $category=[];
                    $product_services=[];
                }


                $items = [];
                $taxs = [];
                $projects = [];
                if(module_is_active('Taskly'))
                {
                    if(module_is_active('ProductService'))
                    {
                        $taxs = \Modules\ProductService\Entities\Tax::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
                    }
                    $projects = \Modules\Taskly\Entities\Project::where('workspace', getActiveWorkSpace())->projectonly()->get()->pluck('name', 'id');
                }
                foreach ($proposal->items as $proposalItem)
                {
                    $itemAmount               = $proposalItem->quantity * $proposalItem->price;
                    $proposalItem->itemAmount = $itemAmount;
                    $proposalItem->taxes      = Proposal::tax($proposalItem->tax);
                    $items[]                  = $proposalItem;
                }

                $work_order = [];
                if(module_is_active('CMMS'))
                {
                    $work_order = WorkOrder::with('getLocation')->where(['company_id' => creatorId(), 'workspace' => getActiveWorkSpace(),  'status' => 1])->get()->pluck('wo_name','id');
                }
                if(module_is_active('CustomField')){
                    $proposal->customField = \Modules\CustomField\Entities\CustomField::getData($proposal, 'Base','Proposal');
                    $customFields             = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'Base')->where('sub_module','Proposal')->get();
                }else{
                    $customFields = null;
                }
                return view('proposal.edit', compact('customers','proposal', 'proposal_number', 'category', 'items','taxs','projects','customFields','work_order'));
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

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Proposal $proposal)
    {
        if (Auth::user()->isAbleTo('proposal edit'))
        {
            if($proposal->workspace == getActiveWorkSpace())
            {
                if($request->proposal_type == "product")
                {
                    $validator = \Validator::make(
                        $request->all(),
                        [
                            'customer_id' => 'required',
                            'issue_date' => 'required',
                            'category_id' => 'required',
                            'items' => 'required',
                        ]
                    );
                    if ($validator->fails()) {
                        $messages = $validator->getMessageBag();

                        return redirect()->route('proposal.index')->with('error', $messages->first());
                    }
                    $proposal->customer_id        = $request->customer_id;
                    $proposal->issue_date         = $request->issue_date;
                    $proposal->category_id        = $request->category_id;
                    $proposal->account_type   = $request->account_type;
                    $proposal->proposal_module    = 'account';
                    $proposal->proposal_template    = $request->proposal_template;
                    $proposal->save();
                    if(module_is_active('CustomField'))
                    {
                        \Modules\CustomField\Entities\CustomField::saveData($proposal, $request->customField);
                    }
                    $products = $request->items;

                    for ($i = 0; $i < count($products); $i++)
                    {
                        $proposalProduct = ProposalProduct::find($products[$i]['id']);
                        if ($proposalProduct == null) {
                            $proposalProduct                = new ProposalProduct();
                            $proposalProduct->proposal_id   = $proposal->id;
                        }

                        if (isset($products[$i]['item'])) {
                            $proposalProduct->product_id    = $products[$i]['item'];
                        }
                        $proposalProduct->product_type      = $products[$i]['product_type'];
                        $proposalProduct->quantity          = $products[$i]['quantity'];
                        $proposalProduct->tax               = $products[$i]['tax'];
                        $proposalProduct->discount          = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
                        $proposalProduct->price             = $products[$i]['price'];
                        $proposalProduct->description       = str_replace( array( '\'', '"', '`','{',"\n"), ' ', $products[$i]['description']);
                        $proposalProduct->save();
                    }

                    // first parameter request second parameter proposal
                    event(new UpdateProposal($request, $proposal));

                    return redirect()->route('proposal.index')->with('success', __('Proposal successfully updated.'));

                }
                else if($request->proposal_type == "project")
                {
                    $validator = \Validator::make(
                        $request->all(), [
                                        'customer_id' => 'required',
                                        'issue_date' => 'required',
                                        'project' => 'required',
                                        'tax_project' => 'required',
                                        'items' => 'required',

                                    ]
                    );
                    if($validator->fails())
                    {
                        $messages = $validator->getMessageBag();

                        return redirect()->back()->with('error', $messages->first());
                    }

                    if(module_is_active('Account'))
                    {
                        $customer = \Modules\Account\Entities\Customer::where('user_id', '=', $request->customer_id)->first();
                        $proposal->customer_id    = !empty($customer) ?  $customer->id : null;
                    }
                    if($request->proposal_type != $proposal->proposal_module)
                    {
                        ProposalProduct::where('proposal_id', '=', $proposal->id)->delete();
                    }

                    $status = Proposal::$statues;
                    $proposal->proposal_id        = $proposal->proposal_id;
                    $proposal->customer_id        = $request->customer_id;
                    $proposal->issue_date         = $request->issue_date;
                    $proposal->account_type   = $request->account_type;
                    $proposal->category_id        = $request->project;
                    $proposal->proposal_module    = 'taskly';
                    $proposal->proposal_template    = $request->proposal_template;
                    $proposal->save();

                    $products = $request->items;

                    if(module_is_active('CustomField'))
                    {
                        \Modules\CustomField\Entities\CustomField::saveData($proposal, $request->customField);
                    }
                    $project_tax = implode(',',$request->tax_project);
                    for($i = 0; $i < count($products); $i++)
                    {
                        $proposalProduct = ProposalProduct::find($products[$i]['id']);
                        if($proposalProduct == null)
                        {
                            $proposalProduct             = new ProposalProduct();
                            $proposalProduct->proposal_id = $proposal->id;
                        }
                        $proposalProduct->product_id  = $products[$i]['item'];
                        $proposalProduct->quantity    = 1;
                        $proposalProduct->tax         = $project_tax;
                        $proposalProduct->discount    = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
                        $proposalProduct->price       = $products[$i]['price'];
                        $proposalProduct->description = $products[$i]['description'];
                        $proposalProduct->save();
                    }
                     // first parameter request second parameter proposal
                     event(new UpdateProposal($request, $proposal));
                }
                else if($request->proposal_type == "parts")
                {
                    $validator = \Validator::make(
                        $request->all(), [
                            'customer_id' => 'required',
                            'issue_date' => 'required',
                            'work_order' => 'required',
                            'items' => 'required',
                        ]
                    );

                    if ($validator->fails()) {
                        $messages = $validator->getMessageBag();

                        return redirect()->route('proposal.index')->with('error', $messages->first());
                    }
                    $proposal->customer_id        = $request->customer_id;
                    $proposal->issue_date         = $request->issue_date;
                    $proposal->category_id        = $request->work_order;
                    $proposal->account_type   = $request->account_type;
                    $proposal->proposal_module    = 'cmms';
                    $proposal->proposal_template    = $request->proposal_template;
                    $proposal->save();
                    if(module_is_active('CustomField'))
                    {
                        \Modules\CustomField\Entities\CustomField::saveData($proposal, $request->customField);
                    }
                    $products = $request->items;

                    for ($i = 0; $i < count($products); $i++)
                    {
                        $proposalProduct = ProposalProduct::find($products[$i]['id']);
                        if ($proposalProduct == null) {
                            $proposalProduct                = new ProposalProduct();
                            $proposalProduct->proposal_id   = $proposal->id;
                        }

                        if (isset($products[$i]['item'])) {
                            $proposalProduct->product_id    = $products[$i]['item'];
                        }
                        $proposalProduct->product_type      = $products[$i]['product_type'];
                        $proposalProduct->quantity          = $products[$i]['quantity'];
                        $proposalProduct->tax               = $products[$i]['tax'];
                        $proposalProduct->discount          = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
                        $proposalProduct->price             = $products[$i]['price'];
                        $proposalProduct->description       = str_replace( array( '\'', '"', '`','{',"\n"), ' ', $products[$i]['description']);
                        $proposalProduct->save();
                    }

                    // first parameter request second parameter proposal
                    event(new UpdateProposal($request, $proposal));

                    return redirect()->route('proposal.index')->with('success', __('Proposal successfully updated.'));
                }
                return redirect()->route('proposal.index')->with('success', __('Proposal successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Proposal $proposal)
    {
        if (Auth::user()->isAbleTo('proposal delete'))
        {
            if($proposal->workspace == getActiveWorkSpace())
            {
                ProposalProduct::where('proposal_id', '=', $proposal->id)->delete();
                if(module_is_active('CustomField')){
                    $customFields = \Modules\CustomField\Entities\CustomField::where('module','Base')->where('sub_module','Proposal')->get();
                    foreach($customFields as $customField)
                    {
                        $value = \Modules\CustomField\Entities\CustomFieldValue::where('record_id', '=', $proposal->id)->where('field_id',$customField->id)->first();
                        if(!empty($value)){
                            $value->delete();
                        }
                    }
                }
                // first parameter proposal
                event(new DestroyProposal($proposal));
                $proposal->delete();

                return redirect()->route('proposal.index')->with('success', __('Proposal successfully deleted.'));
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
    function proposalNumber()
    {

        $latest = company_setting('proposal_starting_number');
        if($latest == null)
        {
            return 1;
        }
        else
        {
            return $latest;
        }
    }
    public function customer(Request $request)
    {
        if(module_is_active('Account'))
        {
            $customer = \Modules\Account\Entities\Customer::where('user_id', '=', $request->id)->first();
            if(empty($customer))
            {
                $user = User::where('id',$request->id)->where('workspace_id',getActiveWorkSpace())->where('created_by',creatorId())->first();
                $customer['name'] = !empty($user->name) ? $user->name : '';
                $customer['email'] = !empty($user->email) ? $user->email : '';
            }
        }
        else
        {
            $user = User::where('id',$request->id)->where('workspace_id',getActiveWorkSpace())->where('created_by',creatorId())->first();
            $customer['name'] = !empty($user->name) ? $user->name : '';
            $customer['email'] = !empty($user->email) ? $user->email : '';
        }
        return view('proposal.customer_detail', compact('customer'));
    }
    public function product(Request $request)
    {
        $data['product']     = $product = \Modules\ProductService\Entities\ProductService::find($request->product_id);
        $data['unit']        = !empty($product) ? ((!empty($product->unit())) ? $product->unit()->name : '') : '';
        $data['taxRate']     = $taxRate = !empty($product) ? (!empty($product->tax_id) ? $product->taxRate($product->tax_id) : 0 ): 0;
        $data['taxes']       =  !empty($product) ? ( !empty($product->tax_id) ? $product->tax($product->tax_id) : 0) : 0;
        $salePrice           = !empty($product) ?  $product->sale_price : 0;
        $quantity            = 1;
        $taxPrice            = !empty($product) ? (($taxRate / 100) * ($salePrice * $quantity)) : 0;
        $data['totalAmount'] = !empty($product) ?  ($salePrice * $quantity) : 0;

        return json_encode($data);
    }
    public function convert($proposal_id)
    {
        if(Auth::user()->isAbleTo('proposal convert invoice'))
        {
            $proposal                     = Proposal::where('id', $proposal_id)->first();
            $proposal->is_convert         = 1;
            $convertInvoice                      = new Invoice();

            if($proposal->account_type == 'Projects')
            {
                $account_type = 'Taskly';
            }
            else
            {
                $account_type = 'Account';
            }

            if(module_is_active('Account'))
            {
                $customer = \Modules\Account\Entities\Customer::where('user_id',$proposal['customer_id'])->first();
                $convertInvoice->customer_id    = !empty($customer) ?  $customer->id : null;
            }
            $convertInvoice->invoice_id          = $this->invoiceNumber();
            $convertInvoice->user_id             = $proposal->customer_id;
            $convertInvoice->account_type        = $account_type;
            $convertInvoice->issue_date          = date('Y-m-d');
            $convertInvoice->due_date            = date('Y-m-d');
            $convertInvoice->send_date           = null;
            $convertInvoice->category_id         = $proposal['category_id'];
            $convertInvoice->status              = 0;
            $convertInvoice->invoice_module      = $proposal['proposal_module'];
            $convertInvoice->invoice_template      = $proposal['proposal_template'];
            $convertInvoice->workspace           = $proposal['workspace'];
            $convertInvoice->created_by          = $proposal['created_by'];
            $convertInvoice->save();
            Invoice::starting_number( $convertInvoice->invoice_id + 1, 'invoice');
            $proposal->converted_invoice_id = $convertInvoice->id;
            $proposal->save();


            if($convertInvoice)
            {
                $proposalProduct = ProposalProduct::where('proposal_id', $proposal_id)->get();
                foreach($proposalProduct as $product)
                {
                    $duplicateProduct             = new InvoiceProduct();
                    $duplicateProduct->invoice_id = $convertInvoice->id;
                    $duplicateProduct->product_type = $product->product_type;
                    $duplicateProduct->product_id = $product->product_id;
                    $duplicateProduct->quantity   = $product->quantity;
                    $duplicateProduct->tax        = $product->tax;
                    $duplicateProduct->discount   = $product->discount;
                    $duplicateProduct->price      = $product->price;
                    $duplicateProduct->description    = str_replace( array( '\'', '"', '`','{',"\n"), ' ', $product->description);


                    $duplicateProduct->save();


                    //inventory management (Quantity)
                    if($proposal['proposal_module'] == 'account'){
                        Invoice::total_quantity('minus',$duplicateProduct->quantity,$duplicateProduct->product_id);
                    }

                    //Product Stock Report
                    if(module_is_active('Account'))
                    {
                        $type='invoice';
                        $type_id = $convertInvoice->id;
                        \Modules\Account\Entities\StockReport::where('type','=','invoice')->where('type_id' ,'=', $convertInvoice->id)->delete();
                        $description= $duplicateProduct->quantity.''.__(' quantity sold in').' ' . Proposal::proposalNumberFormat($proposal->proposal_id).' '.__('Proposal convert to invoice').' '. Invoice::invoiceNumberFormat($convertInvoice->invoice_id);
                        \Modules\Account\Entities\AccountUtility::addProductStock( $duplicateProduct->product_id,$duplicateProduct->quantity,$type,$description,$type_id);
                    }
                }
            }
            event(new ConvertToInvoice($convertInvoice));

            return redirect()->back()->with('success', __('Proposal to invoice convert successfully.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function duplicate($proposal_id)
    {
        if (Auth::user()->isAbleTo('proposal duplicate'))
        {
            $proposal                               = Proposal::where('id', $proposal_id)->first();
            $duplicateProposal                      = new Proposal();
            $duplicateProposal->account_type      =  $proposal['account_type'];
            $duplicateProposal->proposal_id         = $this->proposalNumber();
            $duplicateProposal->customer_id         = $proposal['customer_id'];
            $duplicateProposal->issue_date          = date('Y-m-d');
            $duplicateProposal->send_date           = null;
            $duplicateProposal->category_id         = $proposal['category_id'];
            $duplicateProposal->status              = 0;
            $duplicateProposal->proposal_module     = $proposal['proposal_module'];
            $duplicateProposal->proposal_template     = $proposal['proposal_template'];
            $duplicateProposal->created_by          = $proposal['created_by'];
            $duplicateProposal->workspace           = $proposal['workspace'];
            $duplicateProposal->save();
            Invoice::starting_number($duplicateProposal->proposal_id + 1, 'proposal');

            if ($duplicateProposal)
            {
                $proposalProduct = ProposalProduct::where('proposal_id', $proposal_id)->get();
                foreach ($proposalProduct as $product)
                {
                    $duplicateProduct                   = new ProposalProduct();
                    $duplicateProduct->proposal_id      = $duplicateProposal->id;
                    $duplicateProduct->product_type     = $product->product_type;
                    $duplicateProduct->product_id       = $product->product_id;
                    $duplicateProduct->quantity         = $product->quantity;
                    $duplicateProduct->tax              = $product->tax;
                    $duplicateProduct->discount         = $product->discount;
                    $duplicateProduct->price            = $product->price;
                    $duplicateProduct->save();
                }
            }
            event(new DuplicateProposal($duplicateProposal));
            return redirect()->back()->with('success', __('Proposal duplicate successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function items(Request $request)
    {
        $items = ProposalProduct::where('proposal_id', $request->proposal_id)->where('product_id', $request->product_id)->first();

        return json_encode($items);
    }
    public function payproposal($proposal_id)
    {
        if(!empty($proposal_id))
        {
            try {
                $id = \Illuminate\Support\Facades\Crypt::decrypt($proposal_id);
            } catch (\Throwable $th) {
                return redirect('login');
            }

            $proposal = Proposal::where('id',$id)->first();
            if(!is_null($proposal))
            {
                $items         = [];
                $totalTaxPrice = 0;
                $totalQuantity = 0;
                $totalRate     = 0;
                $totalDiscount = 0;
                $taxesData     = [];

                foreach($proposal->items as $item)
                {
                    $totalQuantity += $item->quantity;
                    $totalRate     += $item->price;
                    $totalDiscount += $item->discount;
                    $taxes         = Proposal::tax($item->tax);
                    $itemTaxes = [];
                    foreach($taxes as $tax)
                    {

                        if(!empty($tax))
                        {
                            $taxPrice            = Proposal::taxRate($tax->rate, $item->price, $item->quantity,$item->discount);
                            $totalTaxPrice       += $taxPrice;
                            $itemTax['tax_name'] = $tax->tax_name;
                            $itemTax['tax']      = $tax->rate . '%';
                            $itemTax['price']    = currency_format_with_sym($taxPrice,$proposal->created_by);
                            $itemTaxes[]         = $itemTax;

                            if(array_key_exists($tax->name, $taxesData))
                            {
                                $taxesData[$itemTax['tax_name']] = $taxesData[$tax->tax_name] + $taxPrice;
                            }
                            else
                            {
                                $taxesData[$tax->tax_name] = $taxPrice;
                            }

                        }
                        else
                        {
                            $taxPrice            = Proposal::taxRate(0, $item->price, $item->quantity,$item->discount);
                            $totalTaxPrice       += $taxPrice;
                            $itemTax['tax_name'] = 'No Tax';
                            $itemTax['tax']      = '';
                            $itemTax['price']    = currency_format_with_sym($taxPrice,$proposal->created_by);
                            $itemTaxes[]         = $itemTax;

                            if(array_key_exists('No Tax', $taxesData))
                            {
                                $taxesData[$tax->tax_name] = $taxesData['No Tax'] + $taxPrice;
                            }
                            else
                            {
                                $taxesData['No Tax'] = $taxPrice;
                            }

                        }
                    }

                    $item->itemTax = $itemTaxes;
                    $items[]       = $item;
                }
                $proposal->items         = $items;
                $proposal->totalTaxPrice = $totalTaxPrice;
                $proposal->totalQuantity = $totalQuantity;
                $proposal->totalRate     = $totalRate;
                $proposal->totalDiscount = $totalDiscount;
                $proposal->taxesData     = $taxesData;
                $ownerId = $proposal->created_by;

                $users = User::where('id',$proposal->created_by)->first();

                if(!is_null($users))
                {
                    \App::setLocale($users->lang);
                }
                else
                {
                    \App::setLocale('en');
                }

                $proposal    = Proposal::where('id', $id)->first();
                $customer = $proposal->customer;
                $item   = $proposal->items;

                $company_payment_setting =[];

                if(module_is_active('Account'))
                {
                    $customer = \Modules\Account\Entities\Customer::where('user_id',$proposal->customer_id)->first();
                }
                else
                {
                    $customer = $proposal->customer;
                }
                if(module_is_active('CustomField')){
                    $proposal->customField = \Modules\CustomField\Entities\CustomField::getData($proposal, 'Base','Proposal');
                    $customFields             = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', $proposal->workspace)->where('module', '=', 'Base')->where('sub_module','Proposal')->get();
                }else{
                    $customFields = null;
                }

                $company_settings = getCompanyAllSetting($proposal->created_by,$proposal->workspace);
                $company_id = $proposal->created_by;
                $workspace_id = $proposal->workspace;
                return view('proposal.proposalpay',compact('proposal','item','customer','users','company_payment_setting','customFields','company_id','workspace_id','company_settings'));
            }
            else
            {
                return abort('404', 'The Link You Followed Has Expired');
            }
        }else{
            return abort('404', 'The Link You Followed Has Expired');
        }
    }

    public function statusChange(Request $request, $id)
    {
        $status           = $request->status;
        $proposal         = Proposal::find($id);
        $proposal->status = $status;
        $proposal->save();

         // first parameter request second  proposal
        event(new StatusChangeProposal($request,$proposal));

        return redirect()->back()->with('success', __('Proposal status changed successfully.'));
    }
    public function resent($id)
    {
        if (Auth::user()->isAbleTo('proposal send'))
        {
            $proposal = Proposal::where('id', $id)->first();

            $customer           = User::where('id', $proposal->customer_id)->first();
            $proposal->name     = !empty($customer) ? $customer->name : '';
            $proposal->proposal = Proposal::proposalNumberFormat($proposal->proposal_id);

            $proposalId    = Crypt::encrypt($proposal->id);
            $proposal->url = route('proposal.pdf', $proposalId);

            // first parameter proposal
            event(new ResentProposal($proposal));
            //Email notification
            if((!empty(company_setting('Proposal Status Updated')) && company_setting('Proposal Status Updated')  == true) )
            {
                $uArr = [
                    'proposal_name' => $proposal->name,
                    'proposal_number' => $proposal->proposal,
                    'proposal_url' => $proposal->url,
                ];

                try
                {
                    $resp = EmailTemplate::sendEmailTemplate('Proposal Send', [$customer->id => $customer->email],$uArr);
                }
                catch(\Exception $e)
                {
                    $resp['error'] = $e->getMessage();
                }
                return redirect()->back()->with('success', __('Proposal successfully sent.') . ((isset($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }
            return redirect()->back()->with('success', __('Proposal sent email notification is off.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function sent($id)
    {

        if (\Auth::user()->isAbleTo('proposal send'))
        {
            $proposal            = Proposal::where('id', $id)->first();
            $proposal->send_date = date('Y-m-d');
            $proposal->status    = 1;
            $proposal->save();
            if(module_is_active('Account'))
            {
                $customer         = \Modules\Account\Entities\Customer::where('user_id', $proposal->customer_id)->first();
                if(empty($customer))
                {
                    $customer         = User::where('id', $proposal->customer_id)->first();
                }
            }
            else
            {
                $customer         = User::where('id', $proposal->customer_id)->first();

            }
            $proposal->name     = !empty($customer) ? $customer->name : '';

            $proposal->proposal = Proposal::proposalNumberFormat($proposal->proposal_id);

            $proposalId    = Crypt::encrypt($proposal->id);
            $proposal->url = route('proposal.pdf', $proposalId);

            // first parameter proposal
            event(new SentProposal($proposal));

            //Email notification
            if(!empty(company_setting('Proposal Status Updated')) && company_setting('Proposal Status Updated')  == true)
            {
                $uArr = [
                    'proposal_name' => $proposal->name,
                    'proposal_number' => $proposal->proposal,
                    'proposal_url' => $proposal->url,
                ];
                try
                {
                    $resp = EmailTemplate::sendEmailTemplate('Proposal Send', [$customer->id => $customer->email],$uArr);
                }
                catch(\Exception $e)
                {
                    $resp['error'] = $e->getMessage();
                }
                return redirect()->back()->with('success', __('Proposal successfully sent.') . ((isset($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }
            return redirect()->back()->with('success', __('Proposal sent email notification is off.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function proposal($proposal_id)
    {
        $proposalId = Crypt::decrypt($proposal_id);
        $proposal   = Proposal::where('id', $proposalId)->first();

        if(module_is_active('Account'))
        {
            $customer         = \Modules\Account\Entities\Customer::where('user_id', $proposal->customer_id)->first();
        }
        else
        {
            $customer         = User::where('id', $proposal->customer_id)->first();
        }

        $items         = [];
        $totalTaxPrice = 0;
        $totalQuantity = 0;
        $totalRate     = 0;
        $totalDiscount = 0;
        $taxesData     = [];
        foreach ($proposal->items as $product) {
            $item              = new \stdClass();
            if($proposal->proposal_module == "taskly")
            {
                $item->name        = !empty($product->product())?$product->product()->title:'';
            }
            elseif($proposal->proposal_module == "account")
            {
                $item->name        = !empty($product->product()) ? $product->product()->name : '';
                $item->product_type   = !empty($product->product_type) ? $product->product_type : '';
            }
            $item->quantity    = $product->quantity;
            $item->tax         = $product->tax;
            $item->discount    = $product->discount;
            $item->price       = $product->price;
            $item->description = $product->description;

            $totalQuantity += $item->quantity;
            $totalRate     += $item->price;
            $totalDiscount += $item->discount;

            if(module_is_active('ProductService'))
            {
                $taxes = \Modules\ProductService\Entities\Tax::tax($product->tax);

                $itemTaxes = [];
                if(!empty($item->tax))
                {
                    $tax_price = 0;
                    foreach($taxes as $tax)
                    {
                        $taxPrice      = Invoice::taxRate($tax->rate, $item->price, $item->quantity,$item->discount);
                        $tax_price  += $taxPrice;
                        $totalTaxPrice += $taxPrice;

                        $itemTax['name']  = $tax->name;
                        $itemTax['rate']  = $tax->rate . '%';
                        $itemTax['price'] = currency_format_with_sym($taxPrice,$proposal->created_by);
                        $itemTaxes[]      = $itemTax;


                        if(array_key_exists($tax->name, $taxesData))
                        {
                            $taxesData[$tax->name] = $taxesData[$tax->name] + $taxPrice;
                        }
                        else
                        {
                            $taxesData[$tax->name] = $taxPrice;
                        }
                    }
                    $item->itemTax = $itemTaxes;
                    $item->tax_price = $tax_price;

                }
                else
                {
                    $item->itemTax = [];
                }

                $items[] = $item;
            }
        }
        $proposal->itemData      = $items;
        $proposal->totalTaxPrice = $totalTaxPrice;
        $proposal->totalQuantity = $totalQuantity;
        $proposal->totalRate     = $totalRate;
        $proposal->totalDiscount = $totalDiscount;
        $proposal->taxesData     = $taxesData;

        if(module_is_active('CustomField')){
            $proposal->customField = \Modules\CustomField\Entities\CustomField::getData($proposal, 'Base','Proposal');
            $customFields             = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', $proposal->workspace)->where('module', '=', 'Base')->where('sub_module','Proposal')->get();
        }else{
            $customFields = null;
        }


        //Set your logo
        $company_logo = get_file(sidebar_logo());
        $company_settings = getCompanyAllSetting($proposal->created_by,$proposal->workspace);
        $proposal_logo = isset($company_settings['proposal_logo']) ? $company_settings['proposal_logo'] : '';
        if(isset($proposal_logo) && !empty($proposal_logo))
        {
            $img  = get_file($proposal_logo);
        }
        else{
            $img  = $company_logo;
        }
        if ($proposal) {
            $color      = '#'.(!empty($company_settings['proposal_color']) ? $company_settings['proposal_color'] : 'ffffff');
            $font_color = User::getFontColor($color);


            if(!empty($proposal->proposal_template))
            {
                $proposal_template = $proposal->proposal_template;
            }
            else{
            $proposal_template  = (!empty($company_settings['proposal_template']) ? $company_settings['proposal_template'] : 'template1');
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
            $settings['proposal_footer_title'] = isset($company_settings['proposal_footer_title']) ? $company_settings['proposal_footer_title'] : '';
            $settings['proposal_footer_notes'] = isset($company_settings['proposal_footer_notes']) ? $company_settings['proposal_footer_notes'] : '';
            $settings['proposal_shipping_display'] = isset($company_settings['proposal_shipping_display']) ? $company_settings['proposal_shipping_display'] : '';
            $settings['proposal_template'] = isset($company_settings['proposal_template']) ? $company_settings['proposal_template'] : '';
            $settings['proposal_color'] = isset($company_settings['proposal_color']) ? $company_settings['proposal_color'] : '';
            $settings['proposal_qr_display'] = isset($company_settings['proposal_qr_display']) ? $company_settings['proposal_qr_display'] : '';

            return view('proposal.templates.' .$proposal_template, compact('proposal', 'color', 'settings', 'customer', 'img', 'font_color','customFields'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    function invoiceNumber()
    {
        $latest = company_setting('invoice_starting_number');
        if($latest == null)
        {
            return 1;
        }
        else
        {
            return $latest;
        }
    }
    public function saveTemplateSettings(Request $request)
    {
        $user = Auth::user();
        if($request->hasFile('proposal_logo'))
        {
            $proposal_logo         = $user->id.'_proposal_logo'.time().'.png';

            $uplaod = upload_file($request,'proposal_logo',$proposal_logo,'proposal_logo');
            if($uplaod['flag'] == 1)
            {
                $url = $uplaod['url'];
                $old_proposal_logo = company_setting('proposal_logo');
                if(!empty($old_proposal_logo) && check_file($old_proposal_logo))
                {
                    delete_file($old_proposal_logo);
                }
            }
            else
            {
                return redirect()->back()->with('error',$uplaod['msg']);
            }
        }
        $post = $request->all();
        unset($post['_token']);

        if (isset($post['proposal_template']) && (!isset($post['proposal_color']) || empty($post['proposal_color'])))
        {
            $post['proposal_color'] = "ffffff";
        }
        if(isset($post['proposal_logo']))
        {
            $post['proposal_logo'] = $url;
        }
        if(!isset($post['proposal_shipping_display']))
        {
            $post['proposal_shipping_display'] = 'off';
        }
        if(!isset($post['proposal_qr_display']))
        {
            $post['proposal_qr_display'] = 'off';
        }
        foreach ($post as $key => $value) {
            // Define the data to be updated or inserted
            $data = [
                'key' => $key,
                'workspace' => getActiveWorkSpace(),
                'created_by' => creatorId(),
            ];
            // Check if the record exists, and update or insert accordingly
            Setting::updateOrInsert($data, ['value' => $value]);
        }
        // Settings Cache forget
        comapnySettingCacheForget();
        return redirect()->back()->with('success', __('Proposal Print setting save sucessfully.'));
    }
    public function previewInvoice($template, $color)
    {
        $proposal = new Proposal();

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

        $totalTaxPrice = 0;
        $taxesData     = [];

        $items = [];
        for($i = 1; $i <= 3; $i++)
        {
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
            foreach($taxes as $k => $tax)
            {
                $taxPrice         = 10;
                $totalTaxPrice    += $taxPrice;
                $itemTax['name']  = 'Tax ' . $k;
                $itemTax['rate']  = '10 %';
                $itemTax['price'] = '$10';
                $itemTaxes[]      = $itemTax;
                if(array_key_exists('Tax ' . $k, $taxesData))
                {
                    $taxesData['Tax ' . $k] = $taxesData['Tax 1'] + $taxPrice;
                }
                else
                {
                    $taxesData['Tax ' . $k] = $taxPrice;
                }
            }
            $item->itemTax = $itemTaxes;
            $item->tax_price = 10;
            $items[]       = $item;
        }

        $proposal->proposal_id = 1;
        $proposal->issue_date  = date('Y-m-d H:i:s');
        $proposal->due_date    = date('Y-m-d H:i:s');
        $proposal->itemData    = $items;

        $proposal->totalTaxPrice = 60;
        $proposal->totalQuantity = 3;
        $proposal->totalRate     = 300;
        $proposal->totalDiscount = 10;
        $proposal->taxesData     = $taxesData;
        $proposal->customField   = [];
        $customFields            = [];

        $preview    = 1;
        $color      = '#' . $color;
        $font_color = User::getFontColor($color);

        $company_logo = get_file(sidebar_logo());
        $company_settings = getCompanyAllSetting();

        $proposal_logo =  isset($company_settings['proposal_logo']) ? $company_settings['proposal_logo'] : '';

        if(!empty($proposal_logo))
        {
            $img = get_file($proposal_logo);
        }
        else{
            $img          =  $company_logo;
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
        $settings['proposal_footer_title'] = isset($company_settings['proposal_footer_title']) ? $company_settings['proposal_footer_title'] : '';
        $settings['proposal_footer_notes'] = isset($company_settings['proposal_footer_notes']) ? $company_settings['proposal_footer_notes'] : '';
        $settings['proposal_shipping_display'] = isset($company_settings['proposal_shipping_display']) ? $company_settings['proposal_shipping_display'] : '';
        $settings['proposal_template'] = isset($company_settings['proposal_template']) ? $company_settings['proposal_template'] : '';
        $settings['proposal_color'] = isset($company_settings['proposal_color']) ? $company_settings['proposal_color'] : '';
        $settings['proposal_qr_display'] = isset($company_settings['proposal_qr_display']) ? $company_settings['proposal_qr_display'] : '';

        return view('proposal.templates.' . $template, compact('proposal', 'preview', 'color', 'img', 'settings', 'customer', 'font_color', 'customFields'));
    }
    public function ProposalSectionGet(Request $request)
    {
        $type = $request->type;
        $acction = $request->acction;
        $proposal = [];
        if($acction == 'edit')
        {
            $proposal = Proposal::find($request->proposal_id);
        }

        if($request->type == "product" && module_is_active('Account'))
        {
            $product_services = \Modules\ProductService\Entities\ProductService::where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
            $product_services_count =$product_services->count();
            $product_type = ProductService::$product_type;
            $returnHTML = view('proposal.section',compact('product_services','product_type','type' ,'acction','proposal','product_services_count'))->render();
                $response = [
                    'is_success' => true,
                    'message' => '',
                    'html' => $returnHTML,
                ];
            return response()->json($response);
        }
        elseif($request->type == "project" && module_is_active('Taskly'))
        {
            $projects = \Modules\Taskly\Entities\Project::where('workspace', getActiveWorkSpace())->projectonly();
            if($request->project_id != 0)
            {
                $projects = $projects->where('id',$request->project_id);
            }
            $projects = $projects->first();
            $tasks=[];
            if(!empty($projects)){

                $tasks = \Modules\Taskly\Entities\Task::where('project_id', $projects->id)->get()->pluck('title', 'id');
                if($acction != 'edit')
                {
                    $tasks->prepend('--', '');
                }
            }
            $returnHTML = view('proposal.section',compact('tasks','type' ,'acction','proposal'))->render();
                $response = [
                    'is_success' => true,
                    'message' => '',
                    'html' => $returnHTML,
                ];
            return response()->json($response);
        }
        elseif($request->type == "parts" && module_is_active('CMMS'))
        {
            $product_services = \Modules\ProductService\Entities\ProductService::where('workspace_id', getActiveWorkSpace())->where('type','parts')->get()->pluck('name', 'id');
            $product_services_count =$product_services->count();
            if($acction != 'edit')
            {
                $product_services->prepend('--', '');
            }

            if (module_is_active('CMMS')) {
                $product_type['parts'] = 'Parts';
            }
            $returnHTML = view('proposal.section',compact('product_services','product_type','type' ,'acction','proposal','product_services_count'))->render();
                $response = [
                    'is_success' => true,
                    'message' => '',
                    'html' => $returnHTML,
                ];
            return response()->json($response);
        }
        else
        {
            return [];
        }
    }
    public function TaxDetailGet(Request $request)
    {
        $taxs_data = [];
        if(module_is_active('ProductService'))
        {
            $taxs_data = \Modules\ProductService\Entities\Tax::whereIn('id',$request->Taxid)->where('workspace_id', getActiveWorkSpace())->get();
        }
        return $taxs_data;
    }
    public function getTax(Request $request){

        if(module_is_active('ProductService'))
        {
            $taxs_data = \Modules\ProductService\Entities\Tax::whereIn('id',$request->tax_id)->where('workspace_id', getActiveWorkSpace())->get();
            return json_encode($taxs_data);
        }else{
            $taxs_data = [];
            return json_encode($taxs_data);
        }

    }
    public function productDestroy(Request $request)
    {
        if(Auth::user()->isAbleTo('proposal product delete'))
        {
            ProposalProduct::where('id', '=', $request->id)->delete();

            return response()->json(['success' => __('Proposal product successfully deleted')]);
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')]);
        }
    }
    public function proposalAttechment(Request $request,$id)
    {
        $proposal = Proposal::find($id);
        $file_name = time() . "_" . $request->file->getClientOriginalName();

        $upload = upload_file($request,'file',$file_name,'proposal_attachment',[]);

        $fileSizeInBytes = \File::size($upload['url']);
        $fileSizeInKB = round($fileSizeInBytes / 1024, 2);

        if ($fileSizeInKB < 1024) {
            $fileSizeFormatted = $fileSizeInKB . " KB";
        } else {
            $fileSizeInMB = round($fileSizeInKB / 1024, 2);
            $fileSizeFormatted = $fileSizeInMB . " MB";
        }

        if($upload['flag'] == 1){
            $file                 = ProposalAttechment::create(
                [
                    'proposal_id' => $proposal->id,
                    'file_name'  => $file_name,
                    'file_path' => $upload['url'],
                    'file_size' => $fileSizeFormatted,
                ]
            );
            $return               = [];
            $return['is_success'] = true;


            return response()->json($return);
        }else{

            return response()->json(
                [
                    'is_success' => false,
                    'error' => $upload['msg'],
                ], 401
            );
        }
    }

    public function proposalAttechmentDestroy($id)
    {
        $file = ProposalAttechment::find($id);

        if (!empty($file->file_path)) {
            delete_file($file->file_path);
        }
        $file->delete();
        return redirect()->back()->with('success', __('File successfully deleted.'));
    }

    public function ProposalQuickStats()
    {
        $total_proposals = Proposal::where('workspace',getActiveWorkSpace())->count();
        $statues = Proposal::$statues;
        return view('proposal.statsreport',compact('statues','total_proposals'));
    }

}
