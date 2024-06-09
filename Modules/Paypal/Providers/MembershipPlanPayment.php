<?php

namespace Modules\Paypal\Providers;

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
                            if(module_is_active('Paypal', $user->created_by) && ($company_settings['paypal_payment_is_on']  == 'on') && ($company_settings['company_paypal_client_id']) && ($company_settings['company_paypal_secret_key']))
                        {
                                $view->getFactory()->startPush('memberplan_payment_tab', view('paypal::payment.sidebar'));
                                $view->getFactory()->startPush('memberplan_payment_div', view('paypal::payment.member_plan_payment',compact('user','company_settings','assignmembershipplan')));
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
