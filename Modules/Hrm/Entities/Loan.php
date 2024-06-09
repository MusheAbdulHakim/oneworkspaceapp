<?php

namespace Modules\Hrm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'loan_option',
        'title',
        'type',
        'amount',
        'start_date',
        'end_date',
        'reason',
        'workspace',
        'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Hrm\Database\factories\LoanFactory::new();
    }

    public static $Loantypes=[
        'fixed'=>'Fixed',
        'percentage'=> 'Percentage',
    ];
    public function loan_option()
    {
        return $this->hasOne(LoanOption::class, 'id', 'loan_option')->first();
    }

    public function loanoption()
    {
        return $this->hasOne(LoanOption::class, 'id', 'loan_option');
    }
}
