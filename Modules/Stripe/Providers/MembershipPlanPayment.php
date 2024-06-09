<?php

namespace Modules\Stripe\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Modules\GymManagement\Entities\AssignMembershipPlan;

class MembershipPlanPayment extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot(){

        view()->composer(['gymmanagement::member.memberplanpay'], function ($view)
        {
            $route = \Request::route()->getName();
            if($route == "pay.membership.plan")
            {
                try {
                    $ids = \Request::segment(3);
                    if(!empty($ids))
                    {
                        try {
                            $id = \Illuminate\Support\Facades\Crypt::decrypt($ids);
                            $user = User::where('id',$id)->first();
                            $company_settings = getCompanyAllSetting( $user->created_by,$user->workspace);
                            $assignmembershipplan = AssignMembershipPlan::where('user_id',$user->id)->first();
                            if(module_is_active('Stripe', $user->created_by) && ((isset($company_settings['stripe_is_on'])? $company_settings['stripe_is_on'] : 'off')  == 'on') && (isset($company_settings['stripe_key'])) && (isset($company_settings['stripe_secret'])))
                            {
                                $view->getFactory()->startPush('memberplan_payment_tab', view('stripe::payment.sidebar'));
                                $view->getFactory()->startPush('memberplan_payment_div', view('stripe::payment.member_plan_payment',compact('user','company_settings','assignmembershipplan')));
                            }
                        } catch (\Throwable $th)
                        {

                        }
                    }
                } catch (\Throwable $th) {

                }
            }
        });
    }

    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
