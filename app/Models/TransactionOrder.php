<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'req_amount',
        'req_user_id',
        'coupon_id'
    ];

    public static $status = [
        'Rejected',
        'In Progress',
        'Approved',
        'Paid',
    ];
}
