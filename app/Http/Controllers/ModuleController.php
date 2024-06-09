<?php

namespace App\Http\Controllers;

use App\Events\CancelSubscription;
use App\Models\AddOn;
use App\Models\Sidebar;
use App\Models\User;
use App\Models\userActiveModule;
use Nwidart\Modules\Facades\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Permission;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use ZipArchive;

class ModuleController extends Controller
{
    public function index(){
        if(Auth::user()->isAbleTo('module manage'))
        {
            try {
                $modules = Module::all();
                $module_path = Module::getPath();

                $category_wise_add_ons = json_decode(file_get_contents("https://dash-demo.workdo.io/cronjob/dash-addon.json"),true);
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', __('oops something wren wrong!'));
            }

            return view('module.index',compact('modules','module_path','category_wise_add_ons'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function add(){
        if(Auth::user()->isAbleTo('module add'))
        {
            return view('module.add');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function enable(Request $request)
    {
        $module = Module::find($request->name);
        if(!empty($module))
        {
            // Sidebar Performance Changes
            sideMenuCacheForget('all');

            \App::setLocale('en');

            if($module->isEnabled())
            {
                $check_child_module = $this->Check_Child_Module($module);
                if($check_child_module == true)
                {
                    $module->disable();
                    return redirect()->back()->with('success', __('Module Disable Successfully!'));
                }
                else
                {
                    return redirect()->back()->with('error', __($check_child_module['msg']));
                }

            }
            else
            {
                $check_parent_module = $this->Check_Parent_Module($module);
                if($check_parent_module['status'] == true)
                {
                    Artisan::call('module:migrate '. $request->name);
                    Artisan::call('module:seed '. $request->name);
                    // $this->installDependencies($request->name);
                    // Artisan::call('module:update '. $request->name);

                    $addon = AddOn::where('module',$request->name)->first();
                    if(empty($addon))
                    {
                        $addon = new AddOn;
                        $addon->module = $request->name;
                        $addon->name = Module_Alias_Name($request->name);
                        $addon->monthly_price = 0;
                        $addon->yearly_price = 0;

                        $addon->save();
                    }
                    $module->enable();
                    // Artisan::call('module:migrate-rollback '.$module);
                    return redirect()->back()->with('success', __('Module Enable Successfully!'));
                }
                else
                {
                    return redirect()->back()->with('error', __($check_parent_module['msg']));
                }

            }
        }else{
            return redirect()->back()->with('error', __('oops something wren wrong!'));
        }
    }
    private function installDependencies($moduleName)
    {
        // Define the dependencies for each module
        $dependencies = [
            'Paypal' => ['srmklive/paypal' => '^3.0'],
            // Add more modules and their dependencies as needed
        ];
        if (array_key_exists($moduleName, $dependencies)) {
            $requiredPackages = $dependencies[$moduleName];
            foreach ($requiredPackages as $package => $version) {
                // Use Composer to install the required packages
                try {
                    // exec('composer require '.$package.':'.$version);
                    // exec('/usr/local/bin/composer require ' . $package . ':' . $version);
                    // exec('composer require ' . $package . ':' . $version . ' 2>&1', $output, $return_var);
                    putenv('COMPOSER_HOME=' . base_path('vendor/bin/composer'));

                    $process = new Process(['composer', 'require', $package . ':' . $version]);
                    $process->setEnv(['COMPOSER_HOME' => base_path('vendor/bin/composer')]); // Set the environment variable
                    $process->run();

                    if (!$process->isSuccessful()) {
                        throw new ProcessFailedException($process);
                    }


                    // Composer::require($package . ':' . $version);
                } catch (\Throwable $th) {
                    // dd($th->getMessage());
                }
            }
        }
    }

    // public function enable(Request $request){
    //     $module = Module::find($request->name);
    //     if(!empty($module))
    //     {
    //         if($module->isEnabled()){
    //             $module->disable();
    //             Sidebar::where('module',$request->name)->update(['is_visible'=>0]);
    //             return redirect()->back()->with('success', __('Module Disable Successfully!'));
    //         }else{
    //             $module->enable();
    //             Artisan::call('module:migrate '.$request->name);
    //             Artisan::call('module:seed '.$request->name);
    //             Sidebar::where('module',$request->name)->update(['is_visible'=>1]);
    //             return redirect()->back()->with('success', __('Module Enable Successfully!'));
    //         }
    //     }else{

    //     }
    // }
    public function install(Request $request){
        $zip = new ZipArchive;
        try {
                $res = $zip->open($request->file);
          } catch (\Exception $e) {
                return error_res($e->getMessage());
          }
        if ($res === TRUE)
        {
            $zip->extractTo('Modules/');
            $zip->close();
            return success_res('Install successfully.');
        } else {
            return error_res('oops something wren wrong');
        }
        return error_res('oops something wren wrong');
    }

    public function remove($module)
    {
        if(Auth::user()->isAbleTo('module remove'))
        {
            $module = Module::find($module);
            if($module)
            {
                $module->disable();
                $module->delete();
                Permission::where('module',$module)->delete();
                Artisan::call('module:migrate-refresh '.$module);
                AddOn::where('module',$module)->delete();

                // Sidebar Performance Changes
                sideMenuCacheForget('all');
                return redirect()->back()->with('success', __('Module delete successfully!'));
            }
            else
            {
                return redirect()->back()->with('error', __('oops something wren wrong!'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function Check_Parent_Module($module)
    {
        $path =$module->getPath().'/module.json';
        $json = json_decode(file_get_contents($path), true);
        $data['status'] = true;
        $data['msg'] = '';

        if(isset($json['parent_module']) && !empty($json['parent_module']))
        {
            foreach ($json['parent_module'] as $key => $value) {
                $modules = implode(',',$json['parent_module']);
                $parent_module = module_is_active($value);
                if($parent_module == true)
                {
                    $module = Module::find($value);
                    if($module)
                    {
                         $this->Check_Parent_Module($module);
                    }
                }
                else
                {
                    $data['status'] = false;
                    $data['msg'] = 'please activate this module '.$modules;
                    return $data;
                }
            }
            return $data;
        }
        else
        {
            return $data;
        }
    }
    public function Check_Child_Module($module)
    {
        $path =$module->getPath().'/module.json';
        $json = json_decode(file_get_contents($path), true);
        $status = true;
        if(isset($json['child_module']) && !empty($json['child_module']))
        {
            foreach ($json['child_module'] as $key => $value)
            {
                $child_module = module_is_active($value);
                if($child_module == true)
                {
                    $module = Module::find($value);
                    $module->disable();
                    if($module)
                    {
                        $this->Check_Child_Module($module);
                    }
                }
            }
            return true;
        }
        else
        {
            return true;
        }
    }
    public function GuestModuleSelection(Request $request)
    {
        try
        {
            $post = $request->all();
            unset($post['_token']);
            Session::put('user-module-selection', $post);
            Session::put('Subscription', 'custom_subscription');

        }
        catch (\Throwable $th)
        {

        }
    }
    public function ModuleReset(Request $request)
    {
       $value = Session::get('user-module-selection');
       if(!empty($value))
       {
         Session::forget('user-module-selection');
       }
       return redirect()->route('plans.index');
    }
    public function CancelAddOn($name = null)
    {
        if(!empty($name))
        {
            $name         = \Illuminate\Support\Facades\Crypt::decrypt($name);
            $user_module = explode(',',Auth::user()->active_module);
            $user_module = array_values(array_diff($user_module, array($name)));
            $user = User::find(Auth::user()->id);
            $user->active_module = implode(',',$user_module);
            $user->save();

            event(new CancelSubscription(creatorId(),getActiveWorkSpace(),$name));

            userActiveModule::where('user_id', Auth::user()->id)->where('module', $name)->delete();

            // Settings Cache forget
            comapnySettingCacheForget();
            sideMenuCacheForget();
            return redirect()->back()->with('success', __('Successfully cancel subscription.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Something went wrong please try again .'));
        }
    }
}
