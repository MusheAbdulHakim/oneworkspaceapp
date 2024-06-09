<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'name',
        'email',
        'card_number',
        'card_exp_month',
        'card_exp_year',
        'plan_name',
        'plan_id',
        'price',
        'price_currency',
        'txn_id',
        'payment_status',
        'payment_type',
        'receipt',
        'user_id',
    ];

    public static function total_orders()
    {
        return Order::count();
    }

    public static function total_orders_price()
    {
        return Order::sum('price');
    }

    public function total_coupon_used()
    {
        return $this->hasOne(UserCoupon::class, 'order', 'order_id');
    }
}
