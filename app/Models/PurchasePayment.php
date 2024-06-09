<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'date',
        'account_id',
        'payment_method',
        'reference',
        'description',
        'workspace',
    ];

    public function bankAccount()
    {
        return $this->hasOne(\Modules\Account\Entities\BankAccount::class, 'id', 'account_id');
    }
}
