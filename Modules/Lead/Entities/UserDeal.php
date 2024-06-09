<?php

namespace Modules\Lead\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserDeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'deal_id',
    ];

    protected static function newFactory()
    {
        return \Modules\Lead\Database\factories\UserDealFactory::new();
    }
    public function getDealUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
