<?php

namespace App\Listeners;

use App\Models\ReferralSetting;
use App\Models\ReferralTransaction;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class ReferralTransactionLis
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        if($event->type == 'Subscription')
        {
            if($event->payment->user_id != '')
            {
                $objUser = User::find($event->payment->user_id);
            }
            else
            {
                $objUser = Auth::user();
            }

            $user = ReferralTransaction::where('company_id' , $objUser->id)->first();
            $referralSetting = ReferralSetting::where('created_by' , 1)->first();
            if($objUser->used_referral_code != 0 && $user == null && (isset($referralSetting) && $referralSetting->is_enable == 1))
            {
                $transaction                = new ReferralTransaction();
                $transaction->company_id    = $objUser->id;
                $transaction->plan_id       = $event->data->id;
                $transaction->plan_price    = $event->payment->price;
                $transaction->commission    = $referralSetting->percentage;
                $transaction->referral_code = $objUser->used_referral_code;
                $transaction->save();

                $commissionAmount  = ($event->payment->price * $referralSetting->percentage)/100;
                $user = User::where('referral_code' , $objUser->used_referral_code)->first();

                $user->commission_amount = $user->commission_amount + $commissionAmount;
                $user->save();
            }
        }
    }
}
