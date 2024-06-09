<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChartOfAccountSubType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'workspace',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\Account\Database\factories\ChartOfAccountSubTypeFactory::new();
    }
}
