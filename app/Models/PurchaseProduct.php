<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_type',
        'product_id',
        'purchase_id',
        'quantity',
        'tax',
        'discount',
        'total',
        'workspace',
    ];

    public function product()
    {
        if(module_is_active('ProductService'))
        {
            return $this->hasOne(\Modules\ProductService\Entities\ProductService::class, 'id', 'product_id');   //->first()
        }
    }
}
