<?php

namespace Modules\Hrm\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'user_id',
        'designation_id',
        'promotion_title',
        'promotion_date',
        'description',
        'workspes',
        'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Hrm\Database\factories\PromotionFactory::new();
    }

    public function designation(){
        return $this->hasOne(Designation::class,'id','designation_id');
    }

    public function users(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
