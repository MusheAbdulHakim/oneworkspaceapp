<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Plan extends Model
{
    use HasFactory;
    protected $fillable = [
        'modules',
        'name',
        'trial',
        'trial_days',
        'number_of_workspace',
        'number_of_user',
        'custom_plan',
        'active',
        'is_free_plan',
        'package_price_monthly',
        'package_price_yearly',
        'price_per_user_monthly',
        'price_per_user_yearly',
        'price_per_workspace_monthly',
        'price_per_workspace_yearly',

    ];

    public static $arrDuration = [
        'Unlimited' => 'Unlimited',
        'month' => 'Per Month',
        'Year' => 'Per Year',
    ];
    public static $plan_type = [
        0 => 'Paid',
        1 => 'Free',
    ];
    public function fields()
    {
        return $this->hasOne('App\Models\PlanField', 'plan_id','id');
    }
    public static function total_plan()
    {
        return Plan::count();
    }
    public static function most_purchese_plan()
    {
        $free_plan = Plan::select('id')->where('price', '<=', 0)->get()->toArray();

        return User:: select(DB::raw('count(*) as total'))->where('type', '=', 'company')->whereNotIn('active_plan',$free_plan)->groupBy('active_plan')->first();
    }
}
