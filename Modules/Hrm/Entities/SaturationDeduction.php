<?php

namespace Modules\Hrm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaturationDeduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'deduction_option',
        'title',
        'type',
        'amount',
        'workspace',
        'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Hrm\Database\factories\SaturationDeductionFactory::new();
    }

    public static $saturationDeductiontype = [
        'fixed'=>'Fixed',
        'percentage'=> 'Percentage',
    ];

    public function deduction_option()
    {
        return $this->hasOne(DeductionOption::class,'id','deduction_option')->first();
    }

    public function deductionoption()
    {
        return $this->hasOne(DeductionOption::class, 'id', 'deduction_option');
    }
}
