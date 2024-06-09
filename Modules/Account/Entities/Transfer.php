<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_account',
        'to_account',
        'amount',
        'date',
        'payment_method',
        'reference',
        'description',
        'workspace',
        'created_by',
    ];

    protected $table = 'bank_transfers';
    protected static function newFactory()
    {
        return \Modules\Account\Database\factories\TransferFactory::new();
    }
    public function fromBankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'from_account', 'id');
    }

    public function toBankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'to_account', 'id');
    }
    public static function bankAccountBalance($id, $amount, $type)
    {
        $bankAccount = BankAccount::find($id);
        if($bankAccount)
        {
            if($type == 'credit')
            {
                $oldBalance                   = $bankAccount->opening_balance;
                $bankAccount->opening_balance = $oldBalance + $amount;
                $bankAccount->save();
            }
            elseif($type == 'debit')
            {
                $oldBalance                   = $bankAccount->opening_balance;
                $bankAccount->opening_balance = $oldBalance - $amount;
                $bankAccount->save();
            }
        }

    }
   
}
