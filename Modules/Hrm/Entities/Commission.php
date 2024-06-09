<?php

namespace Modules\Hrm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commission extends Model
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
        return \Modules\Hrm\Database\factories\CommissionFactory::new();
    }

    public static $commissiontype = [
        '' => 'Select Commission Type',
        'fixed'=>'Fixed',
        'percentage'=> 'Percentage',
        'period'=> 'Period',
    ];

    public static $status =[
        '' => 'Select Status',
        'active'=>'Active',
        'expired'=> 'Expired',
    ];
}
