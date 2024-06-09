<?php

namespace Modules\Lead\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Label extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'pipeline_id',
        'workspace_id',
        'created_by',
    ];

    public static $colors = [
        'primary',
        'secondary',
        'danger',
        'warning',
        'info',
    ];

    protected static function newFactory()
    {
        return \Modules\Lead\Database\factories\LabelFactory::new();
    }
}
