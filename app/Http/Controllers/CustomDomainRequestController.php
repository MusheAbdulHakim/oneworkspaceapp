<?php

namespace App\Http\Controllers;

use App\Models\CustomDomainRequest;
use App\Models\WorkSpace;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class CustomDomainRequestController extends Controller
{


    public function index()
    {
        if (Auth::user()->isAbleTo('plan orders'))
        {
            $custom_domain_requests = CustomDomainRequest::orderBy('status', 'asc')->get();

            return view('custom_domain_request.index', compact('custom_domain_requests'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function acceptRequest($id, $response)
    {
        if (Auth::user()->isAbleTo('plan orders'))
        {
            $custom_domain_requests =CustomDomainRequest::find($id);
            if(!empty($custom_domain_requests))
            {
                if($response == 1)
                {
                    $custom_domain_requests->status = 1;
                    $custom_domain_requests->update();

                    $workspace = Workspace::find($custom_domain_requests->workspace);
                    if ($workspace) {
                        $workspace->domain = $custom_domain_requests->domain;
                        $workspace->save();
                    }
                }
                else
                {
                    $workspace = Workspace::find($custom_domain_requests->workspace);
                    if ($workspace) {
                        $workspace->domain_type = null;
                        $workspace->domain = null;
                        $workspace->save();
                    }
                    $custom_domain_requests->status = '2';
                    $custom_domain_requests->update();
                    return redirect()->back()->with('success', __('Request Rejected Successfully.'));
                }
                return redirect()->back()->with('success', __('Request Approved Successfully.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Something went wrong.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function destroy($id)
    {

        if (Auth::user()->isAbleTo('plan orders'))
        {
            $custom_domain_requests =CustomDomainRequest::find($id);
            $custom_domain_requests->delete();

            return redirect()->route('custom_domain_request.index')->with('success', __('Request deleted successfully'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

}
