<?php

namespace Modules\Hrm\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Award;
use Modules\Hrm\Entities\AwardType;
use Modules\Hrm\Events\CreateAwardType;
use Modules\Hrm\Events\DestroyAwardType;
use Modules\Hrm\Events\UpdateAwardType;

class AwardTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('awardtype manage')) {
            $awardtypes = AwardType::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();

            return view('hrm::awardtype.index', compact('awardtypes'));
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
        if (Auth::user()->isAbleTo('awardtype create')) {
            return view('hrm::awardtype.create');
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
        if (Auth::user()->isAbleTo('awardtype create')) {

            $validator = \Validator::make(
                $request->all(),
                [

                    'name' => 'required|max:30',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $awardtype             = new AwardType();
            $awardtype->name       = $request->name;
            $awardtype->workspace   = getActiveWorkSpace();
            $awardtype->created_by = creatorId();
            $awardtype->save();

            event(new CreateAwardType($request, $awardtype));

            return redirect()->route('awardtype.index')->with('success', __('Award Type  successfully created.'));
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
    public function edit(AwardType $awardtype)
    {
        if (Auth::user()->isAbleTo('awardtype edit')) {
            if ($awardtype->created_by == creatorId() && $awardtype->workspace == getActiveWorkSpace()) {
                return view('hrm::awardtype.edit', compact('awardtype'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
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
    public function update(Request $request, AwardType $awardtype)
    {
        if (Auth::user()->isAbleTo('awardtype edit')) {
            if ($awardtype->created_by == creatorId() && $awardtype->workspace == getActiveWorkSpace()) {
                $validator = \Validator::make(
                    $request->all(),
                    [

                        'name' => 'required|max:30',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $awardtype->name = $request->name;
                $awardtype->save();

                event(new UpdateAwardType($request, $awardtype));

                return redirect()->route('awardtype.index')->with('success', __('Award Type successfully updated.'));
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
    public function destroy(AwardType $awardtype)
    {
        if (Auth::user()->isAbleTo('awardtype delete')) {
            if ($awardtype->created_by == creatorId() && $awardtype->workspace == getActiveWorkSpace()) {
                $awards     = Award::where('award_type', $awardtype->id)->where('workspace', getActiveWorkSpace())->get();
                if (count($awards) == 0) {
                    event(new DestroyAwardType($awardtype));

                    $awardtype->delete();
                } else {
                    return redirect()->route('awardtype.index')->with('error', __('This awardtype has award. Please remove the award from this awardtype.'));
                }

                return redirect()->route('awardtype.index')->with('success', __('Award Type successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
