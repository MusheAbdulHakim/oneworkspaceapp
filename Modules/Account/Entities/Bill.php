<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;


class Bill extends Model
{
    use HasFactory;
    public $billTotal = null;
    
    protected $fillable = [
        'bill_id',
        'vendor_id',
        'user_id',
        'bill_date',
        'due_date',
        'order_number',
        'status',
        'bill_shipping_display',
        'send_date',
        'discount_apply',
        'bill_module',
        'category_id',
        'workspace',
        'created_by'
    ];

    protected static function newFactory()
    {
        return \Modules\Account\Database\factories\BillFactory::new();
    }
    public static $statues = [
        'Draft',
        'Sent',
        'Unpaid',
        'Partialy Paid',
        'Paid',
    ];

    public function category()
    {
        if(module_is_active('ProductService'))
        {
            return $this->hasOne(\Modules\ProductService\Entities\Category::class, 'id', 'category_id');
        }
    }
    public function vendor()
    {
        return $this->hasOne(Vender::class, 'id', 'vendor_id');
    }
    public function items()
    {
        return $this->hasMany(BillProduct::class, 'bill_id', 'id');
    }
    public function accounts()
    {
        return $this->hasMany(BillAccount::class, 'ref_id', 'id');
    }
    public function debitNote()
    {
        return $this->hasMany(DebitNote::class, 'bill', 'id');
    }
    public function customdebitNote()
    {
        if(module_is_active('Account'))
        {

            return $this->hasMany(\Modules\Account\Entities\CustomerDebitNotes::class, 'bill', 'id');
        }
        else
        {
            return [];
        }
    }
    public function billTotalDebitNote()
    {
        if(is_null($this->billTotal)){
            $this->billTotal =  $this->hasMany(DebitNote::class, 'bill', 'id')->sum('amount');
            return $this->billTotal;
        }
        else{
            return $this->billTotal;
        }
       
    }

    public function billTotalCustomerDebitNote()
    {
        if(module_is_active('Account'))
        {
            return $this->hasMany(\Modules\Account\Entities\CustomerDebitNotes::class, 'bill', 'id')->sum('amount');
        }
        else
        {
            return 0;
        }
    }
    public function payments()
    {
        return $this->hasMany(BillPayment::class, 'bill_id', 'id');
    }
    public function getSubTotal()
    {
        $subTotal = 0;
        foreach ($this->items as $product) {
            $subTotal += ($product->price * $product->quantity);
        }

        return $subTotal;
    }
    public function getTotalDiscount()
    {
        $totalDiscount = 0;
        foreach ($this->items as $product) {
            $totalDiscount += $product->discount;
        }

        return $totalDiscount;
    }

    public function getAccountTotal()
    {
        $accountTotal = 0;
        foreach ($this->accounts as $account)
        {
            $accountTotal += $account->price;
        }

        return $accountTotal;
    }

    public function getTotal()
    {
        return ($this->getSubTotal() + $this->getTotalTax()) - $this->getTotalDiscount();
    }
    public function getTotalTax()
    {
        $totalTax = 0;
        foreach ($this->items as $product) {
            $taxes = AccountUtility::totalTaxRate($product->tax);
            $totalTax += ($taxes / 100) * ($product->price * $product->quantity - $product->discount) ;
        }

        return $totalTax;
    }
    public function getDue()
    {
        $due = 0;
        foreach ($this->payments as $payment) {
            $due += $payment->amount;
        }
        return ($this->getTotal() - $due) - ($this->billTotalDebitNote());
    }
    public static function billNumberFormat($number,$company_id = null,$workspace_id = null)
    {

        if(!empty($company_id) && empty($workspace))
        {
            $company_settings = getCompanyAllSetting($company_id);
        }
        elseif(!empty($company_id) && !empty($workspace))
        {
            $company_settings = getCompanyAllSetting($company_id,$workspace);
        }
        else
        {
            $company_settings = getCompanyAllSetting();
        }
        $data = !empty($company_settings['bill_prefix']) ? $company_settings['bill_prefix'] : '#BILL0';

        return $data. sprintf("%05d", $number);
    }

     //add quantity in product stock
     public static function addProductStock($product_id, $quantity, $type, $description,$type_id)
     {
        $stocks                = new StockReport();
        $stocks->product_id    = $product_id;
        $stocks->quantity	   = $quantity;
        $stocks->type          = $type;
        $stocks->type_id       = $type_id;
        $stocks->description   = $description;
        $stocks->workspace     = getActiveWorkSpace();
        $stocks->created_by    = \Auth::user()->id;
        $stocks->save();
     }
     public static function weeklyBill()
     {
         $staticstart = date('Y-m-d', strtotime('last Week'));
         $currentDate = date('Y-m-d');
         $bills       = Bill:: select('*')->where('workspace', getActiveWorkSpace())->where('bill_date', '>=', $staticstart)->where('bill_date', '<=', $currentDate)->get();
         $billTotal   = 0;
         $billPaid    = 0;
         $billDue     = 0;
         foreach($bills as $bill)
         {
             $billTotal += $bill->getTotal();
             $billPaid  += ($bill->getTotal() - $bill->getDue());
             $billDue   += $bill->getDue();
         }

         $billDetail['billTotal'] = $billTotal;
         $billDetail['billPaid']  = $billPaid;
         $billDetail['billDue']   = $billDue;

         return $billDetail;
     }

     public static function monthlyBill()
     {
         $staticstart = date('Y-m-d', strtotime('last Month'));
         $currentDate = date('Y-m-d');
         $bills       = Bill:: select('*')->where('workspace', getActiveWorkSpace())->where('bill_date', '>=', $staticstart)->where('bill_date', '<=', $currentDate)->get();
         $billTotal   = 0;
         $billPaid    = 0;
         $billDue     = 0;
         foreach($bills as $bill)
         {
             $billTotal += $bill->getTotal();
             $billPaid  += ($bill->getTotal() - $bill->getDue());
             $billDue   += $bill->getDue();
         }

         $billDetail['billTotal'] = $billTotal;
         $billDetail['billPaid']  = $billPaid;
         $billDetail['billDue']   = $billDue;

         return $billDetail;
     }
     public function lastPayments()
    {
        return $this->hasOne(BillPayment::class, 'bill_id', 'bill_id');
    }

}
