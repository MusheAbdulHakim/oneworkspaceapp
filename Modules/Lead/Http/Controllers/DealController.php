<?php

namespace Modules\Lead\Http\Controllers;

use App\Models\User;
use App\Models\EmailTemplate;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Lead\Entities\ClientDeal;
use Modules\Lead\Entities\ClientPermission;
use Modules\Lead\Entities\Deal;
use Modules\Lead\Entities\DealActivityLog;
use Modules\Lead\Entities\DealCall;
use Modules\Lead\Entities\DealDiscussion;
use Modules\Lead\Entities\DealEmail;
use Modules\Lead\Entities\DealFile;
use Modules\Lead\Entities\DealStage;
use Modules\Lead\Entities\DealTask;
use Modules\Lead\Entities\Label;
use Modules\Lead\Entities\Lead;
use Modules\Lead\Entities\Pipeline;
use Modules\Lead\Entities\Source;
use Modules\Lead\Entities\User as EntitiesUser;
use Modules\Lead\Entities\UserDeal;
use Modules\ProductService\Entities\ProductService;
use Illuminate\Support\Facades\Mail;
use Modules\Lead\Emails\SendDealEmail;
use Illuminate\Support\Facades\Auth;
use Modules\Lead\Events\CreateDeal;
use Modules\Lead\Events\CreateDealTask;
use Modules\Lead\Events\DealAddCall;
use Modules\Lead\Events\DealAddClient;
use Modules\Lead\Events\DealAddDiscussion;
use Modules\Lead\Events\DealAddEmail;
use Modules\Lead\Events\DealAddNote;
use Modules\Lead\Events\DealAddProduct;
use Modules\Lead\Events\DealMoved;
use Modules\Lead\Events\StatusChangeDealTask;
use Modules\Lead\Events\UpdateDealTask;
use Modules\Lead\Events\DestroyDeal;
use Modules\Lead\Events\DestroyDealTask;
use Modules\Lead\Events\DestroyUserDeal;
use Modules\Lead\Events\UpdateDeal;
use Modules\Lead\Events\DealAddUser;
use Modules\Lead\Events\DealCallUpdate;
use Modules\Lead\Events\DealSourceUpdate;
use Modules\Lead\Events\DealUploadFile;
use Modules\Lead\Events\DestroyDealCall;
use Modules\Lead\Events\DestroyDealClient;
use Modules\Lead\Events\DestroyDealfile;
use Modules\Lead\Events\DestroyDealProduct;
use Modules\Lead\Events\DestroyDealSource;

class DealController extends Controller
{
    public function index()
    {
        $creatorId          = creatorId();
        $getActiveWorkSpace = getActiveWorkSpace();
        $usr = Auth::user();
        $user = EntitiesUser::find($usr->id);
        if ($usr->isAbleTo('deal manage')) {
            if ($usr->default_pipeline) {
                $pipeline = Pipeline::where('created_by', '=', $creatorId)->where('workspace_id', $getActiveWorkSpace)->where('id', '=', $usr->default_pipeline)->first();
                if (!$pipeline) {
                    $pipeline = Pipeline::where('created_by', '=', $creatorId)->where('workspace_id', $getActiveWorkSpace)->first();
                }
            } else {
                $pipeline = Pipeline::where('created_by', '=', $creatorId)->where('workspace_id', $getActiveWorkSpace)->first();
            }

            $pipelines = Pipeline::where('created_by', '=', $creatorId)->where('workspace_id', $getActiveWorkSpace)->get()->pluck('name', 'id');
            if (Auth::user()->type == 'client') {
                $id_deals = $user->clientDeals->pluck('id');
            } else {
                $id_deals = $user->deals->pluck('id');
            }
            if (!empty($pipeline)) {
                $deals       = Deal::whereIn('id', $id_deals)->where('pipeline_id', '=', $pipeline->id)->get();
                $curr_month  = Deal::whereIn('id', $id_deals)->where('pipeline_id', '=', $pipeline->id)->whereMonth('created_at', '=', date('m'))->get();
                $curr_week   = Deal::whereIn('id', $id_deals)->where('pipeline_id', '=', $pipeline->id)->whereBetween(
                    'created_at',
                    [
                        \Carbon\Carbon::now()->startOfWeek(),
                        \Carbon\Carbon::now()->endOfWeek(),
                    ]
                )->get();
                $last_30days = Deal::whereIn('id', $id_deals)->where('pipeline_id', '=', $pipeline->id)->whereDate('created_at', '>', \Carbon\Carbon::now()->subDays(30))->get();
            } else {
                return redirect()->back()->with('error', __('Please Create Pipeline'));
            }
            // Deal Summary
            $cnt_deal                = [];
            $cnt_deal['total']       = Deal::getDealSummary($deals);
            $cnt_deal['this_month']  = Deal::getDealSummary($curr_month);
            $cnt_deal['this_week']   = Deal::getDealSummary($curr_week);
            $cnt_deal['last_30days'] = Deal::getDealSummary($last_30days);

            return view('lead::deals.index', compact('pipelines', 'pipeline', 'cnt_deal'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function deal_list()
    {
        $usr = Auth::user();
        $creatorId          = creatorId();
        $getActiveWorkSpace = getActiveWorkSpace();
        $user = EntitiesUser::find($usr->id);
        if ($usr->isAbleTo('deal manage')) {
            if ($usr->default_pipeline) {
                $pipeline = Pipeline::where('created_by', '=', $creatorId)->where('workspace_id', $getActiveWorkSpace)->where('id', '=', $usr->default_pipeline)->first();
                if (!$pipeline) {
                    $pipeline = Pipeline::where('created_by', '=', $creatorId)->where('workspace_id', $getActiveWorkSpace)->first();
                }
            } else {
                $pipeline = Pipeline::where('created_by', '=', $creatorId)->where('workspace_id', $getActiveWorkSpace)->first();
            }

            $pipelines = Pipeline::where('created_by', '=', $creatorId)->where('workspace_id', $getActiveWorkSpace)->get()->pluck('name', 'id');

            if ($usr->type == 'client') {
                $id_deals = $user->clientDeals->pluck('id');
            } else {
                $id_deals = $user->deals->pluck('id');
            }

            $deals       = Deal::whereIn('id', $id_deals)->where('pipeline_id', '=', $pipeline->id)->get();
            $curr_month  = Deal::whereIn('id', $id_deals)->where('pipeline_id', '=', $pipeline->id)->whereMonth('created_at', '=', date('m'))->get();
            $curr_week   = Deal::whereIn('id', $id_deals)->where('pipeline_id', '=', $pipeline->id)->whereBetween(
                'created_at',
                [
                    \Carbon\Carbon::now()->startOfWeek(),
                    \Carbon\Carbon::now()->endOfWeek(),
                ]
            )->get();
            $last_30days = Deal::whereIn('id', $id_deals)->where('pipeline_id', '=', $pipeline->id)->whereDate('created_at', '>', \Carbon\Carbon::now()->subDays(30))->get();

            // Deal Summary
            $cnt_deal                = [];
            $cnt_deal['total']       = Deal::getDealSummary($deals);
            $cnt_deal['this_month']  = Deal::getDealSummary($curr_month);
            $cnt_deal['this_week']   = Deal::getDealSummary($curr_week);
            $cnt_deal['last_30days'] = Deal::getDealSummary($last_30days);

            // Deals
            if ($usr->type == 'client') {
                $deals = Deal::select('deals.*')->join('client_deals', 'client_deals.deal_id', '=', 'deals.id')->where('client_deals.client_id', '=', $usr->id)->where('deals.pipeline_id', '=', $pipeline->id)->orderBy('deals.order')->with('stage')->get();
            } else {
                $deals = Deal::select('deals.*')->join('user_deals', 'user_deals.deal_id', '=', 'deals.id')->where('user_deals.user_id', '=', $usr->id)->where('deals.pipeline_id', '=', $pipeline->id)->orderBy('deals.order')->with('stage')->get();
            }

            return view('lead::deals.list', compact('pipelines', 'pipeline', 'deals', 'cnt_deal'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for creating a new redeal.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('deal create')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            if (module_is_active('CustomField')) {
                $customFields =  \Modules\CustomField\Entities\CustomField::where('workspace_id', $getActiveWorkSpace)->where('module', '=', 'lead')->where('sub_module', 'deal')->get();
            } else {
                $customFields = null;
            }
            $clients      = User::where('created_by', '=', $creatorId)->where('type', '=', 'client')->get()->pluck('name', 'id');
            return view('lead::deals.create', compact('clients', 'customFields'));
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Store a newly created redeal in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $usr = Auth::user();
        if ($usr->isAbleTo('deal create')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $countDeal = Deal::where('created_by', '=', $creatorId)->count();
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                    'price' => 'min:0',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            // Default Field Value
            if ($usr->default_pipeline) {
                $pipeline = Pipeline::where('created_by', '=', $creatorId)->where('workspace_id', $getActiveWorkSpace)->where('id', '=', $usr->default_pipeline)->first();
                if (!$pipeline) {
                    $pipeline = Pipeline::where('created_by', '=', $creatorId)->where('workspace_id', $getActiveWorkSpace)->first();
                }
            } else {
                $pipeline = Pipeline::where('created_by', '=', $creatorId)->where('workspace_id', $getActiveWorkSpace)->first();
            }

            $stage = DealStage::where('pipeline_id', '=', $pipeline->id)->first();
            // End Default Field Value

            // Check if stage are available or not in pipeline.
            if (empty($stage)) {
                return redirect()->back()->with('error', __('Please Create Stage for This Pipeline.'));
            } else {
                $deal       = new Deal();
                $deal->name = $request->name;
                if (empty($request->price)) {
                    $deal->price = 0;
                } else {
                    $deal->price = $request->price;
                }
                $deal->pipeline_id = $pipeline->id;
                $deal->stage_id    = $stage->id;
                $deal->status      = 'Active';
                $deal->phone       = $request->phone;
                $deal->created_by  = $creatorId;
                $deal->workspace_id  = $getActiveWorkSpace;
                $deal->save();

                $clients = User::whereIN('id', array_filter($request->clients))->get()->pluck('email', 'id')->toArray();

                foreach (array_keys($clients) as $client) {
                    ClientDeal::create(
                        [
                            'deal_id' => $deal->id,
                            'client_id' => $client,
                        ]
                    );
                }

                UserDeal::create(
                    [
                        'user_id' => $creatorId,
                        'deal_id' => $deal->id,
                    ]
                );

                if (module_is_active('CustomField')) {
                    \Modules\CustomField\Entities\CustomField::saveData($deal, $request->customField);
                }

                if (!empty(company_setting('Deal Assigned')) && company_setting('Deal Assigned')  == true) {
                    $dArr = [
                        'deal_name' => !empty($deal->name) ? $deal->name : '',
                        'deal_pipeline' => $pipeline->name,
                        'deal_stage' => $stage->name,
                        'deal_status' => $deal->status,
                        'deal_price' =>  currency_format_with_sym($deal->price),
                    ];
                    // Send Mail
                    $resp = EmailTemplate::sendEmailTemplate('Deal Assigned', $clients, $dArr);
                }

                event(new CreateDeal($request, $deal));

                $resp = null;
                $resp['is_success'] = true;
                return redirect()->back()->with('success', __('Deal successfully created!') . (($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Display the specified redeal.
     *
     * @param \App\Deal $deal
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Deal $deal)
    {
        if ($deal->is_active) {
            $transdate = date('Y-m-d', time());
            $calenderTasks = [];
            if (Auth::user()->isAbleTo('deal task show')) {
                foreach ($deal->tasks as $task) {
                    $calenderTasks[] = [
                        'title' => $task->name,
                        'start' => $task->date,
                        'url' => route(
                            'deals.tasks.show',
                            [
                                $deal->id,
                                $task->id,
                            ]
                        ),
                        'className' => ($task->status) ? 'event-success border-success' : 'event-warning border-warning',
                    ];
                }
            }
            $permission = [];
            if (Auth::user()->type == 'client') {
                if ($permission) {
                    $permission = explode(',', $permission->permissions);
                } else {
                    $permission = [];
                }
            }

            if(module_is_active('CustomField')){
                $deal->customField = \Modules\CustomField\Entities\CustomField::getData($deal, 'lead','deal');
                $customFields      = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'lead')->where('sub_module','deal')->get();
            }else{
                $customFields = null;
            }

            return view('lead::deals.show', compact('deal', 'transdate', 'calenderTasks', 'permission','customFields'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for editing the specified redeal.
     *
     * @param \App\Deal $deal
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Deal $deal)
    {
        if (Auth::user()->isAbleTo('deal edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            if ($deal->created_by == $creatorId) {
                $pipelines = Pipeline::where('created_by', '=', $creatorId)->where('workspace_id', $getActiveWorkSpace)->get()->pluck('name', 'id');
                $pipelines->prepend(__('Select Pipeline'), '');
                $sources = Source::where('created_by', '=', $creatorId)->where('workspace_id', $getActiveWorkSpace)->get()->pluck('name', 'id');
                $sources->prepend(__('Select Sources'), '');
                $products = ['Select Products'];
                if (module_is_active('ProductService')) {
                    $products = ProductService::where('created_by', '=', $creatorId)->where('workspace_id', $getActiveWorkSpace)->get()->pluck('name', 'id');
                    $products->prepend(__('Select Products'), '');
                }
                if (module_is_active('CustomField')) {
                    $deal->customField = \Modules\CustomField\Entities\CustomField::getData($deal, 'lead', 'deal');
                    $customFields             = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', $getActiveWorkSpace)->where('module', '=', 'lead')->where('sub_module', 'deal')->get();
                } else {
                    $customFields = null;
                }
                $deal->sources  = explode(',', $deal->sources);
                $deal->products = explode(',', $deal->products);

                return view('lead::deals.edit', compact('deal', 'pipelines', 'sources', 'products', 'customFields'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Update the specified redeal in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Deal $deal
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deal $deal)
    {
        if (Auth::user()->isAbleTo('deal edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required',
                        'pipeline_id' => 'required',
                        'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                        'price' => 'min:0',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $deal->name = $request->name;
                if (empty($request->price)) {
                    $deal->price = 0;
                } else {
                    $deal->price = $request->price;
                }
                $deal->pipeline_id = $request->pipeline_id;
                $deal->stage_id    = $request->stage_id;
                $deal->phone       = $request->phone;
                $deal->sources     = implode(",", array_filter($request->sources));
                $deal->products    = implode(",", array_filter($request->products));
                $deal->notes       = $request->notes;
                $deal->save();

                if (module_is_active('CustomField')) {
                    \Modules\CustomField\Entities\CustomField::saveData($deal, $request->customField);
                }
                event(new UpdateDeal($request, $deal));

                return redirect()->back()->with('success', __('Deal successfully updated!'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Remove the specified redeal from storage.
     *
     * @param \App\Deal $deal
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deal $deal)
    {
        if (Auth::user()->isAbleTo('deal delete')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {

                event(new DestroyDeal($deal));

                DealDiscussion::where('deal_id', '=', $deal->id)->delete();
                $dealfiles = DealFile::where('deal_id', '=', $deal->id)->get();
                foreach ($dealfiles as $dealfile) {

                    delete_file($dealfile->file_path);
                    $dealfile->delete();
                }
                ClientDeal::where('deal_id', '=', $deal->id)->delete();
                UserDeal::where('deal_id', '=', $deal->id)->delete();
                DealTask::where('deal_id', '=', $deal->id)->delete();
                DealActivityLog::where('deal_id', '=', $deal->id)->delete();
                ClientPermission::where('deal_id', '=', $deal->id)->delete();
                if (module_is_active('CustomField')) {
                    $customFields = \Modules\CustomField\Entities\CustomField::where('module', 'lead')->where('sub_module', 'deal')->get();
                    foreach ($customFields as $customField) {
                        $value = \Modules\CustomField\Entities\CustomFieldValue::where('record_id', '=', $deal->id)->where('field_id', $customField->id)->first();
                        if (!empty($value)) {
                            $value->delete();
                        }
                    }
                }
                $lead = Lead::where(['is_converted' => $deal->id])->update(['is_converted' => 0]);

                $deal->delete();

                return redirect()->route('deals.index')->with('success', __('Deal successfully deleted!'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function order(Request $request)
    {
        $usr = Auth::user();

        if ($usr->isAbleTo('deal move')) {
            $post       = $request->all();
            $deal       = Deal::find($post['deal_id']);
            $clients    = ClientDeal::select('client_id')->where('deal_id', '=', $deal->id)->get()->pluck('client_id')->toArray();
            $deal_users = $deal->users->pluck('id')->toArray();
            $usrs       = User::whereIN('id', array_merge($deal_users, $clients))->get()->pluck('email', 'id')->toArray();

            if ($deal->stage_id != $post['stage_id']) {
                $newStage = DealStage::find($post['stage_id']);
                DealActivityLog::create(
                    [
                        'user_id' => $usr->id,
                        'deal_id' => $deal->id,
                        'log_type' => 'Move',
                        'remark' => json_encode(
                            [
                                'title' => $deal->name,
                                'old_status' => $deal->stage->name,
                                'new_status' => $newStage->name,
                            ]
                        ),
                    ]
                );


                if (!empty(company_setting('Deal Moved')) && company_setting('Deal Moved')  == true) {
                    $dArr = [
                        'deal_name' => $deal->name,
                        'deal_pipeline' => $deal->pipeline->name,
                        'deal_stage' => $deal->stage->name,
                        'deal_status' => $deal->status,
                        'deal_price' => currency_format_with_sym($deal->price),
                        'deal_old_stage' => $deal->stage->name,
                        'deal_new_stage' => $newStage->name,
                    ];

                    // Send Email
                    $resp =  EmailTemplate::sendEmailTemplate('Deal Moved', $usrs, $dArr);
                }
                event(new DealMoved($request, $deal));
            }
            foreach ($post['order'] as $key => $item) {
                $deal           = Deal::find($item);
                $deal->order    = $key;
                $deal->stage_id = $post['stage_id'];
                $deal->save();
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function labels($id)
    {
        if (Auth::user()->isAbleTo('deal edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                $labels   = Label::where('pipeline_id', '=', $deal->pipeline_id)->get();
                $selected = $deal->labels();
                if ($selected) {
                    $selected = $selected->pluck('name', 'id')->toArray();
                } else {
                    $selected = [];
                }

                return view('lead::deals.labels', compact('deal', 'labels', 'selected'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function labelStore($id, Request $request)
    {
        if (Auth::user()->isAbleTo('deal edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                if ($request->labels) {
                    $deal->labels = implode(',', $request->labels);
                } else {
                    $deal->labels = $request->labels;
                }
                $deal->save();

                return redirect()->back()->with('success', __('Labels successfully updated!'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function userEdit($id)
    {
        if (Auth::user()->isAbleTo('deal edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                $users = User::where('active_workspace', '=', $getActiveWorkSpace)->where('created_by', '=', $creatorId)->where('type', '!=', 'Client')->whereNOTIn(
                    'id',
                    function ($q) use ($deal) {
                        $q->select('user_id')->from('user_deals')->where('deal_id', '=', $deal->id);
                    }
                )->get();
                foreach ($users as $key => $user) {
                    if (!$user->isAbleTo('deal manage')) {
                        $users->forget($key);
                    }
                }
                $users = $users->pluck('name', 'id');

                $users->prepend(__('Select Users'), '');

                return view('lead::deals.users', compact('deal', 'users'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function userUpdate($id, Request $request)
    {
        $usr = Auth::user();
        if ($usr->isAbleTo('deal edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            $resp = '';

            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                if (!empty($request->users)) {
                    $users = User::whereIN('id', array_filter($request->users))->get()->pluck('email', 'id')->toArray();

                    $dealArr = [
                        'deal_id' => $deal->id,
                        'name' => $deal->name,
                        'updated_by' => $usr->id,
                    ];
                    foreach (array_keys($users) as $user) {
                        UserDeal::create(
                            [
                                'deal_id' => $deal->id,
                                'user_id' => $user,
                            ]
                        );
                    }
                    if (!empty(company_setting('Deal Assigned')) && company_setting('Deal Assigned')  == true) {
                        $dArr = [
                            'deal_name' => $deal->name,
                            'deal_pipeline' => $deal->pipeline->name,
                            'deal_stage' => $deal->stage->name,
                            'deal_status' => $deal->status,
                            'deal_price' => currency_format_with_sym($deal->price),
                        ];
                        // Send Email
                        $resp = EmailTemplate::sendEmailTemplate('Deal Assigned', $users, $dArr);
                    }
                }

                event(new DealAddUser($request,$deal));

                if (!empty($users) && !empty($request->users)) {
                    return redirect()->back()->with('success', __('Users successfully updated!') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
                } else {
                    return redirect()->back()->with('error', __('Please Select Valid User!'));
                }
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function userDestroy($id, $user_id)
    {
        if (Auth::user()->isAbleTo('deal edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                UserDeal::where('deal_id', '=', $deal->id)->where('user_id', '=', $user_id)->delete();

                event(new DestroyUserDeal($deal));

                return redirect()->back()->with('success', __('User successfully deleted!'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function clientEdit($id)
    {
        if (Auth::user()->isAbleTo('deal edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                $clients = User::where('created_by', '=', $creatorId)->where('active_workspace', '=', $getActiveWorkSpace)->where('type', '=', 'Client')->whereNOTIn(
                    'id',
                    function ($q) use ($deal) {
                        $q->select('client_id')->from('client_deals')->where('deal_id', '=', $deal->id);
                    }
                )->get()->pluck('name', 'id');

                return view('lead::deals.clients', compact('deal', 'clients'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function clientUpdate($id, Request $request)
    {
        if (Auth::user()->isAbleTo('deal edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                if (!empty($request->clients)) {
                    $clients = array_filter($request->clients);
                    foreach ($clients as $client) {
                        ClientDeal::create(
                            [
                                'deal_id' => $deal->id,
                                'client_id' => $client,
                            ]
                        );
                    }
                }

                event(new DealAddClient($request,$deal));

                if (!empty($clients) && !empty($request->clients)) {
                    return redirect()->back()->with('success', __('Clients successfully updated!'))->with('status', 'clients');
                } else {
                    return redirect()->back()->with('error', __('Please Select Valid Clients!'))->with('status', 'clients');
                }
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'clients');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'clients');
        }
    }

    public function clientDestroy($id, $client_id)
    {
        if (Auth::user()->isAbleTo('deal edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                ClientDeal::where('deal_id', '=', $deal->id)->where('client_id', '=', $client_id)->delete();
                ClientPermission::where('deal_id', '=', $deal->id)->where('client_id', '=', $client_id)->delete();

                event(new DestroyDealClient($deal));

                return redirect()->back()->with('success', __('Client successfully deleted!'))->with('status', 'clients');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'clients');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'clients');
        }
    }

    public function productEdit($id)
    {
        if (Auth::user()->isAbleTo('deal edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                $products = \Modules\ProductService\Entities\ProductService::where('workspace_id', '=', $getActiveWorkSpace)->where('created_by', '=', $creatorId)->whereNOTIn('id', explode(',', $deal->products))->get()->pluck('name', 'id');

                return view('lead::deals.products', compact('deal', 'products'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function productUpdate($id, Request $request)
    {
        $usr = Auth::user();
        if ($usr->isAbleTo('deal edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal       = Deal::find($id);
            $clients    = ClientDeal::select('client_id')->where('deal_id', '=', $id)->get()->pluck('client_id')->toArray();
            $deal_users = $deal->users->pluck('id')->toArray();

            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                if (!empty($request->products)) {
                    $products       = array_filter($request->products);
                    $old_products   = explode(',', $deal->products);
                    $deal->products = implode(',', array_merge($old_products, $products));
                    $deal->save();

                    $objProduct = ProductService::whereIN('id', $products)->get()->pluck('name', 'id')->toArray();
                    DealActivityLog::create(
                        [
                            'user_id' => $usr->id,
                            'deal_id' => $deal->id,
                            'log_type' => 'Add Product',
                            'remark' => json_encode(['title' => implode(",", $objProduct)]),
                        ]
                    );

                    $productArr = [
                        'deal_id' => $deal->id,
                        'name' => $deal->name,
                        'updated_by' => $usr->id,
                    ];
                }

                event(new DealAddProduct($request,$deal));

                if (!empty($products) && !empty($request->products)) {
                    return redirect()->back()->with('success', __('Products successfully updated!'))->with('status', 'products');
                } else {
                    return redirect()->back()->with('error', __('Please Select Valid Product!'))->with('status', 'general');
                }
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'products');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'products');
        }
    }

    public function productDestroy($id, $product_id)
    {
        if (Auth::user()->isAbleTo('deal edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                $products = explode(',', $deal->products);
                foreach ($products as $key => $product) {
                    if ($product_id == $product) {
                        unset($products[$key]);
                    }
                }
                $deal->products = implode(',', $products);
                $deal->save();

                event(new DestroyDealProduct($deal));

                return redirect()->back()->with('success', __('Products successfully deleted!'))->with('status', 'products');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'products');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'products');
        }
    }

    public function fileUpload($id, Request $request)
    {
        if (Auth::user()->isAbleTo('deal edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {

                $file_name = $request->file->getClientOriginalName();
                $file_path = $request->deal_id . "_" . md5(time()) . "_" . $request->file->getClientOriginalName();

                $url = upload_file($request, 'file', $file_name, 'deals', []);
                if (isset($url['flag']) && $url['flag'] == 1) {

                    $file                 = DealFile::create(
                        [
                            'deal_id' => $request->deal_id,
                            'file_name' => $file_name,
                            'file_path' => $url['url'],
                        ]
                    );
                    $return               = [];
                    $return['is_success'] = true;
                    $return['download']   = get_file($url['url']);

                    $return['delete']     = route(
                        'deals.file.delete',
                        [
                            $deal->id,
                            $file->id,
                        ]
                    );

                    DealActivityLog::create(
                        [
                            'user_id' => Auth::user()->id,
                            'deal_id' => $deal->id,
                            'log_type' => 'Upload File',
                            'remark' => json_encode(['file_name' => $file_name]),
                        ]
                    );

                    event(new DealUploadFile($request,$deal));

                    return response()->json($return);
                } else {
                    return response()->json(
                        [
                            'is_success' => false,
                            'error' => $url['msg'],
                        ],
                        401
                    );
                }
            } else {
                return response()->json(
                    [
                        'is_success' => false,
                        'error' => __('Permission Denied.'),
                    ],
                    401
                );
            }
        } else {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('Permission Denied.'),
                ],
                401
            );
        }
    }

    public function fileDownload($id, $file_id)
    {
        if (Auth::user()->isAbleTo('deal edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                $file = DealFile::find($file_id);
                if ($file) {
                    $file_path = get_base_file($file->file_path);
                    $filename  = $file->file_name;

                    return \Response::download(
                        $file_path,
                        $filename,
                        [
                            'Content-Length: ' . get_size($file_path),
                        ]
                    );
                } else {
                    return redirect()->back()->with('error', __('File is not exist.'));
                }
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function fileDelete($id, $file_id)
    {
        if (Auth::user()->isAbleTo('deal edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                $file = DealFile::find($file_id);
                if ($file) {
                    delete_file($file->file_path);
                    $file->delete();

                    event(new DestroyDealfile($deal));

                    return response()->json(['is_success' => true], 200);
                } else {
                    return response()->json(
                        [
                            'is_success' => false,
                            'error' => __('File is not exist.'),
                        ],
                        200
                    );
                }
            } else {
                return response()->json(
                    [
                        'is_success' => false,
                        'error' => __('Permission Denied.'),
                    ],
                    401
                );
            }
        } else {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('Permission Denied.'),
                ],
                401
            );
        }
    }

    public function noteStore($id, Request $request)
    {
        if (Auth::user()->isAbleTo('deal edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                $deal->notes = $request->notes;
                $deal->save();

                event(new DealAddNote($request,$deal));

                return response()->json(
                    [
                        'is_success' => true,
                        'success' => __('Note successfully saved!'),
                    ],
                    200
                );
            } else {
                return response()->json(
                    [
                        'is_success' => false,
                        'error' => __('Permission Denied.'),
                    ],
                    401
                );
            }
        } else {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('Permission Denied.'),
                ],
                401
            );
        }
    }

    public function taskCreate($id)
    {
        if (Auth::user()->isAbleTo('deal task create')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace  = $getActiveWorkSpace) {
                $priorities = DealTask::$priorities;
                $status     = DealTask::$status;
                return view('lead::deals.tasks', compact('deal', 'priorities', 'status'));
            } else {
                return response()->json(
                    [
                        'is_success' => false,
                        'error' => __('Permission Denied.'),
                    ],
                    401
                );
            }
        } else {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('Permission Denied.'),
                ],
                401
            );
        }
    }

    public function taskStore($id, Request $request)
    {

        $usr = Auth::user();
        if ($usr->isAbleTo('deal task create')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal       = Deal::find($id);
            $clients    = ClientDeal::select('client_id')->where('deal_id', '=', $id)->get()->pluck('client_id')->toArray();
            $deal_users = $deal->users->pluck('id')->toArray();
            $usrs       = User::whereIN('id', array_merge($deal_users, $clients))->get()->pluck('email', 'id')->toArray();

            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required',
                        'date' => 'required',
                        'time' => 'required',
                        'priority' => 'required',
                        'status' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $dealTask = DealTask::create(
                    [
                        'deal_id' => $deal->id,
                        'name' => $request->name,
                        'date' => $request->date,
                        'time' => date('H:i:s', strtotime($request->date . ' ' . $request->time)),
                        'priority' => $request->priority,
                        'status' => $request->status,
                        'workspace' => $getActiveWorkSpace,
                    ]
                );

                DealActivityLog::create(
                    [
                        'user_id' => $usr->id,
                        'deal_id' => $deal->id,
                        'log_type' => 'Create Task',
                        'remark' => json_encode(['title' => $dealTask->name]),
                    ]
                );

                $taskArr = [
                    'deal_id' => $deal->id,
                    'name' => $deal->name,
                    'updated_by' => $usr->id,
                ];
                if (!empty(company_setting('New Task')) && company_setting('New Task')  == true) {
                    $tArr = [
                        'deal_name' => $deal->name,
                        'deal_pipeline' => $deal->pipeline->name,
                        'deal_stage' => $deal->stage->name,
                        'deal_status' => $deal->status,
                        'deal_price' => currency_format_with_sym($deal->price),
                        'task_name' => $dealTask->name,
                        'task_priority' => DealTask::$priorities[$dealTask->priority],
                        'task_status' => DealTask::$status[$dealTask->status],
                    ];

                    // Send Email
                    $resp = EmailTemplate::sendEmailTemplate('New Task', $usrs, $tArr);
                }

                event(new CreateDealTask($request, $dealTask, $deal));
                return redirect()->back()->with('success', __('Task successfully created!') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'tasks');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'tasks');
        }
    }

    public function taskShow($id, $task_id)
    {
        if (Auth::user()->isAbleTo('deal task show')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                $task = DealTask::find($task_id);

                return view('lead::deals.tasksShow', compact('task', 'deal'));
            } else {
                return response()->json(
                    [
                        'is_success' => false,
                        'error' => __('Permission Denied.'),
                    ],
                    401
                );
            }
        } else {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('Permission Denied.'),
                ],
                401
            );
        }
    }

    public function taskEdit($id, $task_id)
    {
        if (Auth::user()->isAbleTo('deal task edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                $priorities = DealTask::$priorities;
                $status     = DealTask::$status;
                $task       = DealTask::find($task_id);

                return view('lead::deals.tasks', compact('task', 'deal', 'priorities', 'status'));
            } else {
                return response()->json(
                    [
                        'is_success' => false,
                        'error' => __('Permission Denied.'),
                    ],
                    401
                );
            }
        } else {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('Permission Denied.'),
                ],
                401
            );
        }
    }

    public function taskUpdate($id, $task_id, Request $request)
    {
        if (Auth::user()->isAbleTo('deal task edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {

                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required',
                        'date' => 'required',
                        'time' => 'required',
                        'priority' => 'required',
                        'status' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $task = DealTask::find($task_id);

                $task->update(
                    [
                        'name' => $request->name,
                        'date' => $request->date,
                        'time' => date('H:i:s', strtotime($request->date . ' ' . $request->time)),
                        'priority' => $request->priority,
                        'status' => $request->status,
                    ]
                );

                event(new UpdateDealTask($request, $deal, $task));

                return redirect()->back()->with('success', __('Task successfully updated!'))->with('status', 'tasks');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'tasks');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'tasks');
        }
    }

    public function taskUpdateStatus($id, $task_id, Request $request)
    {
        if (Auth::user()->isAbleTo('deal task edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {

                $validator = \Validator::make(
                    $request->all(),
                    [
                        'status' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return response()->json(
                        [
                            'is_success' => false,
                            'error' => $messages->first(),
                        ],
                        401
                    );
                }

                $task = DealTask::find($task_id);
                if ($request->status) {
                    $task->status = 0;
                } else {
                    $task->status = 1;
                }
                $task->save();

                event(new StatusChangeDealTask($request, $deal, $task));

                return response()->json(
                    [
                        'is_success' => true,
                        'success' => __('Task successfully updated!'),
                        'status' => $task->status,
                        'status_label' => __(DealTask::$status[$task->status]),
                    ],
                    200
                );
            } else {
                return response()->json(
                    [
                        'is_success' => false,
                        'error' => __('Permission Denied.'),
                    ],
                    401
                );
            }
        } else {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('Permission Denied.'),
                ],
                401
            );
        }
    }

    public function taskDestroy($id, $task_id)
    {
        if (Auth::user()->isAbleTo('deal task delete')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                $task = DealTask::find($task_id);
                $task->delete();

                event(new DestroyDealTask($deal));

                return redirect()->back()->with('success', __('Task successfully deleted!'))->with('status', 'tasks');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'tasks');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'tasks');
        }
    }

    public function sourceEdit($id)
    {
        if (Auth::user()->isAbleTo('deal edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                $sources  = Source::where('created_by', '=', $creatorId)->where('workspace_id', '=', $getActiveWorkSpace)->get();
                $selected = $deal->sources();

                if ($selected) {
                    $selected = $selected->pluck('name', 'id')->toArray();
                }

                return view('lead::deals.sources', compact('deal', 'sources', 'selected'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function sourceUpdate($id, Request $request)
    {
        $usr = Auth::user();

        if ($usr->isAbleTo('deal edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal       = Deal::find($id);
            $clients    = ClientDeal::select('client_id')->where('deal_id', '=', $id)->get()->pluck('client_id')->toArray();
            $deal_users = $deal->users->pluck('id')->toArray();

            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                if (!empty($request->sources) && count($request->sources) > 0) {
                    $deal->sources = implode(',', $request->sources);
                } else {
                    $deal->sources = "";
                }

                $deal->save();
                DealActivityLog::create(
                    [
                        'user_id' => $usr->id,
                        'deal_id' => $deal->id,
                        'log_type' => 'Update Sources',
                        'remark' => json_encode(['title' => 'Update Sources']),
                    ]
                );

                $dealArr = [
                    'deal_id' => $deal->id,
                    'name' => $deal->name,
                    'updated_by' => $usr->id,
                ];

                event(new DealSourceUpdate($request,$deal));

                return redirect()->back()->with('success', __('Sources successfully updated!'))->with('status', 'sources');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'sources');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'sources');
        }
    }

    public function sourceDestroy($id, $source_id)
    {
        if (Auth::user()->isAbleTo('deal edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                $sources = explode(',', $deal->sources);
                foreach ($sources as $key => $source) {
                    if ($source_id == $source) {
                        unset($sources[$key]);
                    }
                }
                $deal->sources = implode(',', $sources);
                $deal->save();

                event(new DestroyDealSource($deal));

                return redirect()->back()->with('success', __('Sources successfully deleted!'))->with('status', 'sources');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'sources');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'sources');
        }
    }

    public function permission($id, $clientId)
    {
        if (Auth::user()->isAbleTo('deal edit')) {
            $deal     = Deal::find($id);
            $client   = EntitiesUser::find($clientId);
            $selected = $client->clientPermission($deal->id);
            if ($selected) {
                $selected = explode(',', $selected->permissions);
            } else {
                $selected = [];
            }
            $permissions = Deal::$permissions;
            return view('lead::deals.permissions', compact('deal', 'client', 'selected', 'permissions'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'clients');
        }
    }

    public function permissionStore($id, $clientId, Request $request)
    {
        if (Auth::user()->isAbleTo('deal edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                $client   = EntitiesUser::find($clientId);
                $permissions = $client->clientPermission($deal->id);
                if ($permissions) {
                    if (!empty($request->permissions) && count($request->permissions) > 0) {
                        $permissions->permissions = implode(',', $request->permissions);
                    } else {
                        $permissions->permissions = "";
                    }
                    $permissions->save();

                    return redirect()->back()->with('success', __('Permissions successfully updated!'))->with('status', 'clients');
                } elseif (!empty($request->permissions) && count($request->permissions) > 0) {
                    ClientPermission::create(
                        [
                            'client_id' => $clientId,
                            'deal_id' => $deal->id,
                            'permissions' => implode(',', $request->permissions),
                        ]
                    );

                    return redirect()->back()->with('success', __('Permissions successfully updated!'))->with('status', 'clients');
                } else {
                    return redirect()->back()->with('error', __('Invalid Permission.'))->with('status', 'clients');
                }
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'clients');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'clients');
        }
    }

    public function jsonUser(Request $request)
    {
        $users = [];
        if (!empty($request->deal_id)) {
            $deal  = Deal::find($request->deal_id);
            $users = $deal->users->pluck('name', 'id');
        }

        return response()->json($users, 200);
    }

    public function changePipeline(Request $request)
    {

        $user = Auth::user();
        $user->default_pipeline = $request->default_pipeline_id;
        $user->save();

        return redirect()->back();
    }

    public function discussionCreate($id)
    {
        $creatorId          = creatorId();
        $getActiveWorkSpace = getActiveWorkSpace();
        $deal = Deal::find($id);
        if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
            return view('lead::deals.discussions', compact('deal'));
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function discussionStore($id, Request $request)
    {
        $creatorId          = creatorId();
        $getActiveWorkSpace = getActiveWorkSpace();
        $usr        = Auth::user();
        $deal       = Deal::find($id);
        $clients    = ClientDeal::select('client_id')->where('deal_id', '=', $id)->get()->pluck('client_id')->toArray();
        $deal_users = $deal->users->pluck('id')->toArray();

        if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
            $discussion             = new DealDiscussion();
            $discussion->comment    = $request->comment;
            $discussion->deal_id    = $deal->id;
            $discussion->created_by = creatorId();
            $discussion->save();

            $dealArr = [
                'deal_id' => $deal->id,
                'name' => $deal->name,
                'updated_by' => $usr->id,
            ];

            event(new DealAddDiscussion($request,$deal));

            return redirect()->back()->with('success', __('Message successfully added!'))->with('status', 'discussion');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'discussion');
        }
    }

    public function changeStatus(Request $request, $id)
    {
        $deal         = Deal::where('id', '=', $id)->first();
        $deal->status = $request->deal_status;
        $deal->save();

        return redirect()->back();
    }

    // Deal Calls
    public function callCreate($id)
    {
        if (Auth::user()->isAbleTo('deal call create')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                $users = UserDeal::where('deal_id', '=', $deal->id)->get();

                return view('lead::deals.calls', compact('deal', 'users'));
            } else {
                return response()->json(
                    [
                        'is_success' => false,
                        'error' => __('Permission Denied.'),
                    ],
                    401
                );
            }
        } else {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('Permission Denied.'),
                ],
                401
            );
        }
    }

    public function callStore($id, Request $request)
    {
        $usr = Auth::user();

        if ($usr->isAbleTo('deal call create')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'subject' => 'required',
                        'call_type' => 'required',
                        'user_id' => 'required',
                        'duration' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                DealCall::create(
                    [
                        'deal_id' => $deal->id,
                        'subject' => $request->subject,
                        'call_type' => $request->call_type,
                        'duration' => $request->duration,
                        'user_id' => $request->user_id,
                        'description' => $request->description,
                        'call_result' => $request->call_result,
                    ]
                );

                DealActivityLog::create(
                    [
                        'user_id' => $usr->id,
                        'deal_id' => $deal->id,
                        'log_type' => 'Create Deal Call',
                        'remark' => json_encode(['title' => 'Create new Deal Call']),
                    ]
                );

                $dealArr = [
                    'deal_id' => $deal->id,
                    'name' => $deal->name,
                    'updated_by' => $usr->id,
                ];

                event(new DealAddCall($request,$deal));

                return redirect()->back()->with('success', __('Call successfully created!'))->with('status', 'calls');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'calls');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'calls');
        }
    }

    public function callEdit($id, $call_id)
    {
        if (Auth::user()->isAbleTo('deal call edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                $call  = DealCall::find($call_id);
                $users = UserDeal::where('deal_id', '=', $deal->id)->get();

                return view('lead::deals.calls', compact('call', 'deal', 'users'));
            } else {
                return response()->json(
                    [
                        'is_success' => false,
                        'error' => __('Permission Denied.'),
                    ],
                    401
                );
            }
        } else {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('Permission Denied.'),
                ],
                401
            );
        }
    }

    public function callUpdate($id, $call_id, Request $request)
    {
        if (Auth::user()->isAbleTo('deal call edit')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'subject' => 'required',
                        'call_type' => 'required',
                        'user_id' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $call = DealCall::find($call_id);

                $call->update(
                    [
                        'subject' => $request->subject,
                        'call_type' => $request->call_type,
                        'duration' => $request->duration,
                        'user_id' => $request->user_id,
                        'description' => $request->description,
                        'call_result' => $request->call_result,
                    ]
                );

                event(new DealCallUpdate($request,$deal));

                return redirect()->back()->with('success', __('Call successfully updated!'))->with('status', 'calls');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'calls');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'tasks');
        }
    }

    public function callDestroy($id, $call_id)
    {
        if (Auth::user()->isAbleTo('deal call delete')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                $task = DealCall::find($call_id);
                $task->delete();

                event(new DestroyDealCall($deal));

                return redirect()->back()->with('success', __('Call successfully deleted!'))->with('status', 'calls');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'calls');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'calls');
        }
    }

    // Deal email
    public function emailCreate($id)
    {
        if (Auth::user()->isAbleTo('deal email create')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                return view('lead::deals.emails', compact('deal'));
            } else {
                return response()->json(
                    [
                        'is_success' => false,
                        'error' => __('Permission Denied.'),
                    ],
                    401
                );
            }
        } else {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('Permission Denied.'),
                ],
                401
            );
        }
    }

    public function emailStore($id, Request $request)
    {
        if (Auth::user()->isAbleTo('deal email create')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            $deal = Deal::find($id);
            if ($deal->created_by == $creatorId && $deal->workspace_id == $getActiveWorkSpace) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'to' => 'required|email',
                        'subject' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $dealEmail = DealEmail::create(
                    [
                        'deal_id' => $deal->id,
                        'to' => $request->to,
                        'subject' => $request->subject,
                        'description' => $request->description,
                    ]
                );

                if (!empty(company_setting('Lead Emails')) && company_setting('Lead Emails')  == true) {
                    try {
                        $setconfing =  SetConfigEmail();
                        if ($setconfing ==  true) {
                            try {
                                Mail::to($request->to)->send(new SendDealEmail($dealEmail));
                            } catch (\Exception $e) {
                                $smtp_error['status'] = false;
                                $smtp_error['msg'] = $e->getMessage();
                            }
                        } else {
                            $smtp_error['status'] = false;
                            $smtp_error['msg'] = __('Something went wrong please try again ');
                        }
                    } catch (\Exception $e) {
                        $smtp_error['status'] = false;
                        $smtp_error['msg'] = $e->getMessage();
                    }
                }

                DealActivityLog::create(
                    [
                        'user_id' => Auth::user()->id,
                        'deal_id' => $deal->id,
                        'log_type' => 'Create Deal Email',
                        'remark' => json_encode(['title' => 'Create new Deal Email']),
                    ]
                );

                event(new DealAddEmail($request,$deal));

                return redirect()->back()->with('success', __('Email successfully created!'))->with('status', 'emails');
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'emails');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'emails');
        }
    }

    public function fileImportExport()
    {
        if (Auth::user()->isAbleTo('deal import')) {
            return view('lead::deals.import');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function fileImport(Request $request)
    {
        if (Auth::user()->isAbleTo('deal import')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
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
                                    <option value="price">Price</option>
                                    <option value="phone">Phone No</option>
                                    </select>
                                </th>
                                ';
                    }

                    $html .= '
                                <th>
                                        <select name="set_column_data" class="form-control set_column_data client-name" data-column_number="' . $count + 1 . '">
                                            <option value="client">Client</option>
                                        </select>
                                </th>
                                ';

                    $html .= '</tr>';
                    $limit = 0;
                    while (($row = fgetcsv($file_data)) !== false) {
                        $limit++;

                        $html .= '<tr>';

                        for ($count = 0; $count < count($row); $count++) {
                            $html .= '<td>' . $row[$count] . '</td>';
                        }

                        $html .= '<td>
                                    <select name="client" class="form-control client-name-value">;';
                        $clients = User::where('type', 'client')->where('created_by', $creatorId)->where('workspace_id', $getActiveWorkSpace)->pluck('name', 'id');
                        foreach ($clients as $key => $client) {
                            $html .= ' <option value="' . $key . '">' . $client . '</option>';
                        }
                        $html .= '  </select>
                                </td>';

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
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    public function fileImportModal()
    {
        if (Auth::user()->isAbleTo('deal import')) {
            return view('lead::deals.import_modal');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function dealImportdata(Request $request)
    {
        if (Auth::user()->isAbleTo('deal import')) {
            $creatorId          = creatorId();
            $getActiveWorkSpace = getActiveWorkSpace();
            session_start();
            $html = '<h3 class="text-danger text-center">Below data is not inserted</h3></br>';
            $flag = 0;
            $html .= '<table class="table table-bordered"><tr>';
            $file_data = $_SESSION['file_data'];

            unset($_SESSION['file_data']);

            $user = Auth::user();

            if ($user->default_pipeline) {
                $pipeline = Pipeline::where('created_by', '=', $creatorId)->where('workspace_id', $getActiveWorkSpace)->where('id', '=', $user->default_pipeline)->first();
                if (!$pipeline) {
                    $pipeline = Pipeline::where('created_by', '=', $creatorId)->where('workspace_id', $getActiveWorkSpace)->first();
                }
            } else {
                $pipeline = Pipeline::where('created_by', '=', $creatorId)->where('workspace_id', $getActiveWorkSpace)->first();
            }
            if (!empty($pipeline)) {
                $stage = DealStage::where('pipeline_id', '=', $pipeline->id)->where('workspace_id', $getActiveWorkSpace)->first();
                // End Default Field Value
            } else {
                return redirect()->back()->with('error', __('Please Create Pipeline.'));
            }
            if (empty($stage)) {
                return redirect()->back()->with('error', __('Please Create Stage for This Pipeline.'));
            }
            foreach ($file_data as $key => $row) {
                $deals = Deal::where('created_by', $creatorId)->where('workspace_id', $getActiveWorkSpace)->Where('name', 'like', $row[$request->name])->get();

                if ($deals->isEmpty()) {
                    try {

                        $client = User::find($request->client[$key]);
                        if (empty($client)) {
                            $client = User::where('created_by', Auth::user()->id)->first();
                        }
                        $deal = Deal::create([
                            'name' => $row[$request->name],
                            'price' => $row[$request->price],
                            'phone' => $row[$request->phone],
                            'pipeline_id' => $pipeline->id,
                            'stage_id' => $stage->id,
                            'created_by' => $creatorId,
                            'workspace_id' => $getActiveWorkSpace,
                        ]);
                        ClientDeal::create([
                            'client_id' => $client->id,
                            'deal_id' => $deal->id,
                        ]);

                        UserDeal::create([
                            'user_id' => $creatorId,
                            'deal_id' => $deal->id,
                        ]);
                    } catch (\Exception $e) {
                        $flag = 1;
                        $html .= '<tr>';

                        $html .= '<td>' . $row[$request->name] . '</td>';
                        $html .= '<td>' . $row[$request->price] . '</td>';
                        $html .= '<td>' . $row[$request->phone] . '</td>';

                        $html .= '</tr>';
                    }
                } else {
                    $flag = 1;
                    $html .= '<tr>';

                    $html .= '<td>' . $row[$request->name] . '</td>';
                    $html .= '<td>' . $row[$request->price] . '</td>';
                    $html .= '<td>' . $row[$request->phone] . '</td>';

                    $html .= '</tr>';
                }
            }

            $html .= '
                            </table>
                            <br />
                            ';
            if ($flag == 1) {

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
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }
}
