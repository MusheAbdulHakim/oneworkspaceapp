<?php

namespace Modules\Hrm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OtherPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'title',
        'type',
        'amount',
        'workspace',
        'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Hrm\Database\factories\OtherPaymentFactory::new();
    }

    public static $otherPaymenttype=[
        'fixed'=>'Fixed',
        'percentage'=> 'Percentage',
    ];
}
