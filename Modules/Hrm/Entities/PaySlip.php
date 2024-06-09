<?php

namespace Modules\Hrm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaySlip extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'net_payble',
        'basic_salary',
        'salary_month',
        'status',
        'allowance',
        'commission',
        'loan',
        'saturation_deduction',
        'other_payment',
        'overtime',
        'workspace',
        'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Hrm\Database\factories\PaySlipFactory::new();
    }
}
