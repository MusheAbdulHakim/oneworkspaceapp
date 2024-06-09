<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use App\Models\EmailTemplate;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('settings.index');
    }
    public function getSettingSection($module, $method = 'index')
    {
        $folder = 'Company';
        if (auth()->user()->type == 'super admin') {
            $settings = getAdminAllSetting();

            $folder = 'SuperAdmin';
        } else {
            $settings = getCompanyAllSetting();
        }

        if (!empty($module) && $module != 'Base') {
            $controllerClass = "Modules\\" . $module . "\\Http\\Controllers\\" . $folder . "\\SettingsController";

            if (class_exists($controllerClass)) {
                $controller = \App::make($controllerClass);
                if (method_exists($controller, $method)) {
                    $output =  $controller->{$method}($settings);
                    $return = [
                        'status' => 200,
                        'html' => $output->toHtml(),
                    ];
                    return  response()->json($return);
                }
            }
        } else {
            $method = 'index';
            $html = '';
            $controllerClass = "App\\Http\\Controllers\\" . $folder . "\\SettingsController";

            if (class_exists($controllerClass)) {
                $controller = \App::make($controllerClass);
                if (method_exists($controller, $method)) {
                    $output =  $controller->{$method}($settings);
                    if ($output !== null) {
                        $html .= $output->toHtml();
                    }
                }
            }

            $method = 'emailSettingGet';
            $controllerClass = "App\\Http\\Controllers\\SettingsController";

            if (class_exists($controllerClass)) {
                $controller = \App::make($controllerClass);
                if (method_exists($controller, $method)) {
                    $output =  $controller->{$method}($settings);
                    if ($output !== null) {
                        $html .= $output->toHtml();
                    }
                }
            }

            $method = 'settingGet';
            $controllerClass = "App\\Http\\Controllers\\BanktransferController";

            if (class_exists($controllerClass)) {
                $controller = \App::make($controllerClass);
                if (method_exists($controller, $method)) {
                    $output =  $controller->{$method}($settings);
                    if ($output !== null) {
                        $html .= $output->toHtml();
                    }
                }
            }

            $return = [
                'status' => 200,
                'html' => $html,
            ];
            return response()->json($return);
        }
    }


    public function emailSettingGet($settings)
    {
        $activatedModules = ActivatedModule();
        $email_notification_modules = Notification::where('type','mail')->whereIn('module', $activatedModules)->orwhere('module','General')->pluck('module')->toArray();

        $email_notification_modules = array_unique($email_notification_modules);

        $email_notify = Notification::where('type', 'mail')->whereIn('module', $email_notification_modules)->get(['module', 'action', 'permissions']);
        $email_setting = EmailTemplate::$email_settings;
        return view('email.index', compact('settings', 'email_notification_modules', 'email_notify','email_setting'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        return redirect()->back();
    }

    public function getfields(Request $request)
    {
        if (auth()->user()->type == 'super admin') {
            $settings = getAdminAllSetting();

            $folder = 'SuperAdmin';
        } else {
            $settings = getCompanyAllSetting();
        }
       $email_setting = $request->emailsetting;

       $returnHTML = view('email.input', compact('email_setting','settings'))->render();
       $response = [
           'is_success' => true,
           'message' => '',
           'html' => $returnHTML,
       ];

       return response()->json($response);
    }
    public function mailStore(Request $request)
    {

        if (Auth::user()->isAbleTo('setting manage')) {

                $validator = \Validator::make(
                    $request->all(),
                    [
                        'mail_driver' => 'required|string|max:255',
                        'mail_host' => 'required|string|max:255',
                        'mail_port' => 'required|string|max:255',
                        'mail_username' => 'required|string|max:255',
                        'mail_password' => 'required|string|max:255',
                        'mail_encryption' => 'required|string|max:255',
                        'mail_from_address' => 'required|string|max:255',
                        'mail_from_name' => 'required|string|max:255',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }

                $post = [
                    'email_setting' => $request->email_setting,
                    'mail_driver' => $request->mail_driver,
                    'mail_host' => $request->mail_host,
                    'mail_port' => $request->mail_port,
                    'mail_username' => $request->mail_username,
                    'mail_password' => $request->mail_password,
                    'mail_encryption' => $request->mail_encryption,
                    'mail_from_address' => $request->mail_from_address,
                    'mail_from_name' => $request->mail_from_name,

                ];

            unset($post['_token'], $post['_method'], $post['mail_noti']);
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
            return redirect()->back()->with('success', 'Mail Setting save sucessfully.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function testMail(Request $request)
    {
        $data                    = [];
        $data['mail_driver']     = $request->mail_driver;
        $data['mail_host']       = $request->mail_host;
        $data['mail_port']       = $request->mail_port;
        $data['mail_username']   = $request->mail_username;
        $data['mail_password']   = $request->mail_password;
        $data['mail_from_address']   = $request->mail_from_address;
        $data['mail_encryption'] = $request->mail_encryption;
        $data['route'] = route('test.mail.send');
        return view('settings.test_mail', compact('data'));
    }

    public function sendTestMail(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                               'email' => 'required|email',
                               'mail_driver' => 'required',
                               'mail_host' => 'required',
                               'mail_port' => 'required',
                               'mail_username' => 'required',
                               'mail_password' => 'required',
                               'mail_from_address' => 'required',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return error_res($messages->first());
        }
        try
        {
            config(
                [
                    'mail.driver' => $request->mail_driver,
                    'mail.host' => $request->mail_host,
                    'mail.port' => $request->mail_port,
                    'mail.encryption' => $request->mail_encryption,
                    'mail.username' => $request->mail_username,
                    'mail.password' => $request->mail_password,
                    'mail.from.address' => $request->mail_from_address,
                    'mail.from.name' => config('name'),
                ]
            );

             Mail::to($request->email)->send(new TestMail());

            return success_res(__('Email send Successfully'));

        }
        catch(\Exception $e)
        {
            return error_res($e->getMessage());
        }
    }

    public function mailNotificationStore(Request $request)
    {
        // mail notification save
        if ($request->has('mail_noti')) {
            foreach ($request->mail_noti as $key => $notification) {
                // Define the data to be updated or inserted
                $data = [
                    'key' => $key,
                    'workspace' => getActiveWorkSpace(),
                    'created_by' => creatorId(),
                ];

                // Check if the record exists, and update or insert accordingly
                Setting::updateOrInsert($data, ['value' => $notification]);
            }
        }
        // Settings Cache forget
        AdminSettingCacheForget();
        comapnySettingCacheForget();
        return redirect()->back()->with('success', 'Mail Notification Setting save sucessfully.');
    }
}
