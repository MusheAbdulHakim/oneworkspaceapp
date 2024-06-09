<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request,$lang = null): RedirectResponse|View
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

        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(RouteServiceProvider::HOME)
                    : view('auth.verify-email',compact('lang'));
    }
}
