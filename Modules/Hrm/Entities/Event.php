<?php

namespace Modules\Hrm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'department_id',
        'employee_id',
        'user_id',
        'title',
        'start_date',
        'end_date',
        'color',
        'description',
        'workspace',
        'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Hrm\Database\factories\EventFactory::new();
    }

    public function branch()
    {
        return $this->hasOne(Branch::class, 'id', 'branch_id');
    }
}
