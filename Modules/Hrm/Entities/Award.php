<?php

namespace Modules\Hrm\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Award extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'user_id',
        'award_type',
        'date',
        'gift',
        'description',
        'workspace',
        'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Hrm\Database\factories\AwardFactory::new();
    }

    public function awardType(){
        return $this->hasOne(AwardType::class,'id','award_type');
    }

    public function users(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
