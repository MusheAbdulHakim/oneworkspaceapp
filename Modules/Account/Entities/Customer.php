<?php

namespace Modules\Account\Entities;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'user_id',
        'name',
        'email',
        'tax_number',
        'password',
        'contact',
        'billing_name',
        'billing_country',
        'billing_state',
        'billing_city',
        'billing_phone',
        'billing_zip',
        'billing_address',
        'shipping_name',
        'shipping_country',
        'shipping_state',
        'shipping_city',
        'shipping_phone',
        'shipping_zip',
        'shipping_address',
        'lang',
        'balance',
        'workspace',
        'created_by',
        'remember_token'
    ];

    protected static function newFactory()
    {
        return \Modules\Account\Database\factories\CustomerFactory::new();
    }
    public function user()
    {
        return  $this->hasOne(User::class,'id','user_id');
    }
    public static function customerNumberFormat($number)
    {
        $company_settings = getCompanyAllSetting();
        $data = !empty($company_settings['customer_prefix']) ? $company_settings['customer_prefix'] : '#CUST0000';

        return $data . sprintf("%05d", $number);
    }
    public function customerTotalInvoiceSum($customerId)
    {
        $invoices = Invoice:: where('customer_id', $customerId)->get();
        $total    = 0;
        foreach($invoices as $invoice)
        {
            $total += $invoice->getTotal();
        }
        return $total;
    }
    public function customerTotalInvoice($customerId)
    {
        $invoices = Invoice:: where('customer_id', $customerId)->count();

        return $invoices;
    }
    public function customerOverdue($customerId)
    {
        $dueInvoices = Invoice:: where('customer_id', $customerId)->whereNotIn(
            'status', [
                        '0',
                        '4',
                    ]
        )->where('due_date', '<', date('Y-m-d'))->get();
        $due         = 0;
        foreach($dueInvoices as $invoice)
        {
            $due += $invoice->getDue();
        }

        return $due;
    }
    public function customerProposal($customerId)
    {
        $proposals = \App\Models\Proposal:: where('customer_id', $customerId)->orderBy('issue_date', 'desc')->get();
        return $proposals;
    }
    public function customerRevenue($customerId)
    {
        $revenue = Revenue:: where('customer_id', $customerId)->orderBy('date', 'desc')->get();
        return $revenue;
    }
    public function customerInvoice($customerId)
    {
        $invoices  = \App\Models\Invoice:: where('customer_id', $customerId)->orderBy('issue_date', 'desc')->get();

        return $invoices;
    }
}

