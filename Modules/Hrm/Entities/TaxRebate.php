<?php

namespace Modules\Hrm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxRebate extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'amount',
        'workspace',
        'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Hrm\Database\factories\TaxRebateFactory::new();
    }
}
