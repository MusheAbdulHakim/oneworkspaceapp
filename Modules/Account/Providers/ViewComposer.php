<?php

namespace Modules\Account\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\Entities\ChartOfAccountType;

class ViewComposer extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */


    public function boot(){
        view()->composer(['productservice::create', 'productservice::edit'], function ($view) {
            if(\Auth::check() && \Auth::user()->type != 'super admin')
            {
                $active_module =  ActivatedModule();
                $dependency = explode(',','Account');

                $incomeChartAccounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name, chart_of_accounts.id as id'))
                    ->leftjoin('chart_of_account_types', 'chart_of_account_types.id','chart_of_accounts.type')
                    ->where('chart_of_account_types.name' ,'Income')
                    ->where('parent', '=', 0)
                    ->where('chart_of_accounts.created_by', creatorId())
                    ->where('chart_of_accounts.workspace', getActiveWorkSpace())
                    ->get()
                    ->pluck('code_name', 'id');
                $incomeChartAccounts->prepend('Select Account', 0);

                $incomeSubAccounts = ChartOfAccount::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name' , 'chart_of_account_parents.account');
                $incomeSubAccounts->leftjoin('chart_of_account_parents', 'chart_of_accounts.parent', 'chart_of_account_parents.id');
                $incomeSubAccounts->leftjoin('chart_of_account_types', 'chart_of_account_types.id','chart_of_accounts.type');
                $incomeSubAccounts->where('chart_of_account_types.name' ,'Income');
                $incomeSubAccounts->where('chart_of_accounts.parent', '!=', 0);
                $incomeSubAccounts->where('chart_of_accounts.created_by', creatorId());
                $incomeSubAccounts->where('chart_of_accounts.workspace', getActiveWorkSpace());
                $incomeSubAccounts = $incomeSubAccounts->get()->toArray();


                $expenseChartAccounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name, chart_of_accounts.id as id'))
                    ->leftjoin('chart_of_account_types', 'chart_of_account_types.id','chart_of_accounts.type')
                    ->whereIn('chart_of_account_types.name' ,['Expenses','Costs of Goods Sold'])
                    ->where('chart_of_accounts.workspace', getActiveWorkSpace())
                    ->where('chart_of_accounts.created_by', creatorId())->get()
                    ->pluck('code_name', 'id');
                $expenseChartAccounts->prepend('Select Account', '');

                $expenseSubAccounts = ChartOfAccount::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name' , 'chart_of_account_parents.account');
                $expenseSubAccounts->leftjoin('chart_of_account_parents', 'chart_of_accounts.parent', 'chart_of_account_parents.id');
                $expenseSubAccounts->leftjoin('chart_of_account_types', 'chart_of_account_types.id','chart_of_accounts.type');
                $expenseSubAccounts->whereIn('chart_of_account_types.name' ,['Expenses','Costs of Goods Sold']);
                $expenseSubAccounts->where('chart_of_accounts.parent', '!=', 0);
                $expenseSubAccounts->where('chart_of_accounts.workspace', getActiveWorkSpace());
                $expenseSubAccounts->where('chart_of_accounts.created_by', creatorId());
                $expenseSubAccounts = $expenseSubAccounts->get()->toArray();

                if(!empty(array_intersect($dependency,$active_module)))
                {
                    $view->getFactory()->startPush('add_column_in_productservice', view('account::setting.add_column_table',compact('incomeChartAccounts','expenseChartAccounts','incomeSubAccounts','expenseSubAccounts')));

                }


            }
        });
        view()->composer(['invoice.create','invoice.edit','invoice.index','invoice.grid'], function ($view)
        {
            if (Auth::check() && module_is_active('Account'))
            {
                $view->getFactory()->startPush('account_type', view('account::invoice.account_type'));
            }

        });
        view()->composer(['reminder::reminder.create' ,'reminder::reminder.edit'], function ($view)
        {

            if (Auth::check() && module_is_active('Account'))
            {
                $view->getFactory()->startPush('module_name', view('account::bill.module_name'));
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
