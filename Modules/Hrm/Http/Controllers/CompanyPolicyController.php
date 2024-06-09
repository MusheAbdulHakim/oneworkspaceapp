<?php

namespace Modules\Hrm\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Branch;
use Modules\Hrm\Entities\CompanyPolicy;
use Modules\Hrm\Events\CreateCompanyPolicy;
use Modules\Hrm\Events\DestroyCompanyPolicy;
use Modules\Hrm\Events\UpdateCompanyPolicy;

class CompanyPolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('companypolicy manage')) {
            $companyPolicy = CompanyPolicy::where('workspace', getActiveWorkSpace())->where('created_by', '=', creatorId())->with('branches')->get();
            return view('hrm::companyPolicy.index', compact('companyPolicy'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('companypolicy create')) {
            $branches     = Branch::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            return view('hrm::companyPolicy.create', compact('branches'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('companypolicy create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'branch' => 'required',
                    'title' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            if (!empty($request->attachment)) {
                $filenameWithExt = $request->file('attachment')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('attachment')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                $uplaod = upload_file($request, 'attachment', $fileNameToStore, 'companyPolicy');
                if ($uplaod['flag'] == 1) {
                    $url = $uplaod['url'];
                } else {
                    return redirect()->back()->with('error', $uplaod['msg']);
                }
            }
            $policy              = new CompanyPolicy();
            $policy->branch      = $request->branch;
            $policy->title       = $request->title;
            $policy->description = !empty($request->description) ? $request->description : '';
            $policy->attachment  = !empty($request->attachment) ? $url : '';
            $policy->workspace  = getActiveWorkSpace();
            $policy->created_by  = creatorId();
            $policy->save();

            event(new CreateCompanyPolicy($request, $policy));


            return redirect()->route('company-policy.index')->with('success', __('Company policy successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return redirect()->back();
        return view('hrm::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(CompanyPolicy $companyPolicy)
    {
        if (Auth::user()->isAbleTo('companypolicy edit')) {
            $branches     = Branch::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');

            return view('hrm::companyPolicy.edit', compact('branches', 'companyPolicy'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, CompanyPolicy $companyPolicy)
    {
        if (Auth::user()->isAbleTo('companypolicy edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'branch' => 'required',
                    'title' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            if (isset($request->attachment)) {
                if (!empty($companyPolicy->attachment)) {
                    delete_file($companyPolicy->attachment);
                }
                $filenameWithExt = $request->file('attachment')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('attachment')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                $uplaod = upload_file($request, 'attachment', $fileNameToStore, 'companyPolicy');
                if ($uplaod['flag'] == 1) {
                    $url = $uplaod['url'];
                } else {
                    return redirect()->back()->with('error', $uplaod['msg']);
                }
            }

            $companyPolicy->branch      = $request->branch;
            $companyPolicy->title       = $request->title;
            $companyPolicy->description = $request->description;
            if (isset($request->attachment)) {
                $companyPolicy->attachment = $url;
            }
            $companyPolicy->save();
            event(new UpdateCompanyPolicy($request, $companyPolicy));
            return redirect()->route('company-policy.index')->with('success', __('Company policy successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(CompanyPolicy $companyPolicy)
    {
        if (Auth::user()->isAbleTo('companypolicy delete')) {
            if ($companyPolicy->created_by == creatorId() && $companyPolicy->workspace == getActiveWorkSpace()) {
                if (!empty($companyPolicy->attachment)) {
                    delete_file($companyPolicy->attachment);
                }
                event(new DestroyCompanyPolicy($companyPolicy));
                $companyPolicy->delete();
                return redirect()->route('company-policy.index')->with('success', __('Company policy successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
