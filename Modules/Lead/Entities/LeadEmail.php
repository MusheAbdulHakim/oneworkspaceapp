<?php

namespace Modules\Lead\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeadEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'to',
        'subject',
        'description',
    ];

    protected static function newFactory()
    {
        return \Modules\Lead\Database\factories\LeadEmailFactory::new();
    }
}
