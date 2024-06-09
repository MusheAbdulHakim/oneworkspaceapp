<?php

namespace Modules\Account\Providers;

use Modules\Retainer\Entities\Retainer;
use App\Models\Setting;
use Illuminate\Support\ServiceProvider;
use Modules\Account\Entities\BankAccount;

class RetainerPayment extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */

    public function boot(){

        view()->composer(['retainer::retainer.retainerpay'], function ($view)
        {
            $route = \Request::route()->getName();
            if($route == "pay.retainer")
            {
                try {
                    $ids = \Request::segment(3);
                    if(!empty($ids))
                    {
                        try {
                            $id = \Illuminate\Support\Facades\Crypt::decrypt($ids);
                            $invoice = Retainer::where('id',$id)->first();
                            $account = Setting::where('key', 'bank_account')->first();
                            $bank_accounts = [];
                            $bankaccountId=0;
                            if ($account) {
                                $account_ids = explode(',', $account->value);
                                $bank_accounts = BankAccount::whereIn('id', $account_ids)
                                    ->get()
                                    ->pluck('holder_name', 'id');

                            }
                            $type = 'retainer';
                            if (module_is_active('Account', $invoice->created_by) && (company_setting('bank_account_payment_is_on', $invoice->created_by, $invoice->workspace)  == 'on')) {

                                $view->getFactory()->startPush('retainer_payment_tab', view('account::payment.sidebar'));
                                $view->getFactory()->startPush('retainer_payment_div', view('account::payment.nav_containt_div', compact('type', 'invoice','bank_accounts','bankaccountId')));
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
