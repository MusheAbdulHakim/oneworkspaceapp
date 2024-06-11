<?php

namespace Modules\PabblyConnect\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\PabblyConnect\Entities\Pabbly;
use Modules\PabblyConnect\Entities\PabblyModules;
use Modules\Zapier\Entities\Zapier;

class PabblyConnectController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('pabblyconnect::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        // return view('pabblyconnect::create');

        if (Auth::user()->isAbleTo('pabbly create')) {
            $methods = ['GET' => 'GET', 'POST' => 'POST', 'PUT' => 'PUT'];
            if(Auth::user()->type == 'super admin'){
                $modules = PabblyModules::select('module', 'submodule')->where('type','super admin')->get();
                $PabblyModule = [];
                foreach($modules as $module){
                    if(module_is_active($module->module) || $module->module == 'general'){

                        $sub_modules = PabblyModules::select('id','module','submodule')->where('module',$module->module)->where('type','super admin')->get();
                        $temp = [];
                        foreach($sub_modules as $sub_module){
                            $temp[$sub_module->id] = $sub_module->submodule;
                        }
                        $PabblyModule[Module_Alias_Name($module->module)] = $temp;
                    }
                }
            }
            else{
                $modules = PabblyModules::select('module', 'submodule')->where('type','company')->get();
                $PabblyModule = [];
                foreach($modules as $module){
                    if(module_is_active($module->module) || $module->module == 'general'){
                        $sub_modules = PabblyModules::select('id','module','submodule')->where('module',$module->module)->where('type','company')->get();
                        $temp = [];
                        foreach($sub_modules as $sub_module){
                            $temp[$sub_module->id] = $sub_module->submodule;
                        }
                        $PabblyModule[Module_Alias_Name($module->module)] = $temp;
                    }
                }
            }
            return view('pabblyconnect::pabbly.create',compact('PabblyModule', 'methods'));
        }
        else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('pabbly create')) {
            $validator = Validator::make(
                $request->all(), [
                    'module' => 'required',
                    'method' => 'required',
                    'url' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $zapier =  Pabbly::where('action',$request->module)->where('workspace',getActiveWorkSpace())->first();
            if(empty($zapier))
            {
                $zapier = new Pabbly();
                $zapier->method = $request->method;
                $zapier->action = $request->module;
                $zapier->url = $request->url;
                $zapier->workspace = getActiveWorkSpace();
                $zapier->created_by = creatorId();
                $zapier->save();

                return redirect()->back()->with('success', __('Pabbly Setting successfully created!'));
            }
            else
            {
                return redirect()->back()->with('error', __('The module has already been taken.'));
            }
        }
        else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('pabblyconnect::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        // return view('pabblyconnect::edit');

        if (Auth::user()->isAbleTo('pabbly edit')) {
            $methods = ['GET' => 'GET', 'POST' => 'POST', 'PUT' => 'PUT'];
            $pabbly = Pabbly::find($id);
            if(Auth::user()->type == 'super admin'){
                $modules = PabblyModules::select('module', 'submodule')->where('type','super admin')->get();
                $PabblyModule = [];
                foreach($modules as $module){
                    $sub_modules = PabblyModules::select('id','module','submodule')->where('module',$module->module)->where('type','super admin')->get();
                    $temp = [];
                    foreach($sub_modules as $sub_module){
                        $temp[$sub_module->id] = $sub_module->submodule;
                    }
                    $PabblyModule[Module_Alias_Name($module->module)] = $temp;
                }
            }
            else{
                $modules = PabblyModules::select('module', 'submodule')->where('type','company')->get();
                $PabblyModule = [];
                foreach($modules as $module){
                    if(module_is_active($module->module) || $module->module == 'general'){
                        $sub_modules = PabblyModules::select('id','module','submodule')->where('module',$module->module)->where('type','company')->get();
                        $temp = [];
                        foreach($sub_modules as $sub_module){
                            $temp[$sub_module->id] = $sub_module->submodule;
                        }
                        $PabblyModule[Module_Alias_Name($module->module)] = $temp;
                    }
                }
            }
            return view('pabblyconnect::pabbly.edit',compact('PabblyModule','pabbly','methods'));
        }
        else{
            return response()->json(['error' => __('Permission Denied.')], 401);
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
        if (Auth::user()->isAbleTo('pabbly edit')) {
            
            $validator = Validator::make(
                $request->all(), [
                    'module' => 'required',
                    'method' => 'required',
                    'url' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $zapier = Pabbly::find($id);
            $zapier->method = $request->method;
            $zapier->action = $request->module;
            $zapier->url = $request->url;
            $zapier->workspace = getActiveWorkSpace();
            $zapier->created_by = creatorId();
            $zapier->update();
            return redirect()->back()->with('success', __('Pabbly Setting successfully Updated!'));
        }
        else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('pabbly delete')) {
            $zap = Pabbly::find($id);
            $zap->delete();
            return redirect()->back()->with('success', __('Pabbly Setting successfully deleted!'));
        }
        else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
