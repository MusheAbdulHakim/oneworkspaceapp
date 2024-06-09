<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\PlanField;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Plans extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plan = Plan::where('custom_plan',1)->first();
        if(empty($plan))
        {
            $new_pan = new Plan();
            $new_pan->package_price_monthly = 0;
            $new_pan->package_price_yearly = 0;
            $new_pan->price_per_user_monthly = 0;
            $new_pan->price_per_user_yearly = 0;
            $new_pan->number_of_user = 5;
            $new_pan->number_of_workspace = 5;
            $new_pan->custom_plan = 1;
            $new_pan->save();
        }
        $plans = Plan::where('is_free_plan',1)->first();
        if(empty($plans))
        {
            $new_pan = new Plan();
            $new_pan->name = "Basic";
            $new_pan->package_price_monthly = 0;
            $new_pan->package_price_yearly = 0;
            $new_pan->price_per_user_monthly = 0;
            $new_pan->price_per_user_yearly = 0;
            $new_pan->modules = "Account,Hrm,Stripe,ProductService,Pos,Taskly,Paypal,Lead";
            $new_pan->number_of_user = 5;
            $new_pan->number_of_workspace = 5;
            $new_pan->is_free_plan = 1;
            $new_pan->custom_plan = 0;
            $new_pan->save();
        }
    }
}
