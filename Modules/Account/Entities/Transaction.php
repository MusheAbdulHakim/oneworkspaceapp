<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'account',
        'type',
        'amount',
        'description',
        'date',
        'customer_id',
        'vendor_name',
        'payment_id',
        'workspace',
        'created_by',
    ];


    protected static function newFactory()
    {
        return \Modules\Account\Database\factories\TransactionFactory::new();
    }
    public function bankAccount()
    {
        return $this->hasOne(BankAccount::class, 'id', 'account')->first();
    }
    public static function addTransaction($request)
    {
        $transaction              = new Transaction();
        $transaction->account     = $request->account;
        $transaction->user_id     = $request->user_id;
        $transaction->vendor_name = $request->vendor_name;
        $transaction->user_type   = $request->user_type;
        $transaction->type        = $request->type;
        $transaction->amount      = $request->amount;
        $transaction->description = $request->description;
        $transaction->date        = $request->date;
        $transaction->created_by  = $request->created_by;
        $transaction->payment_id  = $request->payment_id;
        $transaction->category    = $request->category;
        $transaction->workspace   = getActiveWorkSpace();
        $transaction->save();
    }
    public static function editTransaction($request)
    {
        $transaction              = Transaction::where('payment_id', $request->payment_id)->where('type', $request->type)->first();
        $transaction->account     = $request->account;
        $transaction->amount      = $request->amount;
        $transaction->description = $request->description;
        $transaction->date        = $request->date;
        $transaction->category    = $request->category;
        $transaction->save();
    }
    public static function destroyTransaction($id, $type, $user)
    {
        Transaction::where('payment_id', $id)->where('type', $type)->where('user_type', $user)->delete();
    }
}
