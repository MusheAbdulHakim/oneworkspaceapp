<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PinnedApp;
use App\Models\PinnedAppCategory;

class PinAppsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $module = null)
    {
        $categories = PinnedAppCategory::where('user_id',auth()->user()->id)->get();
        return view('pin-apps.create',compact(
            'categories','module'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createCategory(Request $request, PinnedAppCategory $category)
    {
        $categories = PinnedAppCategory::where('user_id',auth()->user()->id)->get();
        return view('pin-apps.create',compact(
            'categories','category'
        ));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function deleteCategory(PinnedAppCategory $category)
    {
        $category->delete();
        return redirect()->route('home')->with('success', 'Pin Category successfully deleted');
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'module' => 'required',
            'category' => 'required'
        ]);
        $user_id = auth()->user()->id;
        $category = PinnedAppCategory::firstOrCreate([
            'name' => $request->category,
            'user_id' => $user_id,
        ]);
        $user_pin = PinnedApp::where('user_id', $user_id)
                                ->where('module', $request->module)
                                ->first();
        if(!empty($user_pin) && !empty($user_pin->id)){
            return back()->with('error', 'App is already pinned');
        }
        PinnedApp::create([
            'pinned_app_category_id' => $category->id,
            'module' => $request->module,
            'user_id' => auth()->user()->id,
        ]);
        return back()->with('success', "App has been successfully pinned");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        PinnedApp::findOrFail($id)->delete();
        return back()->with('success', 'Pin has been removed successfully');
    }
}
