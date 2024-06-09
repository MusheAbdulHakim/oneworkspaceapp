<?php

namespace Modules\Hrm\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'user_id',
        'branch_id',
        'department_id',
        'transfer_date',
        'description',
        'workspace',
        'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Hrm\Database\factories\TransferFactory::new();
    }

    public function branch(){
        return $this->hasOne(Branch::class,'id','branch_id');
    }
    
    public function department(){
        return $this->hasOne(Department::class,'id','department_id');
    }

    public function users(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
