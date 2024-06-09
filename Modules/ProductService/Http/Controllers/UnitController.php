<?php

namespace Modules\ProductService\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\ProductService\Entities\ProductService;
use Modules\ProductService\Entities\Unit;
use Modules\ProductService\Events\CreateUnit;
use Modules\ProductService\Events\DestroyUnit;
use Modules\ProductService\Events\UpdateUnit;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return redirect()->back()->with('error', __('Permission denied.'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if(Auth::user()->isAbleTo('unit cerate'))
        {
            return view('productservice::units.create');
        }
        else
        {
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
        if(Auth::user()->isAbleTo('unit cerate'))
        {
            $validator = \Validator::make(
                $request->all(), [
                    'name' => 'required|max:20',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $category             = new Unit();
            $category->name       = $request->name;
            $category->created_by = creatorId();
            $category->workspace_id = getActiveWorkSpace();
            $category->save();

            event(new CreateUnit($request,$category));

            return redirect()->route('category.index')->with('success', __('Unit successfully created.'));
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
    public function show($id)
    {
        return redirect()->back();
        return view('productservice::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if(Auth::user()->isAbleTo('unit edit'))
        {
            $unit = Unit::find($id);
            return view('productservice::units.edit',compact('unit'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
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
        if(Auth::user()->isAbleTo('unit edit'))
        {
            $unit = Unit::find($id);
            $validator = \Validator::make(
                $request->all(), [
                    'name' => 'required|max:20',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $unit->name = $request->name;
            $unit->save();

            event(new UpdateUnit($request,$unit));

            return redirect()->route('category.index')->with('success', __('Unit successfully updated.'));
        }
        else
        {
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
        if(Auth::user()->isAbleTo('unit delete'))
        {
            $unit = Unit::find($id);
            $units = ProductService::where('unit_id', $unit->id)->first();
            if(!empty($units))
            {
                return redirect()->back()->with('error', __('this unit is already assign so please move or remove this unit related data.'));
            }
            event(new DestroyUnit($unit));

            $unit->delete();
            return redirect()->route('category.index')->with('success', __('Unit successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
