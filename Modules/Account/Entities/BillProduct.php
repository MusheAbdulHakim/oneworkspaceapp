<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\ProductService\Entities\Tax;

class BillProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_type',
        'product_id',
        'bill_id',
        'quantity',
        'tax',
        'discount',
        'total',
    ];

    protected static function newFactory()
    {
            return \Modules\Account\Database\factories\BillProductFactory::new();
    }

    public function product()
    {
        $bill =  $this->hasMany(Bill::class, 'id', 'bill_id')->first();
        if(!empty($bill) && $bill->bill_module == "account" || $bill->bill_module == '')
        {
            if(module_is_active('ProductService'))
            {
                return $this->hasOne(\Modules\ProductService\Entities\ProductService::class, 'id', 'product_id')->first();
            }
            else
            {
                return [];
            }
        }
        elseif(!empty($bill) && $bill->bill_module == "taskly")
        {
            if(module_is_active('Taskly'))
            {
                return  $this->hasOne(\Modules\Taskly\Entities\Task::class, 'id', 'product_id')->first();
            }
            else
            {
                return [];
            }
        }

    }
    public function taxes()
    {
        return $this->belongsToMany(Tax::class);
    }

}
