<?php

namespace Modules\Lead\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DealDiscussion extends Model
{
    use HasFactory;

    protected $fillable = [
        'deal_id',
        'comment',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\Lead\Database\factories\DealDiscussionFactory::new();
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }
}
