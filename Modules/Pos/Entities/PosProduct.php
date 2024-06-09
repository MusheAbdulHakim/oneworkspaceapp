<?php

namespace Modules\Pos\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PosProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_type',
        'product_id',
        'pos_id',
        'quantity',
        'tax',
        'discount',
        'total',
        'workspace',
    ];

    protected static function newFactory()
    {
        return \Modules\Pos\Database\factories\PosProductFactory::new();
    }
    public function product(){
        return $this->hasOne(\Modules\ProductService\Entities\ProductService::class, 'id', 'product_id');   //->first()
    }

}
