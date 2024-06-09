<?php

namespace Modules\Hrm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'branch_id',
        'department_id',
        'employee_id',
        'description',
        'workspace',
        'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Hrm\Database\factories\AnnouncementFactory::new();
    }
    
    public function branch()
    {
        return $this->hasOne(Branch::class, 'id', 'branch_id');
    }
}
