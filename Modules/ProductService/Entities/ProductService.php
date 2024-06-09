<?php

namespace Modules\ProductService\Entities;

use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\WarehouseProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','sku','sale_price','purchase_price','tax_id','category_id','description','type', 'icon', 'parent_id', 'sort_order', 'route', 'is_visible','quantity', 'permissions', 'module','image','unit_id','sale_chartaccount_id','expense_chartaccount_id','workspace_id','created_by'
    ];
    public static $product_type =
    [
        'product'    => 'Products',
        'service'    => 'Services',
        'parts'      => 'Parts',
    ];

    protected static function newFactory()
    {
        return \Modules\ProductService\Database\factories\ProductServiceFactory::new();
    }

    public function units()
    {
        return $this->belongsTo('Modules\ProductService\Entities\Unit', 'unit_id');
    }

    public function categorys()
    {
        return $this->belongsTo('Modules\ProductService\Entities\Category', 'category_id');
    }
    public function taxes()
    {
        return $this->hasOne('Modules\ProductService\Entities\Tax', 'id', 'tax_id')->first();
    }

    public function unit()
    {
        return $this->hasOne('Modules\ProductService\Entities\Unit', 'id', 'unit_id')->first();
    }

    public function category()
    {
        return $this->hasOne('Modules\ProductService\Entities\Category', 'id', 'category_id');
    }
    public function taxRate($taxes)
    {
        $taxArr  = explode(',', $taxes);
        $taxRate = 0;
        foreach ($taxArr as $tax) {
            $tax     = Tax::find($tax);
            $taxRate += $tax->rate;
        }

        return $taxRate;
    }
    public function tax($taxes)
    {
        $taxArr = explode(',', $taxes);

        $taxes  = [];
        foreach ($taxArr as $tax) {
            $taxes[] = Tax::find($tax);
        }

        return $taxes;
    }
    public function getTotalProductQuantity()
    {
        if(module_is_active('Pos'))
        {
            $totalquantity = $purchasedquantity = $posquantity = 0;
            $authuser = \Auth::user();
            $product_id = $this->id;
            $purchases = Purchase::where('created_by', creatorId())->where('workspace',getActiveWorkSpace());
            if ($this->isUser())
            {
                $purchases = $purchases->where('warehouse_id', $authuser->warehouse_id);
            }
            foreach($purchases->get() as $purchase)
            {
                $purchaseditem = PurchaseProduct::select('quantity')->where('purchase_id', $purchase->id)->where('product_id', $product_id)->first();
                $purchasedquantity += $purchaseditem != null ? $purchaseditem->quantity : 0;
            }

            $poses = \Modules\Pos\Entities\Pos::where('created_by', creatorId())->where('workspace',getActiveWorkSpace());
            if ($this->isUser())
            {
                $pos = $poses->where('warehouse_id', $authuser->warehouse_id);
            }

            foreach($poses->get() as $pos)
            {
                $positem = \Modules\Pos\Entities\PosProduct::select('quantity')->where('pos_id', $pos->id)->where('product_id', $product_id)->first();
                $posquantity += $positem != null ? $positem->quantity : 0;
            }

            $totalquantity = $purchasedquantity - $posquantity;
            return $totalquantity;
        }

    }
    public function isUser()
    {
        return $this->type === 'user' ? 1 : 0;
    }

    public function warehouseProduct($product_id,$warehouse_id){
        if(module_is_active('Pos'))
        {
            $product=WarehouseProduct::where('warehouse_id',$warehouse_id)
                ->where('product_id',$product_id)
                ->where('workspace',getActiveWorkSpace())->first();

            return !empty($product)?$product->quantity:0;
        }
    }


    public static function addProductStock($product_id, $quantity, $type, $description,$type_id)
    {
        $stocks                 = new \Modules\Account\Entities\StockReport();
        $stocks->product_id     = $product_id;
        $stocks->quantity	    = $quantity;
        $stocks->type           = $type;
        $stocks->type_id        = $type_id;
        $stocks->description    = $description;
        $stocks->created_by     = creatorId();
        $stocks->workspace      = getActiveWorkSpace();
        $stocks->save();
    }



    public function getProductQuantity()
    {
        if(module_is_active('Pos'))
        {
            $totalquantity  = $posquantity = 0;
            $authuser = \Auth::user();

            $product_id = $this->id;

            $quotations = \Modules\Quotation\Entities\Quotation::where('created_by', creatorId())->where('workspace',getActiveWorkSpace());


            if ($this->isUser())
            {
                $quotation = $quotations->where('warehouse_id', $authuser->warehouse_id);
            }

            foreach($quotations->get() as $quotation)
            {
                $positem = \Modules\Quotation\Entities\QuotationProduct::select('quantity')->where('quotation_id', $quotation->id)->where('product_id', $product_id)->first();
                $totalquantity += $positem != null ? $positem->quantity : 0;
            }

            return $totalquantity;
        }
    }
}
