<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'name',
        'code',
        'discount',
        'limit',
        'description',
    ];

    public static $couponType = [
        'percentage' => 'Percentage',
        'flat' => 'Flat',
        'fixed' => 'Fixed Plan Wise'
    ];


    public function used_coupon()
    {
        return $this->hasMany('App\Models\UserCoupon', 'coupon', 'id')->count();
    }

}
