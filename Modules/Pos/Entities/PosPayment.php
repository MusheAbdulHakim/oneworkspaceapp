<?php

namespace Modules\Pos\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PosPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pos_id',
        'date',
        'amount',
        'discount',
        'discount_amount',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\Pos\Database\factories\PosPaymentFactory::new();
    }
    public function bankAccount()
    {
        return $this->hasOne(\Modules\Pos\Entities\BankAccount::class, 'id', 'account_id');
    }
}
