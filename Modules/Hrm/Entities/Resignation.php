<?php

namespace Modules\Hrm\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Resignation extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'user_id',
        'notice_date',
        'resignation_date',
        'description',
        'workspace',
        'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Hrm\Database\factories\ResignationFactory::new();
    }

    public function users(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
