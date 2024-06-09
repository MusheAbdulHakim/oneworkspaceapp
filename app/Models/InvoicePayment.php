<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;

class InvoicePayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_id',
        'date',
        'amount',
        'account_id',
        'payment_method',
        'order_id',
        'currency',
        'txn_id',
        'payment_type',
        'receipt',
        'add_receipt',
        'reference',
        'description',
    ];


    protected $appends = array('invoiceno');

    public function bankAccount()
    {
        return $this->hasOne(\Modules\Account\Entities\BankAccount::class, 'id', 'account_id');
    }

    public function getInvoicenoAttribute()
    {
        return Invoice::invoiceNumberFormat($this->invoice_id);
    }
}
