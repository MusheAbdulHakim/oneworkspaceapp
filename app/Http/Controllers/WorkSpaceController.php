<?php

namespace App\Http\Controllers;

use App\Events\DefaultData;
use App\Events\DestroyWorkSpace;
use App\Models\CustomDomainRequest;
use App\Models\User;
use App\Models\WorkSpace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WorkSpaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->isAbleTo('workspace create'))
        {
            // custom domain code

            $serverIp   = $_SERVER['SERVER_ADDR'];

            $subdomain_name = str_replace(
                [
                    'http://',
                    'https://',
                ],
                '',
                env('APP_URL')
            );

            $serverIp   = gethostbyname($subdomain_name);
            if ($serverIp == $_SERVER['SERVER_ADDR']) {
                $serverIp;
            } else {
                $serverIp = request()->server('SERVER_ADDR');
            }
            return view('workspace.create',compact('subdomain_name','serverIp'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Auth::user()->isAbleTo('workspace create'))
        {
            if(Auth::user()->type != 'super admin'){
                $canUse=  PlanCheck('Workspace',Auth::user()->id);
                if($canUse == false)
                {
                    return redirect()->back()->with('error','You have maxed out the total number of Workspace allowed on your current plan');
                }
            }
            $validator = \Validator::make(
                $request->all(), [
                    'name' => 'required',
                ]
            );

            // custom domain code
            if ($request->domain_switch == 'on')
            {
                if ($request->enable_domain == 'enable_domain') {
                    $validator = \Validator::make(
                        $request->all(), [
                            'domains' => 'required',
                        ]
                    );
                }
                if ($request->enable_domain == 'enable_subdomain') {
                    $validator = \Validator::make(
                        $request->all(), [
                            'subdomain' => 'required',
                        ]
                    );
                }
             }

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            try {
                $workspace = new WorkSpace();

                // custom domain code
                if ($request->domain_switch == 'on')
                {
                    $workspace->enable_domain = 'on';


                    if ($request->enable_domain == 'enable_domain')
                    {

                        $input = $request->domains;
                        $input = trim($input, '/');
                        if (!preg_match('#^http(s)?://#', $input)) {
                            $input = 'http://' . $input;
                        }
                        $urlParts = parse_url($input);
                        $domain_name = preg_replace('/^www\./', '', $urlParts['host']);

                        $check = WorkSpace::where('domain',$domain_name)->first();
                        if($check)
                        {
                            return redirect()->back()->with('error', __('The domain has already been claimed. Please try a different one.'));
                        }

                        $workspace->domain_type =  'custom';
                        $custom_domain_request = new CustomDomainRequest();
                        $custom_domain_request->domain =  $domain_name;
                        $custom_domain_request->status =  0;
                        $custom_domain_request->created_by = \Auth::user()->id;

                    }
                    if ($request->enable_domain == 'enable_subdomain') {
                        $input = env('APP_URL');
                        $input = trim($input, '/');
                        if (!preg_match('#^http(s)?://#', $input)) {
                            $input = 'http://' . $input;
                        }
                        $urlParts = parse_url($input);
                        $subdomain_name = preg_replace('/^www\./', '', $urlParts['host']);
                        $subdomain_name = $request->subdomain . '.' . $subdomain_name;

                        $check = WorkSpace::where('subdomain',$subdomain_name)->first();
                        if($check)
                        {
                            return redirect()->back()->with('error', __('The domain has already been claimed. Please try a different one.'));
                        }

                        $workspace->domain_type =  'subdomain';
                        $workspace->subdomain =  $subdomain_name;
                    }
                }

                $workspace->name = $request->name;
                $workspace->created_by = \Auth::user()->id;
                $workspace->save();

                $msg = __('Workspace create successfully!');

               if($workspace->domain_type == 'custom')
               {
                $custom_domain_request->workspace = $workspace->id;
                $custom_domain_request->save();
                $msg  =  __('Workspace create successfully!'). '<br> <span class="text-danger">'. __("Your customdomain request will be approved by admin and then your domain is activated.") .'</span>';
               }

                $user = \Auth::user();
                $user->active_workspace =$workspace->id;
                $user->save();


                User::CompanySetting(\Auth::user()->id,$workspace->id);
                if(!empty(\Auth::user()->active_module))
                {
                    event(new DefaultData(\Auth::user()->id,$workspace->id,\Auth::user()->active_module));
                }


                // return redirect()->route('dashboard')->with('success',$msg);
                return redirect()->back()->with('success',$msg);



            }catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkSpace  $workSpace
     * @return \Illuminate\Http\Response
     */
    public function show(WorkSpace $workSpace)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkSpace  $workSpace
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Auth::user()->isAbleTo('workspace edit'))
        {
            $workSpace = WorkSpace::find($id);

            // custom domain code

            $serverIp   = $_SERVER['SERVER_ADDR'];

            $subdomain_name = str_replace(
                [
                    'http://',
                    'https://',
                ],
                '',
                env('APP_URL')
            );

            $serverIp   = gethostbyname($subdomain_name);
            if ($serverIp == $_SERVER['SERVER_ADDR']) {
                $serverIp;
            } else {
                $serverIp = request()->server('SERVER_ADDR');
            }
            $sub_domain = $workSpace->subdomain;
            $parts = explode('.', $sub_domain); // Split the string by '.' delimiter
            $subdomain = $parts[0];

            $custom_domain_request = CustomDomainRequest::where('workspace',$workSpace->id)->first();

            return view('workspace.edit',compact('workSpace','subdomain_name','serverIp','subdomain','custom_domain_request'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WorkSpace  $workSpace
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        if(Auth::user()->isAbleTo('workspace edit'))
        {

            $workSpace = WorkSpace::find($id);

            $validator = \Validator::make(
                $request->all(), [
                    'name' => 'required',
                ]
            );

            // custom domain code

            if ($request->domain_switch == 'on')
            {
                if ($request->enable_domain == 'enable_domain') {
                    $validator = \Validator::make(
                        $request->all(), [
                            'domains' => 'required',
                        ]
                    );
                }
                if ($request->enable_domain == 'enable_subdomain') {
                    $validator = \Validator::make(
                        $request->all(), [
                            'subdomain' => 'required',
                        ]
                    );
                }
            }

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            // custom domain code

            $workSpace->enable_domain = 'off';
            $workSpace->domain_type =  null;
            $workSpace->domain =  null;
            $workSpace->subdomain =  null;

            if ($request->domain_switch == 'on')
            {
                $workSpace->enable_domain = 'on';

                if ($request->enable_domain == 'enable_domain')
                {
                    $input = $request->domains;
                    $input = trim($input, '/');
                    if (!preg_match('#^http(s)?://#', $input)) {
                        $input = 'http://' . $input;
                    }
                    $urlParts = parse_url($input);
                    $domain_name = preg_replace('/^www\./', '', $urlParts['host']);

                    $check = WorkSpace::where('domain',$domain_name)->where('id','!=',$workSpace->id)->first();
                    if($check)
                    {
                        return redirect()->back()->with('error', __('The domain has already been claimed. Please try a different one.'));
                    }
                    $workSpace->domain_type =  'custom';
                    $workSpace->domain =  $domain_name;
                    $custom_domain_request = CustomDomainRequest::where('workspace', $id)->first();
                    if ($custom_domain_request) {
                        $custom_domain_request->domain =  $domain_name;
                        $custom_domain_request->status =  0;
                        $custom_domain_request->update();
                    }
                    else{
                        $custom_domain_request = new CustomDomainRequest();
                        $custom_domain_request->domain =  $domain_name;
                        $custom_domain_request->status =  0;
                        $custom_domain_request->workspace = $id;
                        $custom_domain_request->created_by = \Auth::user()->id;
                        $custom_domain_request->save();
                    }
                }
                if ($request->enable_domain == 'enable_subdomain') {

                    $input = env('APP_URL');
                    $input = trim($input, '/');
                    if (!preg_match('#^http(s)?://#', $input)) {
                        $input = 'http://' . $input;
                    }
                    $urlParts = parse_url($input);
                    $subdomain_name = preg_replace('/^www\./', '', $urlParts['host']);
                    $subdomain_name = $request->subdomain . '.' . $subdomain_name;

                    $check = WorkSpace::where('subdomain',$subdomain_name)->where('id','!=',$workSpace->id)->first();
                    if($check)
                    {
                        return redirect()->back()->with('error', __('The domain has already been claimed. Please try a different one.'));
                    }

                    $workSpace->domain_type =  'subdomain';
                    $workSpace->subdomain =  $subdomain_name;
                }
            }

            $workSpace->name = $request->name;
            $workSpace->slug = $request->slug;
            $workSpace->save();


            return redirect()->back()->with('success', __('Workspace updated successfully!'));

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkSpace  $workSpace
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkSpace $workSpace,$workspace_id)
    {
        if(Auth::user()->isAbleTo('workspace delete'))
        {
            $objUser   = \Auth::user();
            $workspace = Workspace::find($workspace_id);

            if($workspace && $workspace->created_by == $objUser->id)
            {
                $other_workspac = Workspace::where('created_by',$objUser->id)->where('is_disable',1)->where('id','!=',$workspace->id)->first();
                if($other_workspac)
                {
                    if(!empty($other_workspac))
                    {
                        $objUser->active_workspace = $other_workspac->id;
                        $objUser->save();
                    }
                     // first parameter workspace
                    event(new DestroyWorkSpace($workspace));

                    $workspace->delete();

                    // custom domain code

                    $local = parse_url(config('app.url'))['host'];
                    // Get the request host
                    $remote = request()->getHost();
                    if($local != $remote)
                    {
                        if($other_workspac->enable_domain == 'on')
                        {
                            sideMenuCacheForget('company');

                            if($other_workspac->domain_type == 'custom')
                            {
                                return redirect('http://'. $other_workspac->domain.'/dashboard')->with('success', 'User Workspace change successfully.');
                            }
                            else if($other_workspac->domain_type == 'sub')
                            {
                                return redirect($other_workspac->subdomain.'/dashboard')->with('success', 'User Workspace change successfully.');
                            }

                        }
                    }

                    return redirect()->route('dashboard')->with('success', __('Workspace Deleted Successfully!'));
                }
                return redirect()->route('dashboard')->with('errors', __("You can't delete Workspace! because your other workspaces are disabled "));
            }
            else
            {
                return redirect()->route('dashboard')->with('errors', __("You can't delete Workspace!"));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function change($workspace_id)
    {
        $check = WorkSpace::find($workspace_id);
        if(!empty($check))
        {
            $users = User::where('email',\Auth::user()->email)->where('workspace_id',$workspace_id)->where('created_by',Auth::user()->created_by)->first();
            if(empty($users))
            {
                $users = User::where('email',\Auth::user()->email)->Where('id',$check->created_by)->first();
            }
            if(empty($users))
            {
                $users = User::where('email',\Auth::user()->email)->where('workspace_id',$workspace_id)->first();
            }
            $user = User::find($users->id);
            $user->active_workspace = $workspace_id;
            $user->save();
            if(!empty($user)){
                Auth::login($user);

                if($check->enable_domain == 'on')
                {
                    if($check->domain_type == 'custom' && !empty($check->domain))
                    {
                        return redirect()->away('http://'.$check->domain.'/dashboard');
                    }
                    else if($check->domain_type == 'subdomain' && !empty($check->subdomain))
                    {
                        return redirect()->away('http://'.$check->subdomain.'/dashboard');
                    }
                }

                return redirect()->route('dashboard')->with('success', 'User Workspace change successfully.');
            }
            return redirect()->route('dashboard')->with('success', 'User Workspace change successfully.');
        }else{
           return redirect()->route('dashboard')->with('error', "Workspace not found.");
        }
    }

    public function workspaceCheck(Request $request)
    {
        if(isset($request->slug))
        {
             $workSpace = WorkSpace::where('slug',$request->slug)->where('id','!=',$request->workspace)->exists();
             if(!$workSpace)
             {
                return response()->json(['success' => __('This Slug is Available.')]);
             }
        }
        return response()->json(['error' => __('This Slug Not Available.')]);
    }
}
