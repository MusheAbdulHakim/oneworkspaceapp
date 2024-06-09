<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChartOfAccountParent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sub_type',
        'type',
        'parent',
        'workspace',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\Account\Database\factories\ChartOfAccountParentFactory::new();
    }
}
