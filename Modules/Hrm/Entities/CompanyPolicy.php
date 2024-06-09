<?php

namespace Modules\Hrm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyPolicy extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch',
        'title',
        'description',
        'file',
        'workspace',
        'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Hrm\Database\factories\CompanyPolicyFactory::new();
    }

    public function branches(){
        return $this->hasOne(Branch::class,'id','branch');
    }
}
