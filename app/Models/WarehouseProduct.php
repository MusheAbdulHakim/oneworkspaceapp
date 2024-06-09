<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'warehouse_id',
        'product_id',
        'quantity',
        'created_by',
        'workspace',
    ];

    public function product()
    {
      if(module_is_active('ProductService'))
      {
        return $this->hasOne(\Modules\ProductService\Entities\ProductService::class, 'id', 'product_id'); //->first()
      }
    }
    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id')->first();
    }
}
