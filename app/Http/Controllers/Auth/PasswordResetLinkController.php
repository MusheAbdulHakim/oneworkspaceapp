<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Modules\GoogleCaptcha\Events\VerifyReCaptchaToken;
class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function __construct()
    {
        if(!file_exists(storage_path() . "/installed"))
        {
            header('location:install');
            die;
        }
    }
    public function create($lang = ''): View
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
        $admin_settings = getAdminAllSetting();
        if(module_is_active('GoogleCaptcha') && (isset($admin_settings['google_recaptcha_is_on']) ? $admin_settings['google_recaptcha_is_on'] : 'off') == 'on' )
        {
            config(['captcha.secret' => isset($admin_settings['google_recaptcha_secret']) ? $admin_settings['google_recaptcha_secret'] : '']);
            config(['captcha.sitekey' => isset($admin_settings['google_recaptcha_key']) ? $admin_settings['google_recaptcha_key'] : '']);
        }

        return view('auth.forgot-password',compact('lang'));
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
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

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.

        try {

            $admin_user = User::where('type','super admin')->first();
            SetConfigEmail(!empty($admin_user->id) ? $admin_user->id : null);

            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status == Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))
                    ->withErrors(['email' => __($status)]);
            //code...
        } catch (\Exception $e) {
            //throw $th;
            return redirect()->back()->withErrors(['email' => __('Email SMTP settings does not configured so please contact to your site admin.')]);
        }
    }
}
