<?php

namespace Modules\Lead\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeadDiscussion extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'comment',
    ];

    protected static function newFactory()
    {
        return \Modules\Lead\Database\factories\LeadDiscussionFactory::new();
    }
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }
}
