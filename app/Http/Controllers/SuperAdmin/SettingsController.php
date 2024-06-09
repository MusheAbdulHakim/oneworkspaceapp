<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Mail\TestMail;
use App\Models\ApikeySetiings;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($settings)
    {
        $file_type = config('files_types');
        $timezones = config('timezones');

        $ai_key_settings = ApikeySetiings::get();

        return view('super-admin.settings.index', compact('settings', 'file_type', 'timezones', 'ai_key_settings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('setting manage')) {
            $post = $request->all();
            unset($post['_token']);
            unset($post['_method']);

            if (!isset($post['landing_page'])) {
                $post['landing_page'] = 'off';
            }
            if (!isset($post['site_rtl'])) {
                $post['site_rtl'] = 'off';
            }
            if (!isset($post['signup'])) {
                $post['signup'] = 'off';
            }
            if (!isset($post['email_verification'])) {
                $post['email_verification'] = 'off';
            }
            if (!isset($post['site_transparent'])) {
                $post['site_transparent'] = 'off';
            }
            if (!isset($post['cust_darklayout'])) {
                $post['cust_darklayout'] = 'off';
            }
            if (isset($request->color) && $request->color_flag == 'false') {
                $post['color'] = $request->color;
            } else {
                $post['color'] = $request->custom_color;
            }

             if (!isset($post['category_wise_sidemenu'])) {
                $post['category_wise_sidemenu'] = 'off';
            }

            $admin_settings = getAdminAllSetting();
            if ($request->hasFile('logo_dark')) {
                $logo_dark = 'logo_dark.png';
                $uplaod = upload_file($request, 'logo_dark', $logo_dark, 'logo');

                $logo_dark =  'logo_dark_' . time() . '.png';
                $uplaod = upload_file($request, 'logo_dark', $logo_dark, 'logo');
                if ($uplaod['flag'] == 1) {
                    $post['logo_dark'] = $uplaod['url'];

                    $old_logo_dark = isset($admin_settings['logo_dark']) ? $admin_settings['logo_dark'] : null;
                    if (!empty($old_logo_dark) && check_file($old_logo_dark)) {
                        delete_file($old_logo_dark);
                    }
                } else {
                    return redirect()->back()->with('error', $uplaod['msg']);
                }
            }
            if ($request->hasFile('logo_light')) {

                $logo_light = 'logo_light.png';
                $uplaod = upload_file($request, 'logo_light', $logo_light, 'logo');

                $logo_light =  'logo_light_' . time() . '.png';
                $uplaod = upload_file($request, 'logo_light', $logo_light, 'logo');
                if ($uplaod['flag'] == 1) {
                    $post['logo_light'] = $uplaod['url'];

                    $old_logo_light = isset($admin_settings['logo_light']) ? $admin_settings['logo_light'] : null;
                    if (!empty($old_logo_light) && check_file($old_logo_light)) {
                        delete_file($old_logo_light);
                    }
                } else {
                    return redirect()->back()->with('error', $uplaod['msg']);
                }
            }
            if ($request->hasFile('favicon')) {

                $favicon = 'favicon.png';
                $uplaod = upload_file($request, 'favicon', $favicon, 'logo');

                $favicon =  'favicon_' . time() . '.png';
                $uplaod = upload_file($request, 'favicon', $favicon, 'logo');
                if ($uplaod['flag'] == 1) {
                    $post['favicon'] = $uplaod['url'];

                    $old_favicon = isset($admin_settings['favicon']) ? $admin_settings['favicon'] : null;
                    if (!empty($old_favicon) && check_file($old_favicon)) {
                        delete_file($old_favicon);
                    }
                } else {
                    return redirect()->back()->with('error', $uplaod['msg']);
                }
            }

            foreach ($post as $key => $value) {
                // Define the data to be updated or inserted
                $data = [
                    'key' => $key,
                    'workspace' => getActiveWorkSpace(),
                    'created_by' => creatorId(),
                ];

                // Check if the record exists, and update or insert accordingly
                Setting::updateOrInsert($data, ['value' => $value]);
            }

            // Settings Cache forget
            AdminSettingCacheForget();
            comapnySettingCacheForget();
            sideMenuCacheForget();
            return redirect()->back()->with('success', __('Setting save sucessfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function SystemStore(Request $request)
    {
        if (Auth::user()->isAbleTo('setting manage')) {
            $post = $request->all();
            unset($post['_token']);
            unset($post['_method']);

            foreach ($post as $key => $value) {
                // Define the data to be updated or inserted
                $data = [
                    'key' => $key,
                    'workspace' => getActiveWorkSpace(),
                    'created_by' => creatorId(),
                ];

                // Check if the record exists, and update or insert accordingly
                Setting::updateOrInsert($data, ['value' => $value]);
            }
            // Settings Cache forget
            AdminSettingCacheForget();
            comapnySettingCacheForget();
            return redirect()->back()->with('success', 'Setting save sucessfully.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function CookieSetting(Request $request)
    {
        if ($request->has('enable_cookie')) {
            $validator = \Validator::make($request->all(), [
                'cookie_title' => 'required',
                'cookie_description' => 'required',
                'strictly_cookie_title' => 'required',
                'strictly_cookie_description' => 'required',
                'more_information_description' => 'required',
                'contactus_url' => 'required',
            ]);
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
        }


        if ($request->has('enable_cookie')) {
            $post = $request->all();
            unset($post['_token'], $post['_method']);

            $post['cookie_logging'] = isset($request->cookie_logging) ? $request->cookie_logging : 'off';
            foreach ($post as $key => $value) {
                // Define the data to be updated or inserted
                $data = [
                    'key' => $key,
                    'workspace' => getActiveWorkSpace(),
                    'created_by' => creatorId(),
                ];

                // Check if the record exists, and update or insert accordingly
                Setting::updateOrInsert($data, ['value' => $value]);
            }
        } else {
            // Define the data to be updated or inserted
            $data = [
                'key' => 'enable_cookie',
                'workspace' => getActiveWorkSpace(),
                'created_by' => creatorId(),
            ];

            // Check if the record exists, and update or insert accordingly
            Setting::updateOrInsert($data, ['value' => 'off']);
        }
        // Settings Cache forget
        AdminSettingCacheForget();
        comapnySettingCacheForget();
        return redirect()->back()->with('success', 'Cookie setting save successfully.');
    }

    public function CookieConsent(Request $request)
    {
        if (admin_setting('enable_cookie') == "on" &&  admin_setting('cookie_logging') == "on") {
            try {

                $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);
                // Generate new CSV line
                $browser_name = $whichbrowser->browser->name ?? null;
                $os_name = $whichbrowser->os->name ?? null;
                $browser_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
                $device_type = GetDeviceType($_SERVER['HTTP_USER_AGENT']);

                $ip = $_SERVER['REMOTE_ADDR'];

                $query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));

                if ($query['status'] == 'success') {
                    $date = (new \DateTime())->format('Y-m-d');
                    $time = (new \DateTime())->format('H:i:s') . ' UTC';


                    $new_line = implode(',', [$ip, $date, $time, implode('-', $request['cookie']), $device_type, $browser_language, $browser_name, $os_name, isset($query) ? $query['country'] : '', isset($query) ? $query['region'] : '', isset($query) ? $query['regionName'] : '', isset($query) ? $query['city'] : '', isset($query) ? $query['zip'] : '', isset($query) ? $query['lat'] : '', isset($query) ? $query['lon'] : '']);
                    if (!check_file('/uploads/sample/cookie_data.csv')) {
                        $first_line = 'IP,Date,Time,Accepted-cookies,Device type,Browser anguage,Browser name,OS Name,Country,Region,RegionName,City,Zipcode,Lat,Lon';
                        file_put_contents(base_path() . '/uploads/sample/cookie_data.csv', $first_line . PHP_EOL, FILE_APPEND | LOCK_EX);
                    }
                    file_put_contents(base_path() . '/uploads/sample/cookie_data.csv', $new_line . PHP_EOL, FILE_APPEND | LOCK_EX);
                }
            } catch (\Throwable $th) {
                return response()->json('error');
            }
            return response()->json('success');
        }
        return response()->json('error');
    }

    public function savePusherSettings(Request $request)
    {
        if (\Auth::user()->type == 'super admin') {
            $request->validate(
                [
                    'pusher_app_id' => 'required',
                    'pusher_app_key' => 'required',
                    'pusher_app_secret' => 'required',
                    'pusher_app_cluster' => 'required',
                ]
            );
            try {
                $pusher_settings = [];

                $pusher_settings['PUSHER_APP_ID'] = $request->pusher_app_id;
                $pusher_settings['PUSHER_APP_KEY'] = $request->pusher_app_key;
                $pusher_settings['PUSHER_APP_SECRET'] = $request->pusher_app_secret;
                $pusher_settings['PUSHER_APP_CLUSTER'] = $request->pusher_app_cluster;

                foreach ($pusher_settings as $key => $value) {
                    // Define the data to be updated or inserted
                    $data = [
                        'key' => $key,
                        'workspace' => getActiveWorkSpace(),
                        'created_by' => creatorId(),
                    ];

                    // Check if the record exists, and update or insert accordingly
                    Setting::updateOrInsert($data, ['value' => $value]);
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
            // Settings Cache forget
            AdminSettingCacheForget();
            comapnySettingCacheForget();
            return redirect()->back()->with('success', __('Pusher successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function seoSetting(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'meta_title' => 'required|string',
                'meta_keywords' => 'required|string',
                'meta_description' => 'required|string',
                'meta_image' => 'mimes:jpeg,jpg,png,gif',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        if ($request->hasFile('meta_image')) {
            $filenameWithExt = $request->file('meta_image')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('meta_image')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            $uplaod = upload_file($request, 'meta_image', $fileNameToStore, 'meta');

            if ($uplaod['flag'] == 1) {
                // old img delete
                $settings = getAdminAllSetting();
                if ((!empty($settings['meta_image'])) && strpos($settings['meta_image'], 'meta_image.png') == false && check_file($settings['meta_image'])) {
                    delete_file($settings['meta_image']);
                }
            } else {
                return redirect()->back()->with('error', $uplaod['msg']);
            }
        }

        try {
            $post = $request->all();
            unset($post['_token'], $post['_method']);
            if ((isset($uplaod)) && ($uplaod['flag'] == 1) && (!empty($uplaod['url']))) {
                $post['meta_image'] = $uplaod['url'];
            }

            foreach ($post as $key => $value) {
                // Define the data to be updated or inserted
                $data = [
                    'key' => $key,
                    'workspace' => getActiveWorkSpace(),
                    'created_by' => creatorId(),
                ];

                // Check if the record exists, and update or insert accordingly
                Setting::updateOrInsert($data, ['value' => $value]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        // Settings Cache forget
        AdminSettingCacheForget();
        comapnySettingCacheForget();
        return redirect()->back()->with('success', __('SEO setting successfully updated.'));
    }
    public function storageStore(Request $request)
    {
        if (Auth::user()->isAbleTo('setting storage manage')) {
            $post = $request->all();
            unset($post['_token']);

            if ($request->storage_setting == 'wasabi') {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'wasabi_key' => 'required',
                        'wasabi_secret' => 'required',
                        'wasabi_region' => 'required',
                        'wasabi_bucket' => 'required',
                        'wasabi_url' => 'required',
                        'wasabi_root' => 'required',
                        'wasabi_max_upload_size' => 'required',
                        'wasabi_storage_validation' => 'required',
                    ]
                );
            } elseif ($request->storage_setting == 's3') {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        's3_key' => 'required',
                        's3_secret' => 'required',
                        's3_region' => 'required',
                        's3_bucket' => 'required',
                        's3_url' => 'required',
                        's3_endpoint' => 'required',
                        's3_max_upload_size' => 'required',
                        's3_storage_validation' => 'required',
                    ]
                );
            } else {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'local_storage_max_upload_size' => 'required',
                        'local_storage_validation' => 'required',
                    ]
                );
            }

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $post['s3_storage_validation'] = isset($request->s3_storage_validation) ? implode(",", $request->s3_storage_validation) : null;
            $post['wasabi_storage_validation'] = isset($request->wasabi_storage_validation) ? implode(",", $request->wasabi_storage_validation) : null;
            $post['local_storage_validation'] = isset($request->local_storage_validation) ? implode(",", $request->local_storage_validation) : null;

            foreach ($post as $key => $value) {
                // Define the data to be updated or inserted
                $data = [
                    'key' => $key,
                    'workspace' => getActiveWorkSpace(),
                    'created_by' => creatorId(),
                ];

                // Check if the record exists, and update or insert accordingly
                Setting::updateOrInsert($data, ['value' => $value]);
            }
            // Settings Cache forget
            AdminSettingCacheForget();
            comapnySettingCacheForget();
            return redirect()->back()->with('success', 'Storage Setting save sucessfully.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function aiKeySettingSave(Request $request)
    {
        if (Auth::user()->isAbleTo('api key setting create')) {

            $key_arr = $request->api_key;
            foreach ($key_arr as  $data) {

                if ($data != '' && !empty($data)) {
                    ApikeySetiings::updateOrCreate(
                        [
                            'key' => $data,
                            'created_by' => creatorId()
                        ]
                    );
                }
            }
            ApikeySetiings::whereNotIn('key', $key_arr)->delete();
            $post['chatgpt_model'] = $request->chatgpt_model;

            foreach ($post as $key => $value) {
                // Define the data to be updated or inserted
                $data = [
                    'key' => $key,
                    'workspace' => getActiveWorkSpace(),
                    'created_by' => creatorId(),
                ];

                // Check if the record exists, and update or insert accordingly
                Setting::updateOrInsert($data, ['value' => $value]);
            }
            // Settings Cache forget
            AdminSettingCacheForget();
            comapnySettingCacheForget();
            return redirect()->back()->with('success', __('Key Settings Save Successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function saveCurrencySettings(Request $request)
    {
        $post = $request->all();
        unset($post['_token']);
        unset($post['_method']);
        if (isset($post['defult_currancy'])) {
            $data = explode('-', $post['defult_currancy']);
            $post['defult_currancy_symbol'] = $data[0];
            $post['defult_currancy']        = $data[1];
        } else {
            $post['defult_currancy']        = 'USD';
            $post['defult_currancy_symbol'] = '$';
        }
        if (isset($post['site_currency_symbol_position'])) {
            $post['site_currency_symbol_position'] = !empty($request->site_currency_symbol_position) ? $request->site_currency_symbol_position : 'pre';
        }
        foreach ($post as $key => $value) {
            // Define the data to be updated or inserted
            $data = [
                'key' => $key,
                'workspace' => getActiveWorkSpace(),
                'created_by' => creatorId(),
            ];

            // Check if the record exists, and update or insert accordingly
            Setting::updateOrInsert($data, ['value' => $value]);
        }
        // Settings Cache forget
        AdminSettingCacheForget();
        comapnySettingCacheForget();
        return redirect()->back()->with('success', __('Currency Setting save successfully.'));
    }
    public function updateNoteValue(Request $request)
    {
        $symbol_position = 'pre';
        $symbol = '$';
        $format = '1';
        $price  = '10000';
        $number = explode('.', $price);
        $length = strlen(trim($number[0]));
        $currency_symbol = explode('-',$request->defult_currancy);

        if ($length > 3) {
            $decimal_separator  = isset($request->decimal_separator) && $request->decimal_separator === 'dot' ? ',' : ',';
            $thousand_separator = isset($request->thousand_separator) && $request->thousand_separator === 'dot' ? '.' : ',';
        } else {
            $decimal_separator  = isset($request->decimal_separator) == 'dot'  ? '.' : ',';
            $thousand_separator = isset($request->thousand_separator) == 'dot' ? '.' : ',';
        }

        if (isset($request->site_currency_symbol_position) && $request->site_currency_symbol_position == "post") {
            $symbol_position = 'post';
        }

        if (isset($request->defult_currancy)) {
            $symbol = $request->defult_currancy;
        }

        if (isset($request->currency_format)) {
            $format = $request->currency_format;
        }
        if (isset($request->currency_space)) {
            $currency_space = isset($request->currency_space) ? $request->currency_space : '';
        }
        if (isset($request->site_currency_symbol_name)) {
            $symbol = $request->site_currency_symbol_name == 'symbol' ? $currency_symbol[0] : $currency_symbol[1];
        }
        $formatted_price = (
            ($symbol_position == "pre")  ?  $symbol : '') . (isset($currency_space) && $currency_space == 'withspace' ? ' ' : '')
            . number_format($price, $format, $decimal_separator, $thousand_separator) . (isset($currency_space) && $currency_space == 'withspace' ? ' ' : '') .
            (($symbol_position == "post") ?  $symbol : '');
        return response()->json(['success' => true,'formatted_price' => $formatted_price]);
    }

}
