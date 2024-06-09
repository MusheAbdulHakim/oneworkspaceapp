<?php

namespace Modules\Hrm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Designation extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'department_id',
        'name',
        'workspace',
        'created_by'
    ];
    
    protected static function newFactory()
    {
        return \Modules\Hrm\Database\factories\DesignationFactory::new();
    }

    public function branch(){
        return $this->hasOne(Branch::class,'id','branch_id');
    }
    public function department(){
        return $this->hasOne(Department::class,'id','department_id');
    }
}
