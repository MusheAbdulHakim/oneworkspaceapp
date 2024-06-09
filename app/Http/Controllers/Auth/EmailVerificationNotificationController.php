<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function __construct() {
        $user = User::where('type','super admin')->first();
        SetConfigEmail(!empty($user->id) ? $user->id : null);
    }
    public function store(Request $request)
    {
        try {
            if ($request->user()->hasVerifiedEmail()) {
                return redirect()->intended(RouteServiceProvider::HOME);
            }
            $request->user()->sendEmailVerificationNotification();
        } catch (\Throwable $th) {
            return back()->with('status', 'verification-link-not-sent');
        }
        return back()->with('status', 'verification-link-sent');
    }
}
