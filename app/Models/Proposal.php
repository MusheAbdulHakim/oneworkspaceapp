<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'customer_id',
        'issue_date',
        'status',
        'category_id',
        'is_convert',
        'converted_invoice_id',
        'workspace',
        'created_by',
    ];

    public static $statues = [
        'Draft',
        'Open',
        'Accepted',
        'Declined',
        'Close',
    ];
    public function customers()
    {
        return  $this->hasOne(User::class,'id','customer_id');
    }
    public function customer()
    {
        return  $this->hasOne(User::class,'id','customer_id');
    }
    public function category()
    {
        return $this->hasOne(\Modules\ProductService\Entities\Category::class, 'id', 'category_id');
    }
    public function items()
    {
        return $this->hasMany(ProposalProduct::class, 'proposal_id', 'id');
    }
    public static function proposalNumberFormat($number, $company_id = null,$workspace = null)
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
        $data = !empty($company_settings['proposal_prefix']) ? $company_settings['proposal_prefix'] : '#INVO0';
        return $data . sprintf("%05d", $number);
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
        foreach($this->items as $product)
        {
            $totalDiscount += $product->discount;
        }
        return $totalDiscount;
    }
    public function getTotalTax()
    {
        $totalTax = 0;
        foreach ($this->items as $product) {
            $taxes = Proposal::totalTaxRate($product->tax);

            $totalTax += ($taxes / 100) * (($product->price * $product->quantity) - $product->discount);
        }

        return $totalTax;
    }
    public static function taxRate($taxRate, $price, $quantity,$discount = 0)
    {
        return ($taxRate / 100) * (($price * $quantity) - $discount);
    }
    public static function tax($taxes)
    {
        $taxArr = explode(',', $taxes);
        $taxes  = [];
        if(module_is_active('ProductService'))
        {
            foreach($taxArr as $tax)
            {
                $taxes[] = \Modules\ProductService\Entities\Tax::find($tax);
            }
        }

        return $taxes;
    }
    public static function totalTaxRate($taxes)
    {
        $taxArr  = explode(',', $taxes);
        $taxRate = 0;
        if(module_is_active('ProductService'))
        {
            foreach($taxArr as $tax)
            {
                $tax     =  \Modules\ProductService\Entities\Tax::find($tax);
                $taxRate += !empty($tax->rate) ? $tax->rate : 0;
            }
        }
        return $taxRate;
    }
    public function getTotal()
    {
        return ($this->getSubTotal() - $this->getTotalDiscount() + $this->getTotalTax());
    }
}
