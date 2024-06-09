<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Revenue extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'amount',
        'account_id',
        'customer_id',
        'user_id',
        'category_id',
        'payment_method',
        'reference',
        'description',
        'workspace',
        'created_by'
    ];

    protected static function newFactory()
    {
        return \Modules\Account\Database\factories\RevenueFactory::new();
    }

}
