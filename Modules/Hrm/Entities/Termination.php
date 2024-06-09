<?php

namespace Modules\Hrm\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Termination extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'user_id',
        'notice_date',
        'termination_date',
        'termination_type',
        'description',
        'workspace',
        'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Hrm\Database\factories\TerminationFactory::new();
    }

    public function terminationType(){
        return $this->hasOne(TerminationType::class,'id','termination_type');
    }

    public function users(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
