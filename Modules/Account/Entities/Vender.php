<?php

namespace Modules\Account\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vender extends Model
{
    use HasFactory;
    protected $table = 'vendors';

    protected $fillable = [
        'id',
        '   ',
        'user_id',
        'name',
        'email',
        'contact',
        'tax_number',
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
        return \Modules\Account\Database\factories\VenderFactory::new();
    }
    public static function vendorNumberFormat($number)
    {
        $company_settings = getCompanyAllSetting();
        $data = !empty($company_settings['vendor_prefix']) ? $company_settings['vendor_prefix'] : '#VEND0000';

        return $data . sprintf("%05d", $number);
    }
    public function vendorTotalBillSum($vendorId)
    {
        $bills = Bill::where('vendor_id', $vendorId)->get();
        $total = 0;
        foreach ($bills as $bill) {
            $total += $bill->getTotal();
        }
        return $total;
    }
    public function vendorTotalBill($vendorId)
    {
        $bills = Bill::where('vendor_id', $vendorId)->count();

        return $bills;
    }
    public function vendorOverdue($vendorId)
    {
        $dueBill = Bill::where('vendor_id', $vendorId)->whereNotIn(
            'status',
            [
                '0',
                '4',
            ]
        )->where('due_date', '<', date('Y-m-d'))->get();
        $due     = 0;
        foreach ($dueBill as $bill) {
            $due += $bill->getDue();
        }

        return $due;
    }
    public function vendorBill($vendorId)
    {
        $bills = Bill::where('vendor_id', $vendorId)->orderBy('bill_date', 'desc')->get();
        return $bills;
    }
    public function vendorPayment($vendorId)
    {
        $payment = Payment::where('vendor_id', $vendorId)->orderBy('date', 'desc')->get();
        return $payment;
    }
    public function user()
    {
        return  $this->hasOne(User::class,'id','user_id');
    }
}
