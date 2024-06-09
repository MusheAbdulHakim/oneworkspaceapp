<?php

namespace Modules\Lead\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Source extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'created_by',
        'workspace_id',
    ];

    protected static function newFactory()
    {
        return \Modules\Lead\Database\factories\SourceFactory::new();
    }
}
