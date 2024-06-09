<?php

namespace Modules\Lead\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DealEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'deal_id',
        'to',
        'subject',
        'description',
    ];

    protected static function newFactory()
    {
        return \Modules\Lead\Database\factories\DealEmailFactory::new();
    }
}
