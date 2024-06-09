<?php

namespace Modules\Hrm\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\IpRestrict;

class IpRestrictController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return redirect()->back()->with('error', __('Permission denied.'));
        return view('hrm::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('ip restrict create')) {

            return view('hrm::restrict_ip.create');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('ip restrict create')) {
            if (\Auth::user()->type == 'company') {

                $validator = \Validator::make(
                    $request->all(),
                    [
                        'ip' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $ip = new IpRestrict();
                $ip->ip = $request->ip;
                $ip->workspace = getActiveWorkSpace();
                $ip->created_by = creatorId();
                $ip->save();

                return redirect()->back()->with('success', __('IP successfully created.'));
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
    public function show($id)
    {
        return redirect()->back()->with('error', __('Permission denied.'));
        return view('hrm::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('ip restrict edit')) {

            $ip = IpRestrict::find($id);

            return view('hrm::restrict_ip.edit', compact('ip'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('ip restrict edit')) {

            if (\Auth::user()->type == 'company') {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'ip' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $ip = IpRestrict::find($id);
                $ip->ip = $request->ip;
                $ip->save();

                return redirect()->back()->with('success', __('IP successfully updated.'));
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
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('ip restrict delete')) {

            if (\Auth::user()->type == 'company') {
                $ip = IpRestrict::find($id);
                $ip->delete();

                return redirect()->back()->with('success', __('IP successfully deleted.'));
            } else {

                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
