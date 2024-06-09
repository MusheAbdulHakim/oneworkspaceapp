<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BillPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_id',
        'date',
        'account_id',
        'payment_method',
        'reference',
        'description',
        'add_receipt',
    ];

    protected $appends = array('billno');

    protected static function newFactory()
    {
        return \Modules\Account\Database\factories\BillPaymentFactory::new();
    }
    public function bankAccount()
    {
        return $this->hasOne(BankAccount::class, 'id', 'account_id');
    }
    public function getBillnoAttribute()
    {
        return Bill::billNumberFormat($this->bill_id);
    }
}
