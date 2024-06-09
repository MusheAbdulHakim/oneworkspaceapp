<?php

namespace Modules\Lead\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserLead extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lead_id',
    ];

    public function getLeadUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    protected static function newFactory()
    {
        return \Modules\Lead\Database\factories\UserLeadFactory::new();
    }
}
