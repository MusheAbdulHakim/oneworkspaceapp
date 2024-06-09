<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'type',
        'type_id',
        'description',
        'workspace',
        'created_by'
    ];

    protected static function newFactory()
    {
        return \Modules\Account\Database\factories\StockReportFactory::new();
    }
}
