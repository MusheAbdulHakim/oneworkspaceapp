<?php

namespace Modules\Hrm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Allowance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'allowance_option',
        'title',
        'type',
        'amount',
        'workspace',
        'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Hrm\Database\factories\AllowanceFactory::new();
    }
    public static $Allowancetype = [
        'fixed'=>'Fixed',
        'percentage'=> 'Percentage',
    ];
    public function allowance_option()
    {
        return $this->hasOne(AllowanceOption::class,'id','allowance_option')->first();
    }

    public function allowanceoption()
    {
        return $this->hasOne(AllowanceOption::class, 'id', 'allowance_option');
    }
}
