<?php

use App\Models\AddOn;
use App\Models\Coupon;
use App\Models\Currency;
use App\Models\EmailTemplate;
use App\Models\EmailTemplateLang;
use App\Models\Language;
use App\Models\Order;
use App\Models\Permission;
use App\Models\Plan;
use App\Models\User;
use App\Models\WorkSpace;
use App\Models\userActiveModule;
use Illuminate\Support\Collection;
use App\Models\Setting;
use App\Models\UserCoupon;
use Illuminate\Support\Facades\Auth;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

if(!function_exists('getPageTitle')){

    function getPageTitle($url) {
        $title = false;
        if ($handle = fopen($url, "r"))  {
          $string = stream_get_line($handle, 0, "</title>");
          fclose($handle);
          $string = (explode("<title", $string))[1];
          if (!empty($string)) {
            $title = trim((explode(">", $string))[1]);
          }
        }
        return $title;
    }
}


if (!function_exists('getMenu')) {
    function getMenu()
    {
        $user = auth()->user();
        return Cache::rememberForever(
            'sidebar_menu_' . $user->id,
            function () use ($user) {
                $role = $user->roles->first();
                $menu = new \App\Classes\Menu($user);
                if ($role->name == 'super admin') {
                    event(new \App\Events\SuperAdminMenuEvent($menu));
                } else {
                    event(new \App\Events\CompanyMenuEvent($menu));
                }
                $collection = collect($menu->menu);
                $grouped = $collection->groupBy('category')->toArray();

                $categoryIcon = categoryIcon();
                uksort($grouped, function ($a, $b) use ($categoryIcon) {
                    $indexA = array_search($a, array_keys($categoryIcon));
                    $indexB = array_search($b, array_keys($categoryIcon));
                    return $indexA <=> $indexB;
                });
                return generateMenu($grouped, null);
            }
        );
    }
}


// if (!function_exists('generateMenu')) {
// function generateMenu($menuItems, $parent = null)
// {
//     $html = '';

//     $html .= '<ul class="dash-navbar">';
//     $filteredItems = array_filter($menuItems, function ($item) use ($parent) {
//         return $item['parent'] == $parent;
//     });
//     if($parent == 'subscription')
//     {
//         // dd($filteredItems);
//     }
//     usort($filteredItems, function ($a, $b) {
//         return $a['order'] - $b['order'];
//     });

//     foreach ($filteredItems as $item) {
//         $html .= '<li class="dash-item dash-hasmenu">';
//         $html .= '<a href="' . (!empty($item['route']) ? route($item['route']) : '#!') . '" class="dash-link">
//         <span class="dash-micon"><i class="ti ti-' . $item['icon'] . '"></i></span>
//         <span class="dash-mtext">' . $item['title'] . '</span>';

//         $html .= '<span class="dash-arrow"> <i data-feather="chevron-right"></i> </span> </a> <ul class="dash-submenu">';
// 		$html .= generateMenu($menuItems, $item['name']);

//         $html .= '</ul> </li>';
//     }
//     $html .= '</ul>';

//     return $html;
// }
// }

if (!function_exists('generateMenu')) {
    function generateMenu($grouped, $parent = null)
    {
         $html = '';

        foreach ($grouped as $category => $menuItems)
        {
            $company_settings = getAdminAllSetting();
            if(!empty($company_settings['category_wise_sidemenu']) && $company_settings['category_wise_sidemenu'] == 'on'){
                $icon = isset(categoryIcon()[$category]) ? categoryIcon()[$category] : 'home';
                $html .= '<li class="dash-item dash-caption">
                        <label>'.$category.'</label>
                        <span class="material-icons text-black">storefront</span>
                      </li>';
            }

            $html .= generateSubMenu($menuItems,$parent);

        }

        return $html;
    }
}

if (!function_exists('generateSubMenu')) {
    function generateSubMenu($menuItems, $parent = null)
    {
        $html = '';

        $filteredItems = array_filter($menuItems, function ($item) use ($parent) {
            return $item['parent'] == $parent;
        });

        usort($filteredItems, function ($a, $b) {
            return $a['order'] - $b['order'];
        });

        foreach ($filteredItems as $item) {
            $hasChildren = hasChildren($menuItems, $item['name']);
            if ($item['parent'] == null) {
                $html .= '<li class="dash-item dash-hasmenu">';
            } else {
                $html .= '<li class="dash-item">';
            }
            $html .= '<a href="' . (!empty($item['route']) ? route($item['route']) : '#!') . '" class="dash-link">';

            if ($item['parent'] == null) {
                $html .= ' <span class="dash-micon">
                <span class="material-icons text-black">storefront</span>
                </span>
                <span class="dash-mtext">';
            }
            $html .= __($item['title']) . '</span>';

            if ($hasChildren) {
                $html .= '<span class="dash-arrow"> <i data-feather="chevron-right"></i> </span> </a>';
                $html .= '<ul class="dash-submenu">';
                $html .= generateSubMenu($menuItems, $item['name']);
                $html .= '</ul>';
            } else {
                $html .= '</a>';
            }

            $html .= '</li>';
        }
        return $html;
    }
}

if (!function_exists('categoryIcon')) {
    function categoryIcon()
    {
        $categoryIcon = [
        'Start-here' => 'school',
        'Dashboards' => 'home',
        'Dashboard' => 'home',
        'ERP & Operations' => 'shopping-cart',
        'Automation' => 'brand-gitlab',
        'General' => 'indent-increase',
        'Erp-operation' => 'apps',
        'Addon Manager' => 'apps',
        'Finance' => 'chart-dots',
        'HR' => 'users',
        'Sales' => 'businessplan',
        'Marketing' => 'businessplan',
        'eCommerce' => 'shopping-cart',
        'Marketplace' => 'shopping-cart',
        'Education' => 'school',
        'Operations' => 'stack-2',
        'Productivity' => 'list-check',
        'App-integration' => 'list-check',
        'Knowledgebase' => 'messages',
        'Communication' => 'messages',
        'Medical' => 'ambulance',
        'Vehicle' => 'bike',
        'AI' => 'brand-gitlab',
        'Settings' => 'adjustments-horizontal',
       ];

       return $categoryIcon;
    }
}

if (!function_exists('hasChildren')) {
    function hasChildren($menuItems, $name)
    {
        foreach ($menuItems as $item) {
            if ($item['parent'] === $name) {
                return true;
            }
        }
        return false;
    }
}


if (!function_exists('getSettingMenu')) {
    function getSettingMenu()
    {
        $user = auth()->user();
        $role = $user->roles->first();
        $menu = new \App\Classes\Menu($user);
        if ($role->name == 'super admin') {
            event(new \App\Events\SuperAdminSettingMenuEvent($menu));
        } else {
            event(new \App\Events\CompanySettingMenuEvent($menu));
        }
        return generateSettingMenu($menu->menu);
    }
}


if (!function_exists('generateSettingMenu')) {
    function generateSettingMenu($menuItems)
    {
        usort($menuItems, function ($a, $b) {
            return $a['order'] - $b['order'];
        });

        $html = '';
        foreach ($menuItems as $menu) {
            $method = isset($menu['method']) ? $menu['method'] : null;
            $html .= '<a href="#' . (!empty($menu['navigation']) ? $menu['navigation']: '') . '" data-module="' . $menu['module'] . '" data-method="' . $method . '"  class="list-group-item list-group-item-action setting-menu-nav">' . $menu['title'] . '<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>';
        }
        return $html;
    }
}
if (!function_exists('getSettings')) {
    function getSettings()
    {
        $user = auth()->user();
        $role = $user->roles->first();
        if ($role->name == 'super admin') {
            $settings = getAdminAllSetting();
            $html = new \App\Classes\Setting($user, $settings);
            event(new \App\Events\SuperAdminSettingEvent($html));
        } else {
            $settings = getCompanyAllSetting();
            $html = new \App\Classes\Setting($user, $settings);
            event(new \App\Events\CompanySettingEvent($html));
        }
        return generateSettings($html->html);
    }
}
if (!function_exists('generateSettings')) {
    function generateSettings($settingItems)
    {
        usort($settingItems, function ($a, $b) {
            return $a['order'] - $b['order'];
        });

        $html = '';
        foreach ($settingItems as $setting) {
            $html .= $setting['html'];
        }
        return $html;
    }
}

if (!function_exists('getAdminAllSetting')) {
    function getAdminAllSetting()
    {
        // Laravel cache
        return Cache::rememberForever('admin_settings', function () {
            $super_admin = User::where('type', 'super admin')->first();
            $settings = [];
            if ($super_admin) {
                $settings = Setting::where('created_by', $super_admin->id)->where('workspace', $super_admin->active_workspace)->pluck('value', 'key')->toArray();
            }

            return $settings;
        });
    }
}

if (!function_exists('getCompanyAllSetting')) {
    function getCompanyAllSetting($user_id = null, $workspace = null)
    {
        if (!empty($user_id)) {
            $user = User::find($user_id);
        } else {
            $user =  auth()->user();
        }

        $workspace = $workspace ?? $user->active_workspace;

        // // Check if the user is not 'company' or 'super admin' and find the creator
        if (!in_array($user->type, ['company', 'super admin'])) {
            $user = User::find($user->created_by);
        }

        if (!empty($user)) {
            $key = 'company_settings_' . $workspace . '_' . $user->id;
            return Cache::rememberForever($key, function () use ($user, $workspace) {
                $settings = [];
                $settings = Setting::where('created_by', $user->id)->where('workspace', $workspace)->pluck('value', 'key')->toArray();
                return $settings;
            });
        }

        return [];
    }
}

if (!function_exists('admin_setting')) {
    function admin_setting($key)
    {
        if ($key) {
            $admin_settings = getAdminAllSetting();
            $setting = (array_key_exists($key, $admin_settings)) ? $admin_settings[$key] : null;
            return $setting;
        }
    }
}

if (!function_exists('company_setting')) {
    function company_setting($key, $user_id = null, $workspace = null)
    {
        if ($key) {
            $company_settings = getCompanyAllSetting($user_id, $workspace);
            $setting = null;
            if (!empty($company_settings)) {
                $setting = (array_key_exists($key, $company_settings)) ? $company_settings[$key] : null;
            }
            return $setting;
        }
    }
}

if (!function_exists('AdminSettingCacheForget')) {
    function AdminSettingCacheForget()
    {
        try {
            Cache::forget('admin_settings');
        } catch (\Exception $e) {
            \Log::error('AdminSettingCacheForget :' . $e->getMessage());
        }
    }
}

if (!function_exists('comapnySettingCacheForget')) {
    function comapnySettingCacheForget($user_id = null, $workspace = null)
    {
        try {
            if (empty($user_id)) {
                $user_id = creatorId();
            }
            if (empty($workspace)) {
                $workspace = getActiveWorkSpace();
            }
            $key = 'company_settings_' . $workspace . '_' . $user_id;
            Cache::forget($key);
        } catch (\Exception $e) {
            \Log::error('comapnySettingCacheForget :' . $e->getMessage());
        }
    }
}

if (!function_exists('sideMenuCacheForget')) {
    function sideMenuCacheForget($type = null, $user_id = null)
    {
        if ($type == 'all') {
            Cache::flush();
        }

        if (!empty($user_id)) {
            $user = User::find($user_id);
        } else {
            $user =  auth()->user();
        }

        if ($user->type == 'company') {
            $users = User::select('id')->where('created_by', $user->id)->pluck('id');
            foreach ($users as $id) {
                try {
                    $key = 'sidebar_menu_' . $id;
                    Cache::forget($key);
                } catch (\Exception $e) {
                    \Log::error('comapnySettingCacheForget :' . $e->getMessage());
                }
            }
            try {
                $key = 'sidebar_menu_' . $user->id;
                Cache::forget($key);
            } catch (\Exception $e) {
                \Log::error('comapnySettingCacheForget :' . $e->getMessage());
            }
            return true;
        }

        try {
            $key = 'sidebar_menu_' . $user->id;
            Cache::forget($key);
        } catch (\Exception $e) {
            \Log::error('comapnySettingCacheForget :' . $e->getMessage());
        }

        return true;
    }
}

if (!function_exists('getActiveWorkSpace')) {
    function getActiveWorkSpace($user_id = null)
    {
        if (!empty($user_id)) {
            $user = User::find($user_id);
        } else {
            $user =  auth()->user();
        }

        if ($user) {
            if (!empty($user->active_workspace)) {
                return $user->active_workspace;
            } else {
                if ($user->type == 'super admin') {
                    return 0;
                } else {
                    static $WorkSpace = null;
                    if ($WorkSpace == null) {
                        $workspace = WorkSpace::where('created_by', $user->id)->first();
                    }
                    return $workspace->id;
                }
            }
        }
    }
}

if (!function_exists('getWorkspace')) {
    function getWorkspace()
    {
        $data = [];
        if (Auth::check()) {
            static $users = null;
            if ($users == null) {
                $users = User::where('email', Auth::user()->email)->get();
            }
            static $WorkSpace = null;
            if ($WorkSpace == null) {
                $WorkSpace =  WorkSpace::whereIn('id', $users->pluck('workspace_id')->toArray())->orWhereIn('created_by', $users->pluck('id')->toArray())->where('is_disable', 1)->get();
            }
            return $WorkSpace;
        } else {
            return $data;
        }
    }
}


if (!function_exists('creatorId')) {
    function creatorId()
    {
        if (Auth::user()->type == 'super admin' || Auth::user()->type == 'company') {
            return Auth::user()->id;
        } else {
            return Auth::user()->created_by;
        }
    }
}


if (!function_exists('getModuleList')) {
    function getModuleList()
    {
        $all = Module::getOrdered();
        $list = [];
        foreach ($all as $module) {
            array_push($list, $module->getName());
        }
        return $list;
    }
}

if (!function_exists('getshowModuleList')) {
    function getshowModuleList()
    {
        $all = Module::getOrdered();
        $list = [];
        foreach ($all as $module) {
            $path = $module->getPath() . '/module.json';
            $json = json_decode(file_get_contents($path), true);
            if (!isset($json['display']) || $json['display'] == true) {
                array_push($list, $module->getName());
            }
        }
        return $list;
    }
}

if (!function_exists('module_is_active')) {
    function module_is_active($module, $user_id = null)
    {
        if (Module::has($module)) {
            $module = Module::find($module);
            if ($module->isEnabled()) {
                if (Auth::check()) {
                    $user = Auth::user();
                } elseif ($user_id != null) {
                    $user = User::find($user_id);
                }
                if (!empty($user)) {
                    if ($user->type == 'super admin') {
                        return true;
                    } else {
                        $active_module = ActivatedModule($user->id);
                        if ((count($active_module) > 0 && in_array($module->getName(), $active_module))) {
                            return true;
                        }
                        return false;
                    }
                }
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
if (!function_exists('ActivatedModule')) {
    function ActivatedModule($user_id = null)
    {
        $activated_module = user::$superadmin_activated_module;

        if ($user_id != null) {
            $user = User::find($user_id);
        } elseif (Auth::check()) {
            $user = Auth::user();
        }
        if (!empty($user)) {
            $available_modules = array_keys(Module::getByStatus(1));

            if ($user->type == 'super admin') {
                $user_active_module = $available_modules;
            } else {
                if ($user->type != 'company') {
                    $user_not_com = User::find($user->created_by);
                    if (!empty($user)) {
                        // Sidebar Performance Changes
                        static $active_module = null;
                        if ($active_module == null) {
                            $active_module = userActiveModule::where('user_id', $user_not_com->id)->pluck('module')->toArray();
                        }
                    }
                } else {
                    static $active_module = null;
                    if ($active_module == null) {
                        $active_module = userActiveModule::where('user_id', $user->id)->pluck('module')->toArray();
                    }
                }

                // Find the common modules
                $commonModules = array_intersect($active_module, $available_modules);
                $user_active_module = array_unique(array_merge($commonModules, $activated_module));
            }
        }
        return $user_active_module;
    }
}
// module alias name
if (!function_exists('Module_Alias_Name')) {
    function Module_Alias_Name($module_name)
    {
        static $addons = [];
        static $resultArray = [];
        if (count($addons) == 0 && count($resultArray) == 0) {
            $addons = AddOn::all()->toArray();
            $resultArray = array_reduce($addons, function ($carry, $item) {
                // Check if both "module" and "name" keys exist in the current item
                if (isset($item['module']) && isset($item['name'])) {
                    // Add a new key-value pair to the result array
                    $carry[$item['module']] = $item['name'];
                }
                return $carry;
            }, []);
        }

        $module = Module::find($module_name);
        if (isset($resultArray)) {
            $module_name =  array_key_exists($module_name, $resultArray) ? $resultArray[$module_name] : (!empty($module) ? $module->get('alias') : $module_name);
        } elseif (!empty($module)) {
            $module_name = $module->get('alias');
        }
        return $module_name;
    }
}

if (!function_exists('get_permission_by_module')) {
    function get_permission_by_module($mudule)
    {
        $user = Auth::user();

        if ($user->type == 'super admin') {
            $permissions = Permission::where('module', $mudule)->orderBy('name')->get();
        } else {
            $permissions = new Collection();
            foreach ($user->roles as $role) {
                $permissions = $permissions->merge($role->permissions);
            }
            $permissions = $permissions->where('module', $mudule);
        }
        // $permissions = Spatie\Permission\Models\Permission::where('module',$mudule)->orderBy('name')->get();
        return $permissions;
    }
}

if (!function_exists('getActiveLanguage')) {
    function getActiveLanguage()
    {
        if ((Auth::check()) && (!empty(Auth::user()->lang))) {
            return Auth::user()->lang;
        } else {
            $admin_settings = getAdminAllSetting();
            return !empty($admin_settings['defult_language']) ? $admin_settings['defult_language'] : 'en';
        }
    }
}

if (!function_exists('languages')) {
    function languages()
    {

        try {
            $arrLang = Language::where('status', 1)->get()->pluck('name', 'code')->toArray();
        } catch (\Throwable $th) {
            $arrLang = [
                "ar" => "Arabic",
                "da" => "Danish",
                "de" => "German",
                "en" => "English",
                "es" => "Spanish",
                "fr" => "French",
                "it" => "Italian",
                "ja" => "Japanese",
                "nl" => "Dutch",
                "pl" => "Polish",
                "pt" => "Portuguese",
                "ru" => "Russian",
                "tr" => "Turkish"
            ];
        }
        return $arrLang;
    }
}


// setConfigEmail ( SMTP )
if (!function_exists('SetConfigEmail')) {
    function SetConfigEmail($user_id = null, $workspace_id = null)
    {
        try {

            if (!empty($user_id)) {
                $company_settings = getCompanyAllSetting($user_id);
            } elseif (!empty($user_id) && !empty($workspace_id)) {
                $company_settings = getCompanyAllSetting($user_id, $workspace_id);
            } else if (Auth::check()) {
                $company_settings = getCompanyAllSetting();
            } else {
                $user_id = User::where('type', 'super admin')->first()->id;
                $company_settings = getCompanyAllSetting($user_id);
            }

            config(
                [
                    'mail.driver' => $company_settings['mail_driver'],
                    'mail.host' => $company_settings['mail_host'],
                    'mail.port' => $company_settings['mail_port'],
                    'mail.encryption' => $company_settings['mail_encryption'],
                    'mail.username' => $company_settings['mail_username'],
                    'mail.password' => $company_settings['mail_password'],
                    'mail.from.address' => $company_settings['mail_from_address'],
                    'mail.from.name' => $company_settings['mail_from_name'],
                ]
            );
            return true;
        } catch (\Exception $e) {

            return false;
        }
    }
}

// file upload

if (!function_exists('upload_file')) {
    function upload_file($request, $key_name, $name, $path, $custom_validation = [])
    {
        try {
            $storage_settings = getAdminAllSetting();

            if (isset($storage_settings['storage_setting'])) {
                if ($storage_settings['storage_setting'] == 'wasabi') {
                    config(
                        [
                            'filesystems.disks.wasabi.key' => $storage_settings['wasabi_key'],
                            'filesystems.disks.wasabi.secret' => $storage_settings['wasabi_secret'],
                            'filesystems.disks.wasabi.region' => $storage_settings['wasabi_region'],
                            'filesystems.disks.wasabi.bucket' => $storage_settings['wasabi_bucket'],
                            'filesystems.disks.wasabi.root' => $storage_settings['wasabi_root'],
                            'filesystems.disks.wasabi.endpoint' => $storage_settings['wasabi_url']
                        ]
                    );
                    $max_size = !empty($storage_settings['wasabi_max_upload_size']) ? $storage_settings['wasabi_max_upload_size'] : '2048';
                    $mimes =  !empty($storage_settings['wasabi_storage_validation']) ? $storage_settings['wasabi_storage_validation'] : 'jpeg,jpg,png,svg,zip,txt,gif,docx';
                } else if ($storage_settings['storage_setting'] == 's3') {
                    config(
                        [
                            'filesystems.disks.s3.key' => $storage_settings['s3_key'],
                            'filesystems.disks.s3.secret' => $storage_settings['s3_secret'],
                            'filesystems.disks.s3.region' => $storage_settings['s3_region'],
                            'filesystems.disks.s3.bucket' => $storage_settings['s3_bucket'],
                            'filesystems.disks.s3.url' => $storage_settings['s3_url'],
                            'filesystems.disks.s3.endpoint' => $storage_settings['s3_endpoint'],
                        ]
                    );
                    $max_size = !empty($storage_settings['s3_max_upload_size']) ? $storage_settings['s3_max_upload_size'] : '2048';
                    $mimes =  !empty($storage_settings['s3_storage_validation']) ? $storage_settings['s3_storage_validation'] : 'jpeg,jpg,png,svg,zip,txt,gif,docx';
                } else {
                    $max_size = !empty($storage_settings['local_storage_max_upload_size']) ? $storage_settings['local_storage_max_upload_size'] : '2048';
                    $mimes =  !empty($storage_settings['local_storage_validation']) ? $storage_settings['local_storage_validation'] : 'jpeg,jpg,png,svg,zip,txt,gif,docx';
                }
                $file = $request->$key_name;
                if (count($custom_validation) > 0) {
                    $validation = $custom_validation;
                } else {
                    $validation = [
                        'mimes:' . $mimes,
                        'max:' . $max_size,
                    ];
                }
                $validator = Validator::make($request->all(), [
                    $key_name => $validation
                ]);
                if ($validator->fails()) {
                    $res = [
                        'flag' => 0,
                        'msg' => $validator->messages()->first(),
                    ];
                    return $res;
                } else {
                    $name = $name;
                    $save = Storage::disk($storage_settings['storage_setting'])->putFileAs(
                        $path,
                        $file,
                        $name
                    );
                    if ($storage_settings['storage_setting'] == 'wasabi') {
                        $url = $save;
                    } elseif ($storage_settings['storage_setting'] == 's3') {
                        $url = $save;
                    } else {
                        $url = 'uploads/' . $save;
                    }
                    $res = [
                        'flag' => 1,
                        'msg'  => 'success',
                        'url'  => $url
                    ];
                    return $res;
                }
            } else {
                $res = [
                    'flag' => 0,
                    'msg' => 'not set configurations',
                ];
                return $res;
            }
        } catch (\Exception $e) {
            $res = [
                'flag' => 0,
                'msg' => $e->getMessage(),
            ];
            return $res;
        }
    }
}

if (!function_exists('multi_upload_file')) {
    function multi_upload_file($request, $key_name, $name, $path, $custom_validation = [])
    {
        try {
            $storage_settings = getAdminAllSetting();

            if (isset($storage_settings['storage_setting'])) {
                if ($storage_settings['storage_setting'] == 'wasabi') {
                    config(
                        [
                            'filesystems.disks.wasabi.key' => $storage_settings['wasabi_key'],
                            'filesystems.disks.wasabi.secret' => $storage_settings['wasabi_secret'],
                            'filesystems.disks.wasabi.region' => $storage_settings['wasabi_region'],
                            'filesystems.disks.wasabi.bucket' => $storage_settings['wasabi_bucket'],
                            'filesystems.disks.wasabi.root' => $storage_settings['wasabi_root'],
                            'filesystems.disks.wasabi.endpoint' => $storage_settings['wasabi_url']
                        ]
                    );
                    $max_size = !empty($storage_settings['wasabi_max_upload_size']) ? $storage_settings['wasabi_max_upload_size'] : '2048';
                    $mimes =  !empty($storage_settings['wasabi_storage_validation']) ? $storage_settings['wasabi_storage_validation'] : 'jpeg,jpg,png,svg,zip,txt,gif,docx';
                } else if ($storage_settings['storage_setting'] == 's3') {
                    config(
                        [
                            'filesystems.disks.s3.key' => $storage_settings['s3_key'],
                            'filesystems.disks.s3.secret' => $storage_settings['s3_secret'],
                            'filesystems.disks.s3.region' => $storage_settings['s3_region'],
                            'filesystems.disks.s3.bucket' => $storage_settings['s3_bucket'],
                            'filesystems.disks.s3.url' => $storage_settings['s3_url'],
                            'filesystems.disks.s3.endpoint' => $storage_settings['s3_endpoint'],
                        ]
                    );
                    $max_size = !empty($storage_settings['s3_max_upload_size']) ? $storage_settings['s3_max_upload_size'] : '2048';
                    $mimes =  !empty($storage_settings['s3_storage_validation']) ? $storage_settings['s3_storage_validation'] : 'jpeg,jpg,png,svg,zip,txt,gif,docx';
                } else {
                    $max_size = !empty($storage_settings['local_storage_max_upload_size']) ? $storage_settings['local_storage_max_upload_size'] : '2048';
                    $mimes =  !empty($storage_settings['local_storage_validation']) ? $storage_settings['local_storage_validation'] : 'jpeg,jpg,png,svg,zip,txt,gif,docx';
                }

                $file = $request;
                $key_validation = $key_name . '*';
                if (count($custom_validation) > 0) {
                    $validation = $custom_validation;
                } else {
                    $validation = [
                        'mimes:' . $mimes,
                        'max:' . $max_size,
                    ];
                }
                $validator = Validator::make(array($key_name => $request), [
                    $key_validation => $validation
                ]);
                if ($validator->fails()) {
                    $res = [
                        'flag' => 0,
                        'msg' => $validator->messages()->first(),
                    ];
                    return $res;
                } else {

                    $name = $name;

                    $save = Storage::disk($storage_settings['storage_setting'])->putFileAs(
                        $path,
                        $file,
                        $name
                    );

                    if ($storage_settings['storage_setting'] == 'wasabi') {
                        $url = $save;
                    } elseif ($storage_settings['storage_setting'] == 's3') {
                        $url = $save;
                    } else {
                        $url = 'uploads/' . $save;
                    }
                    $res = [
                        'flag' => 1,
                        'msg'  => 'success',
                        'url'  => $url
                    ];
                    return $res;
                }
            } else {
                $res = [
                    'flag' => 0,
                    'msg' => 'not set configration',
                ];
                return $res;
            }
        } catch (\Exception $e) {
            $res = [
                'flag' => 0,
                'msg' => $e->getMessage(),
            ];
            return $res;
        }
    }
}

if (!function_exists('check_file')) {
    function check_file($path)
    {

        if (!empty($path)) {
            $storage_settings = getAdminAllSetting();
            if(!empty($storage_settings) && !empty($storage_settings['storage_setting'])){
                if ($storage_settings['storage_setting'] == null || $storage_settings['storage_setting'] == 'local') {

                    return file_exists(base_path($path));
                } else {

                    if (isset($storage_settings['storage_setting']) && $storage_settings['storage_setting'] == 's3') {
                        config(
                            [
                                'filesystems.disks.s3.key' => $storage_settings['s3_key'],
                                'filesystems.disks.s3.secret' => $storage_settings['s3_secret'],
                                'filesystems.disks.s3.region' => $storage_settings['s3_region'],
                                'filesystems.disks.s3.bucket' => $storage_settings['s3_bucket'],
                                'filesystems.disks.s3.url' => $storage_settings['s3_url'],
                                'filesystems.disks.s3.endpoint' => $storage_settings['s3_endpoint'],
                            ]
                        );
                    } else if (isset($storage_settings['storage_setting']) && $storage_settings['storage_setting'] == 'wasabi') {
                        config(
                            [
                                'filesystems.disks.wasabi.key' => $storage_settings['wasabi_key'],
                                'filesystems.disks.wasabi.secret' => $storage_settings['wasabi_secret'],
                                'filesystems.disks.wasabi.region' => $storage_settings['wasabi_region'],
                                'filesystems.disks.wasabi.bucket' => $storage_settings['wasabi_bucket'],
                                'filesystems.disks.wasabi.root' => $storage_settings['wasabi_root'],
                                'filesystems.disks.wasabi.endpoint' => $storage_settings['wasabi_url']
                            ]
                        );
                    }
                    try {
                        return  Storage::disk($storage_settings['storage_setting'])->exists($path);
                    } catch (\Throwable $th) {
                        return 0;
                    }
                }
            }
        } else {
            return 0;
        }
    }
}

if (!function_exists('get_file')) {
    function get_file($path)
    {

        $storage_settings = getAdminAllSetting();

        if (isset($storage_settings['storage_setting']) && $storage_settings['storage_setting'] == 's3') {
            config(
                [
                    'filesystems.disks.s3.key' => $storage_settings['s3_key'],
                    'filesystems.disks.s3.secret' => $storage_settings['s3_secret'],
                    'filesystems.disks.s3.region' => $storage_settings['s3_region'],
                    'filesystems.disks.s3.bucket' => $storage_settings['s3_bucket'],
                    'filesystems.disks.s3.url' => $storage_settings['s3_url'],
                    'filesystems.disks.s3.endpoint' => $storage_settings['s3_endpoint'],
                ]
            );
            return Storage::disk('s3')->url($path);
        } else if (isset($storage_settings['storage_setting']) && $storage_settings['storage_setting'] == 'wasabi') {
            config(
                [
                    'filesystems.disks.wasabi.key' => $storage_settings['wasabi_key'],
                    'filesystems.disks.wasabi.secret' => $storage_settings['wasabi_secret'],
                    'filesystems.disks.wasabi.region' => $storage_settings['wasabi_region'],
                    'filesystems.disks.wasabi.bucket' => $storage_settings['wasabi_bucket'],
                    'filesystems.disks.wasabi.root' => $storage_settings['wasabi_root'],
                    'filesystems.disks.wasabi.endpoint' => $storage_settings['wasabi_url']
                ]
            );

            return Storage::disk('wasabi')->url($path);
        } else {
            return asset($path);
        }
    }
}
if (!function_exists('get_base_file')) {
    function get_base_file($path)
    {
        $admin_settings = getAdminAllSetting();
        if (isset($storage_settings['storage_setting']) && $storage_settings['storage_setting'] == 's3') {
            config(
                [
                    'filesystems.disks.s3.key' => $admin_settings['s3_key'],
                    'filesystems.disks.s3.secret' => $admin_settings['s3_secret'],
                    'filesystems.disks.s3.region' => $admin_settings['s3_region'],
                    'filesystems.disks.s3.bucket' => $admin_settings['s3_bucket'],
                    'filesystems.disks.s3.url' => $admin_settings['s3_url'],
                    'filesystems.disks.s3.endpoint' => $admin_settings['s3_endpoint'],
                ]
            );

            return Storage::disk('s3')->url($path);
        } else if (isset($storage_settings['storage_setting']) && $storage_settings['storage_setting'] == 'wasabi') {
            config(
                [
                    'filesystems.disks.wasabi.key' => $admin_settings['wasabi_key'],
                    'filesystems.disks.wasabi.secret' => $admin_settings['wasabi_secret'],
                    'filesystems.disks.wasabi.region' => $admin_settings['wasabi_region'],
                    'filesystems.disks.wasabi.bucket' => $admin_settings['wasabi_bucket'],
                    'filesystems.disks.wasabi.root' => $admin_settings['wasabi_root'],
                    'filesystems.disks.wasabi.endpoint' => $admin_settings['wasabi_url']
                ]
            );
            return Storage::disk('wasabi')->url($path);
        } else {
            return base_path($path);
        }
    }
}
if (!function_exists('delete_file')) {
    function delete_file($path)
    {
        if (check_file($path)) {
            $storage_settings = getAdminAllSetting();
            if (isset($storage_settings['storage_setting'])) {
                if ($storage_settings['storage_setting'] == 'local') {
                    return File::delete($path);
                } else {
                    if ($storage_settings['storage_setting'] == 's3') {
                        config(
                            [
                                'filesystems.disks.s3.key' => $storage_settings['s3_key'],
                                'filesystems.disks.s3.secret' => $storage_settings['s3_secret'],
                                'filesystems.disks.s3.region' => $storage_settings['s3_region'],
                                'filesystems.disks.s3.bucket' => $storage_settings['s3_bucket'],
                                'filesystems.disks.s3.url' => $storage_settings['s3_url'],
                                'filesystems.disks.s3.endpoint' => $storage_settings['s3_endpoint'],
                            ]
                        );
                    } else if ($storage_settings['storage_setting'] == 'wasabi') { {
                            config(
                                [
                                    'filesystems.disks.wasabi.key' => $storage_settings['wasabi_key'],
                                    'filesystems.disks.wasabi.secret' => $storage_settings['wasabi_secret'],
                                    'filesystems.disks.wasabi.region' => $storage_settings['wasabi_region'],
                                    'filesystems.disks.wasabi.bucket' => $storage_settings['wasabi_bucket'],
                                    'filesystems.disks.wasabi.root' => $storage_settings['wasabi_root'],
                                    'filesystems.disks.wasabi.endpoint' => $storage_settings['wasabi_url']
                                ]
                            );
                        }
                        return Storage::disk($storage_settings['storage_setting'])->delete($path);
                    }
                }
            }
        }
    }
}

if (!function_exists('get_size')) {
    function get_size($url)
    {
        $url = str_replace(' ', '%20', $url);
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_NOBODY, TRUE);

        $data = curl_exec($ch);
        $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

        curl_close($ch);
        return $size;
    }
}
if (!function_exists('delete_folder')) {
    function delete_folder($path)
    {
        $storage_settings = getAdminAllSetting();
        if (isset($storage_settings['storage_setting'])) {

            if ($storage_settings['storage_setting'] == 'local') {
                if (is_dir(Storage::path($path))) {
                    return \File::deleteDirectory(Storage::path($path));
                }
            } else {
                if ($storage_settings['storage_setting'] == 's3') {
                    config(
                        [
                            'filesystems.disks.s3.key' => $storage_settings['s3_key'],
                            'filesystems.disks.s3.secret' => $storage_settings['s3_secret'],
                            'filesystems.disks.s3.region' => $storage_settings['s3_region'],
                            'filesystems.disks.s3.bucket' => $storage_settings['s3_bucket'],
                            'filesystems.disks.s3.url' => $storage_settings['s3_url'],
                            'filesystems.disks.s3.endpoint' => $storage_settings['s3_endpoint'],
                        ]
                    );
                } else if ($storage_settings['storage_setting'] == 'wasabi') {
                    config(
                        [
                            'filesystems.disks.wasabi.key' => $storage_settings['wasabi_key'],
                            'filesystems.disks.wasabi.secret' => $storage_settings['wasabi_secret'],
                            'filesystems.disks.wasabi.region' => $storage_settings['wasabi_region'],
                            'filesystems.disks.wasabi.bucket' => $storage_settings['wasabi_bucket'],
                            'filesystems.disks.wasabi.root' => $storage_settings['wasabi_root'],
                            'filesystems.disks.wasabi.endpoint' => $storage_settings['wasabi_url']
                        ]
                    );
                }
                return Storage::disk($storage_settings['storage_setting'])->deleteDirectory($path);
            }
        }
    }
}
if (!function_exists('delete_directory')) {
    function delete_directory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }
}
if (!function_exists('currency')) {
    function currency($code = null)
    {
        if ($code == null) {
            $c = Currency::get();
        } else {
            $c = Currency::where('code', $code)->first();
        }
        return $c;
    }
}

// Company Subscription Details
if (!function_exists('SubscriptionDetails')) {
    function SubscriptionDetails($user_id = null)
    {
        $data = [];
        $data['status'] = false;
        if ($user_id != null) {
            $user = User::find($user_id);
        } elseif (\Auth::check()) {
            $user = \Auth::user();
        }

        if (isset($user) && !empty($user)) {
            if ($user->type != 'company' && $user->type != 'super admin') {
                $user = User::find($user->created_by);
            }

            if (!empty($user)) {
                if ($user->active_plan != 0) {
                    $data['status'] = true;
                    $data['active_plan'] = $user->active_plan;
                    $data['billing_type'] = $user->billing_type;
                    $data['plan_expire_date'] = $user->plan_expire_date;
                    $data['active_module'] = ActivatedModule();
                    $data['total_user'] = $user->total_user == -1 ? 'Unlimited' : (isset($user->total_user) ? $user->total_user : 'Unlimited');
                    $data['total_workspace'] = $user->total_workspace == -1 ? 'Unlimited' : (isset($user->total_workspace) ? $user->total_workspace : 'Unlimited');
                    $data['seeder_run'] = $user->seeder_run;
                }
            }
        }
        return $data;
    }
}


if (!function_exists('PlanCheck')) {
    function PlanCheck($type = 'User', $id = null)
    {
        if (!empty($id)) {
            $user = User::where('id', $id)->first();
            if ($user->type == 'company') {
                $id = $user->id;
            } else {
                $user = User::where('id', $user->created_by)->first();
                $id = $user->id;
            }
        } else {
            $user = \Auth::user();
            if ($user->type == 'company') {
                $id = $user->id;
            } else {
                $user = User::where('id', $user->created_by)->first();
                $id = $user->id;
            }
        }
        if ($type == "User") {
            if ($user->total_user >= 0) {
                if ($user->type == 'company') {
                    $users = User::where('created_by', $id)->where('workspace_id', getActiveWorkSpace())->get();
                } else {
                    $users = User::where('created_by', $user->created_by)->get();
                }
                if ($users->count() >= $user->total_user) {
                    return false;
                } else {
                    return true;
                }
            } elseif ($user->total_user < 0) {
                return true;
            }
        }
        if ($type == "Workspace") {
            if ($user->total_workspace >= 0) {
                $workspace = WorkSpace::where('created_by', $id)->get();

                if ($workspace->count() >= $user->total_workspace) {
                    return false;
                } else {
                    return true;
                }
            } elseif ($user->total_workspace < 0) {
                return true;
            }
        }
    }
}
if (!function_exists('CheckCoupon')) {
    function CheckCoupon($code, $price = 0, $plan_id=null)
    {
        if (empty($code) || intval($price) <= 0) {
            return $price;
        }

        $coupon = Coupon::where('code', strtoupper($code))
                        ->where('is_active', '1')
                        ->first();

        if (empty($coupon)) {
            return $price;
        }

        $usedCoupon = $coupon->used_coupon();
        $userUsedCoupon = \Auth::user()->user_coupon_user($coupon);

        if ($usedCoupon >= $coupon->limit ||
            $userUsedCoupon >= $coupon->limit_per_user ||
            $coupon->minimum_spend > $price ||
            $coupon->maximum_spend < $price ||
            $coupon->expiry_date < date('Y-m-d')) {
            return $price;
        }

        switch ($coupon->type) {
            case 'percentage':
                $discountValue = ($price / 100) * $coupon->discount;
                $finalPrice = $price - $discountValue;
                break;
            case 'flat':
                $finalPrice = $price - $coupon->discount;
                break;
            case 'fixed':
                if ((!empty($coupon->included_module) && in_array($plan_id, explode(',', $coupon->included_module))) ||
                    (empty($coupon->included_module) && !in_array($plan_id, explode(',', $coupon->excluded_module)))) {
                    $finalPrice = $price - $coupon->discount;
                } else {
                    return $price;
                }
                break;
            default:
                return $price;
        }

        return $finalPrice;
    }
}

if (!function_exists('UserCoupon')) {
    function UserCoupon($code, $orderID, $user_id = null)
    {
        if (!empty($code)) {
            $coupons = Coupon::where('code', strtoupper($code))->where('is_active', '1')->first();
            if ($user_id) {
                $user = User::find($user_id);
            } else {
                $user = \Auth::user();
            }
            if (!empty($coupons)) {
                $userCoupon         = new UserCoupon();
                $userCoupon->user   = $user->id;
                $userCoupon->coupon = $coupons->id;
                $userCoupon->order  = $orderID;
                $userCoupon->save();

                $usedCoupun = $coupons->used_coupon();
                if ($coupons->limit <= $usedCoupun) {
                    $coupons->is_active = 0;
                    $coupons->save();
                }
            }
        }
    }
}

// if Subscription price is 0 then call this
if (!function_exists('DirectAssignPlan')) {
    function DirectAssignPlan($plan_id, $duration, $user_module, $counter, $type, $coupon_code = null, $user_id = null)
    {
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $plan = Plan::find($plan_id);
        if (empty($user_id)) {
            $user_id = \Auth::user()->id;
        }
        $user = User::find($user_id);
        $assignPlan = $user->assignPlan($plan->id, $duration, $user_module, $counter, $user_id);
        if ($assignPlan['is_success']) {
            $order = Order::create(
                [
                    'order_id' => $orderID,
                    'name' => null,
                    'email' => null,
                    'card_number' => null,
                    'card_exp_month' => null,
                    'card_exp_year' => null,
                    'plan_name' => !empty($plan->name) ? $plan->name : 'Basic Package',
                    'plan_id' => $plan->id,
                    'price' => 0,
                    'price_currency' => admin_setting('defult_currancy'),
                    'txn_id' => '',
                    'payment_type' => !empty($type) ? $type : "STRIPE",
                    'payment_status' => 'succeeded',
                    'receipt' => null,
                    'user_id' => $user_id,
                ]
            );
            if ($coupon_code) {

                UserCoupon($coupon_code, $order);
            }
            return ['is_success' => true];
        } else {
            return ['is_success' => false];
        }
    }
}
if (!function_exists('makeEmailLang')) {
    function makeEmailLang($lang)
    {
        $templates = EmailTemplate::all();
        foreach ($templates as $template) {

            $default_lang  = EmailTemplateLang::where('parent_id', '=', $template->id)->where('lang', 'LIKE', 'en')->first();

            $emailTemplateLang              = new EmailTemplateLang();
            $emailTemplateLang->parent_id   = $template->id;
            $emailTemplateLang->lang        = $lang;
            $emailTemplateLang->subject     = $default_lang->subject;
            $emailTemplateLang->content     = $default_lang->content;
            $emailTemplateLang->variables   = $default_lang->variables;
            $emailTemplateLang->save();
        }
    }
}
if (!function_exists('error_res')) {
    function error_res($msg = "", $args = array())
    {
        $msg       = $msg == "" ? "error" : $msg;
        $msg_id    = 'error.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg       = $msg_id == $converted ? $msg : $converted;
        $json      = array(
            'flag' => 0,
            'msg' => $msg,
        );

        return $json;
    }
}

if (!function_exists('success_res')) {
    function success_res($msg = "", $args = array())
    {
        $msg       = $msg == "" ? "success" : $msg;
        $json      = array(
            'flag' => 1,
            'msg' => $msg,
        );

        return $json;
    }
}

if (!function_exists('GetDeviceType')) {
    function GetDeviceType($user_agent)
    {
        $mobile_regex = '/(?:phone|windows\s+phone|ipod|blackberry|(?:android|bb\d+|meego|silk|googlebot) .+? mobile|palm|windows\s+ce|opera mini|avantgo|mobilesafari|docomo)/i';
        $tablet_regex = '/(?:ipad|playbook|(?:android|bb\d+|meego|silk)(?! .+? mobile))/i';
        if (preg_match_all($mobile_regex, $user_agent)) {
            return 'mobile';
        } else {
            if (preg_match_all($tablet_regex, $user_agent)) {
                return 'tablet';
            } else {
                return 'desktop';
            }
        }
    }
}

// Get Cache Size
if (!function_exists('CacheSize')) {
    function CacheSize()
    {
        //start for cache clear
        $file_size = 0;
        foreach (\File::allFiles(storage_path('/framework')) as $file) {
            $file_size += $file->getSize();
        }
        $file_size = number_format($file_size / 1000000, 4);

        return $file_size;
    }
}

if (!function_exists('get_module_img')) {
    function get_module_img($module)
    {
        $url = url("/Modules/" . $module . '/favicon.png');
        return $url;
    }
}

if (!function_exists('sidebar_logo')) {
    function sidebar_logo()
    {
        $admin_settings = getAdminAllSetting();
        if (false) {
            $company_settings = getCompanyAllSetting();

            if ((isset($company_settings['cust_darklayout']) ? $company_settings['cust_darklayout'] : 'off') == 'on') {
                if (!empty($company_settings['logo_light'])) {
                    if (check_file($company_settings['logo_light'])) {
                        return $company_settings['logo_light'];
                    } else {
                        return 'uploads/logo/logo_light.png';
                    }
                } else {
                    if (!empty($admin_settings['logo_light'])) {
                        if (check_file($admin_settings['logo_light'])) {
                            return $admin_settings['logo_light'];
                        } else {
                            return 'uploads/logo/logo_light.png';
                        }
                    } else {
                        return 'uploads/logo/logo_light.png';
                    }
                }
            } else {
                if (!empty($company_settings['logo_dark'])) {
                    if (check_file($company_settings['logo_dark'])) {
                        return $company_settings['logo_dark'];
                    } else {
                        return 'uploads/logo/logo_dark.png';
                    }
                } else {
                    if (!empty($admin_settings['logo_dark'])) {
                        if (check_file($admin_settings['logo_dark'])) {
                            return $admin_settings['logo_dark'];
                        } else {
                            return 'uploads/logo/logo_dark.png';
                        }
                    } else {
                        return 'uploads/logo/logo_dark.png';
                    }
                }
            }
        } else {
            if ((isset($admin_settings['cust_darklayout']) ? $admin_settings['cust_darklayout'] : 'off') == 'on') {
                if (!empty($admin_settings['logo_light'])) {
                    if (check_file($admin_settings['logo_light'])) {
                        return $admin_settings['logo_light'];
                    } else {
                        return 'uploads/logo/logo_light.png';
                    }
                } else {
                    return 'uploads/logo/logo_light.png';
                }
            } else {
                if (!empty($admin_settings['logo_dark'])) {
                    if (check_file($admin_settings['logo_dark'])) {
                        return $admin_settings['logo_dark'];
                    } else {
                        return 'uploads/logo/logo_dark.png';
                    }
                } else {
                    return 'uploads/logo/logo_dark.png';
                }
            }
        }
    }
}

if (!function_exists('light_logo')) {
    function light_logo()
    {
        if (\Auth::check()) {
            $company_settings = getCompanyAllSetting();
            $logo_light = isset($company_settings['logo_light']) ? $company_settings['logo_light'] : 'uploads/logo/logo_light.png';
        } else {
            $admin_settings = getAdminAllSetting();
            $logo_light = isset($admin_settings['logo_light']) ? $admin_settings['logo_light'] : 'uploads/logo/logo_light.png';
        }
        if (check_file($logo_light)) {
            return $logo_light;
        } else {
            return 'uploads/logo/logo_dark.png';
        }
    }
}

if (!function_exists('dark_logo')) {
    function dark_logo()
    {
        if (\Auth::check()) {
            $company_settings = getCompanyAllSetting();
            $logo_dark = isset($company_settings['logo_dark']) ? $company_settings['logo_dark'] : 'uploads/logo/logo_dark.png';
        } else {
            $admin_settings = getAdminAllSetting();
            $logo_dark = isset($admin_settings['logo_dark']) ? $admin_settings['logo_dark'] : 'uploads/logo/logo_dark.png';
        }
        if (check_file($logo_dark)) {
            return $logo_dark;
        } else {
            return 'uploads/logo/logo_dark.png';
        }
    }
}

if (!function_exists('currency_format')) {
    function currency_format($price, $company_id = null, $workspace = null)
    {

        return number_format($price, company_setting('currency_format', $company_id, $workspace), '.', '');
    }
}

if (!function_exists('currency_format_with_sym')) {

    function currency_format_with_sym($price, $company_id = null, $workspace = null)
    {
        if (!empty($company_id) && empty($workspace)) {
            $company_settings = getCompanyAllSetting($company_id);
        } elseif (!empty($company_id) && !empty($workspace)) {
            $company_settings = getCompanyAllSetting($company_id, $workspace);
        } else {
            $company_settings = getCompanyAllSetting();
        }
        $symbol_position = 'pre';
        $currancy_symbol = '$';
        $format = '1';
        $number = explode('.', $price);
        $length = strlen(trim($number[0]));
        if ($length > 3) {
            $decimal_separator  = isset($company_settings['decimal_separator']) && $company_settings['decimal_separator'] === 'dot' ? ',' : ',';
            $thousand_separator = isset($company_settings['thousand_separator']) && $company_settings['thousand_separator'] === 'dot' ? '.' : ',';
        } else {
            $decimal_separator  = isset($company_settings['decimal_separator']) == 'dot'  ? '.' : ',';
            $thousand_separator = isset($company_settings['thousand_separator']) == 'dot' ? '.' : ',';
        }
        if (isset($company_settings['site_currency_symbol_position'])) {
            $symbol_position = $company_settings['site_currency_symbol_position'];
        }
        if (isset($company_settings['defult_currancy_symbol'])) {
            $currancy_symbol = $company_settings['defult_currancy_symbol'];
        }
        if (isset($company_settings['currency_format'])) {
            $format = $company_settings['currency_format'];
        }
        if (isset($company_settings['currency_space'])) {
            $currency_space = isset($company_settings['currency_space']) ? $company_settings['currency_space'] : '';
        }
        if (isset($company_settings['site_currency_symbol_name'])) {
            $defult_currancy = $company_settings['defult_currancy'];
            $defult_currancy_symbol = $company_settings['defult_currancy_symbol'];
            $currancy_symbol = $company_settings['site_currency_symbol_name'] == 'symbol' ? $defult_currancy_symbol : $defult_currancy;
        }

        return (
            ($symbol_position == "pre")  ?  $currancy_symbol : '') . ((isset($currency_space) && $currency_space) == 'withspace' ? ' ' : '')
            . number_format($price, $format, $decimal_separator, $thousand_separator) . ((isset($currency_space) && $currency_space) == 'withspace' ? ' ' : '') .
            (($symbol_position == "post") ?  $currancy_symbol : '');
    }
}

if (!function_exists('company_date_formate')) {
    function company_date_formate($date, $company_id = null, $workspace = null)
    {

        if (!empty($company_id) && empty($workspace)) {
            $company_settings = getCompanyAllSetting($company_id);
        } elseif (!empty($company_id) && !empty($workspace)) {
            $company_settings = getCompanyAllSetting($company_id, $workspace);
        } else {
            $company_settings = getCompanyAllSetting();
        }
        $date_formate = !empty($company_settings['site_date_format']) ? $company_settings['site_date_format'] : 'd-m-y';

        return date($date_formate, strtotime($date));
    }
}

if (!function_exists('super_currency_format_with_sym')) {
    function super_currency_format_with_sym($price)
    {

        $admin_settings = getAdminAllSetting();
        $symbol_position = 'pre';
        $symbol = '$';
        $format = '1';
        $number = explode('.', $price);
        $length = strlen(trim($number[0]));
        if ($length > 3) {
            $decimal_separator  = isset($admin_settings['decimal_separator']) && $admin_settings['decimal_separator'] === 'dot' ? ',' : ',';
            $thousand_separator = isset($admin_settings['thousand_separator']) && $admin_settings['thousand_separator'] === 'dot' ? '.' : ',';
        } else {
            $decimal_separator  = isset($admin_settings['decimal_separator']) == 'dot'  ? '.' : ',';
            $thousand_separator = isset($admin_settings['thousand_separator']) == 'dot' ? '.' : ',';
        }

        if (isset($admin_settings['site_currency_symbol_position']) && $admin_settings['site_currency_symbol_position'] == "post") {
            $symbol_position = 'post';
        }

        if (isset($admin_settings['defult_currancy_symbol'])) {
            $symbol = $admin_settings['defult_currancy_symbol'];
        }

        if (isset($admin_settings['currency_format'])) {
            $format = $admin_settings['currency_format'];
        }

        if (isset($admin_settings['currency_space'])) {
            $currency_space = isset($admin_settings['currency_space']) ? $admin_settings['currency_space'] : '';
        }
        if (isset($admin_settings['site_currency_symbol_name'])) {
            $defult_currancy = $admin_settings['defult_currancy'];
            $defult_currancy_symbol = $admin_settings['defult_currancy_symbol'];
            $symbol = $admin_settings['site_currency_symbol_name'] == 'symbol' ? $defult_currancy_symbol : $defult_currancy;
        }
        return (
            ($symbol_position == "pre")  ?  $symbol : '') . ((isset($currency_space) && $currency_space) == 'withspace' ? ' ' : '')
            . number_format($price, $format, $decimal_separator, $thousand_separator) . ((isset($currency_space) && $currency_space) == 'withspace' ? ' ' : '') .
            (($symbol_position == "post") ?  $symbol : '');
    }
}
if (!function_exists('company_datetime_formate')) {
    function company_datetime_formate($date, $company_id = null, $workspace = null)
    {
        $company_settings = getCompanyAllSetting($company_id, $workspace);
        $date_formate = !empty($company_settings['site_date_format']) ? $company_settings['site_date_format'] : 'd-m-y';
        $time_formate = !empty($company_settings['site_time_format']) ? $company_settings['site_time_format'] : 'H:i';
        return date($date_formate . ' ' . $time_formate, strtotime($date));
    }
}
if (!function_exists('company_Time_formate')) {
    function company_Time_formate($time, $company_id = null, $workspace = null)
    {
        if (!empty($company_id) && empty($workspace)) {
            $company_settings = getCompanyAllSetting($company_id);
        } elseif (!empty($company_id) && !empty($workspace)) {
            $company_settings = getCompanyAllSetting($company_id, $workspace);
        } else {
            $company_settings = getCompanyAllSetting();
        }
        $time_formate = !empty($company_settings['site_time_format']) ? $company_settings['site_time_format'] : 'H:i';
        return date($time_formate, strtotime($time));
    }
}
// module price name
if (!function_exists('ModulePriceByName')) {
    function ModulePriceByName($module_name)
    {
        static $addons = [];
        static $resultArray = [];
        if (count($addons) == 0 && count($resultArray) == 0) {
            $addons = AddOn::all()->toArray();
            $resultArray = array_reduce($addons, function ($carry, $item) {
                // Check if both "module" and "name" keys exist in the current item
                if (isset($item['module'])) {
                    // Add a new key-value pair to the result array
                    $carry[$item['module']]['monthly_price'] = $item['monthly_price'];
                    $carry[$item['module']]['yearly_price'] = $item['yearly_price'];
                }
                return $carry;
            }, []);
        }

        $module = Module::find($module_name);
        $data = [];
        $data['monthly_price'] = 0;
        $data['yearly_price'] = 0;

        if (!empty($module)) {
            $path = $module->getPath() . '/module.json';
            $json = json_decode(file_get_contents($path), true);

            $data['monthly_price'] = (isset($json['monthly_price']) && !empty($json['monthly_price'])) ? $json['monthly_price'] : 0;
            $data['yearly_price'] = (isset($json['yearly_price']) && !empty($json['yearly_price'])) ? $json['yearly_price'] : 0;
        }

        if (isset($resultArray)) {
            $data['monthly_price'] = isset($resultArray[$module_name]['monthly_price']) ? $resultArray[$module_name]['monthly_price'] : $data['monthly_price'];
            $data['yearly_price'] = isset($resultArray[$module_name]['yearly_price']) ? $resultArray[$module_name]['yearly_price'] : $data['yearly_price'];
        }

        return $data;
    }
}
// invoice template Data

if (!function_exists('templateData')) {
    function templateData()
    {
        $arr              = [];
        $arr['colors']    = [
            '003580',
            '666666',
            '6676ef',
            'f50102',
            'f9b034',
            'fbdd03',
            'c1d82f',
            '37a4e4',
            '8a7966',
            '6a737b',
            '050f2c',
            '0e3666',
            '3baeff',
            '3368e6',
            'b84592',
            'f64f81',
            'f66c5f',
            'fac168',
            '46de98',
            '40c7d0',
            'be0028',
            '2f9f45',
            '371676',
            '52325d',
            '511378',
            '0f3866',
            '48c0b6',
            '297cc0',
            'ffffff',
            '000',
        ];
        $arr['templates'] = [
            "template1" => "New York",
            "template2" => "Toronto",
            "template3" => "Rio",
            "template4" => "London",
            "template5" => "Istanbul",
            "template6" => "Mumbai",
            "template7" => "Hong Kong",
            "template8" => "Tokyo",
            "template9" => "Sydney",
            "template10" => "Paris",
        ];
        return $arr;
    }
}
if (!function_exists('AnnualLeaveCycle')) {
    function AnnualLeaveCycle()
    {
        $start_date = date('Y-m-d', strtotime(date('Y') . '-01-01 -1 day'));
        $end_date = date('Y-m-d', strtotime(date('Y') . '-12-31 +1 day'));

        $date['start_date'] = $start_date;
        $date['end_date']   = $end_date;

        return $date;
    }
}

// time tracker
if (!function_exists('second_to_time')) {
    function second_to_time($seconds = 0)
    {
        $H = floor($seconds / 3600);
        $i = ($seconds / 60) % 60;
        $s = $seconds % 60;
        $time = sprintf("%02d:%02d:%02d", $H, $i, $s);
        return $time;
    }
}
