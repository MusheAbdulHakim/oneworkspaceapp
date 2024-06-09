<?php

namespace Modules\Hrm\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Travel extends Model
{
    use HasFactory;

    protected $table = 'travels';

    protected $fillable = [
        'employee_id',
        'user_id',
        'start_date',
        'end_date',
        'purpose_of_visit',
        'place_of_visit',
        'description',
        'workspace',
        'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Hrm\Database\factories\TravelFactory::new();
    }

    public function users(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
