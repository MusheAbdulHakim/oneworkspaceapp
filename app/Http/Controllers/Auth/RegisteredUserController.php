<?php

namespace App\Http\Controllers\Auth;

use App\Events\DefaultData;
use App\Events\GivePermissionToRole;
use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Models\Plan;
use App\Models\Role;
use App\Models\User;
use App\Models\WorkSpace;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Modules\GoogleCaptcha\Events\VerifyReCaptchaToken;
class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public $admin_settings;

    public function setting(){
        $this->admin_settings = getAdminAllSetting();

    }
    public function __construct()
    {
        $this->setting();

        if(!file_exists(storage_path() . "/installed"))
        {
            header('location:install');
            die;
        }
        if(module_is_active('GoogleCaptcha') && (isset($this->admin_settings['google_recaptcha_is_on']) ? $this->admin_settings['google_recaptcha_is_on'] : 'off') == 'on' )
        {
            config(['captcha.secret' => isset($this->admin_settings['google_recaptcha_secret']) ? $this->admin_settings['google_recaptcha_secret'] : '']);
            config(['captcha.sitekey' => isset($this->admin_settings['google_recaptcha_key']) ? $this->admin_settings['google_recaptcha_key'] : '']);
        }
        // $this->middleware('guest')->except('logout');
    }
    public function create(Request $request,$lang = '')
    {
        if (empty( $this->admin_settings['signup']) ||  (isset($this->admin_settings['signup']) ? $this->admin_settings['signup'] : 'off') == "on")
        {
            if($lang == '')
            {
                $lang = getActiveLanguage();
            }
            else
            {
                $lang = array_key_exists($lang, languages()) ? $lang : 'en';
            }
            \App::setLocale($lang);

            $ref = $request->ref_id ?? 0 ;
            $refCode = User::where('referral_code' , '=', $ref)->first();
            if(isset($refCode) && $refCode->referral_code != $ref)
            {
                return redirect()->route('register');
            }

            return view('auth.register',compact('lang','ref'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'workspace' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password_confirmation' => 'required'

        ]);
        if (module_is_active('GoogleCaptcha') && admin_setting('google_recaptcha_is_on') == 'on') {
            if (admin_setting('google_recaptcha_version') == 'v2-checkbox') {
                $request->validate([
                    'g-recaptcha-response' => 'required|captcha',
                ]);
            } else {
                $result = event(new VerifyReCaptchaToken($request));
                if (!isset($result[0]['status']) || $result[0]['status'] != true) {
                    $key = 'g-recaptcha-response';
                    $request->merge([$key => null]); // Set the key to null
                    $request->validate([
                        'g-recaptcha-response' => 'required|captcha',
                    ]);
                }
            }
        }

        do {
            $code = rand(100000, 999999);
        } while (User::where('referral_code', $code)->exists());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'referral_code' => $code,
            'used_referral_code'=> !empty($request->ref_code)?$request->ref_code:'0',
            'password' => Hash::make($request->password),
        ]);
        Auth::login($user);

        $role_r = Role::where('name','company')->first();
        if(!empty($user))
        {
            $user->addRole($role_r);
            // WorkSpace slug create on WorkSpace Model
            $workspace = new WorkSpace();
            $workspace->name = $request->workspace;
            $workspace->created_by = $user->id;
            $workspace->save();

            $user_work = User::find($user->id);
            $user_work->active_workspace = $workspace->id;
            $user_work->workspace_id = $workspace->id;
            $user_work->save();

            User::CompanySetting($user->id);

            $user->MakeRole();


            if(!empty($request->type) && $request->type != "pricing")
            {
                $plan = Plan::where('is_free_plan',1)->first();
                if($plan)
                {
                    $user->assignPlan($plan->id,'Month',$plan->modules,0,$user->id);
                }
            }

            if ( admin_setting('email_verification') == 'on')
            {
                try
                {
                    $uArr = [
                        'email'=> $request->email,
                        'password'=> $request->password,
                        'company_name'=>$request->name,
                    ];

                    $admin_user = User::where('type','super admin')->first();
                    SetConfigEmail(!empty($admin_user->id) ? $admin_user->id : null);
                    $resp = EmailTemplate::sendEmailTemplate('New User', [$user->email], $uArr,$admin_user->id);
                    event(new Registered($user));
                }
                catch(\Exception $e)
                {
                    $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
                }
            }
            else
            {
                $user_work = User::find($user->id);
                $user_work->email_verified_at = date('Y-m-d h:i:s');
                $user_work->save();
            }

        }

        return redirect('plans');
        // return redirect(RouteServiceProvider::HOME);
    }
}
