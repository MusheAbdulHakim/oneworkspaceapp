<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransferPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'user_id',
        'request',
        'status',
        'type',
        'price',
        'price_currency',
        'attachment',
        'created_by',
        'workspace',
    ];

    public function User()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
