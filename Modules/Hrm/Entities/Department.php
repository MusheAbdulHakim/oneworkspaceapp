<?php

namespace Modules\Hrm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'name',
        'workspace',
        'created_by'
    ];
    
    protected static function newFactory()
    {
        return \Modules\Hrm\Database\factories\DepartmentFactory::new();
    }

    public function branch(){
        return $this->hasOne(Branch::class,'id','branch_id');
    }
}
